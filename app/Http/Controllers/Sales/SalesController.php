<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use App\Models\EmiPlan;

use NumberFormatter;

class SalesController extends Controller
{
    /* =========================================================
       AJAX CUSTOMER CREATE (SMART + DUPLICATE SAFE)
    ========================================================= */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
        ]);

        // 🔍 Check existing customer by mobile
        $existing = Customer::where('mobile', $request->mobile)->first();

        if ($existing) {
            return response()->json([
                'exists'   => true,
                'customer' => $existing,
                'message'  => 'Customer already exists',
            ]);
        }

        $customer = Customer::create([
            'name'    => $request->name,
            'mobile'  => $request->mobile,
            'email'   => $request->email,
            'address' => $request->address,
            'gst_no'  => $request->gst_no,
        ]);

        return response()->json([
            'exists'   => false,
            'customer' => $customer,
            'message'  => 'Customer created successfully',
        ]);
    }

    /* =========================================================
       SALES LIST
    ========================================================= */
    public function index()
    {
        $sales = Sale::with('customer')
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    /* =========================================================
       CREATE SALE PAGE
    ========================================================= */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::where('quantity', '>', 0)->get();

        return view('sales.create', compact('customers', 'products'));
    }

    /* =========================================================
       STORE SALE (DUPLICATE + STOCK SAFE)
    ========================================================= */
public function store(Request $request)
{
    $request->validate([
        'customer_id'        => 'required|exists:customers,id',
        'invoice_token'      => 'required|string',
        'items.product_id'   => 'required|array|min:1',
        'items.quantity'     => 'required|array|min:1',
        'items.price'        => 'required|array|min:1',
    ]);

    /* 🔒 Duplicate Invoice Protection */
    $existingSale = Sale::where('invoice_token', $request->invoice_token)->first();
    if ($existingSale) {
        return redirect()
            ->route('sales.show', $existingSale->id)
            ->with('error', 'Invoice already generated');
    }

    try {
        DB::transaction(function () use ($request) {

            /* ================= CALCULATE TOTAL ================= */
            $subTotal = 0;
            foreach ($request->items['product_id'] as $i => $productId) {
                $qty   = (int) $request->items['quantity'][$i];
                $price = (float) $request->items['price'][$i];
                $subTotal += ($qty * $price);
            }

            $discount   = (float) ($request->discount ?? 0);
            $tax        = (float) ($request->tax ?? 0);
            $grandTotal = $subTotal - $discount + ($subTotal * $tax / 100);

            /* ================= CUSTOMER ================= */
            $customer = Customer::lockForUpdate()->findOrFail($request->customer_id);

            /* ================= INVOICE NUMBER ================= */
            $invoiceNo = 'INV-' . str_pad((Sale::max('id') + 1), 6, '0', STR_PAD_LEFT);

            /* ================= CREATE SALE ================= */
            $sale = Sale::create([
                'customer_id'   => $customer->id,
                'invoice_no'    => $invoiceNo,
                'invoice_token' => $request->invoice_token,
                'sale_date'     => now(),
                'sub_total'     => $subTotal,
                'discount'      => $discount,
                'tax'           => $tax,
                'grand_total'   => $grandTotal,
                'payment_status'=> 'unpaid',
            ]);

            /* ================= SALE ITEMS + STOCK ================= */
            foreach ($request->items['product_id'] as $i => $productId) {

                $qty   = (int) $request->items['quantity'][$i];
                $price = (float) $request->items['price'][$i];

                $product = Product::lockForUpdate()->findOrFail($productId);

                if ($product->quantity < $qty) {
                    throw new \Exception("Stock not enough for {$product->name}");
                }

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $price,
                    'total'      => $qty * $price,
                ]);

                $product->decrement('quantity', $qty);
            }

            /* ================= OPEN BALANCE AUTO ADJUST ================= */

            $openBalance = $customer->open_balance ?? 0;
            $advanceAvailable = $openBalance < 0 ? abs($openBalance) : 0;
            $advanceUsed = min($advanceAvailable, $grandTotal);
            $remainingPayable = $grandTotal - $advanceUsed;

            if ($advanceUsed > 0) {

                // update customer open balance
                $customer->update([
                    'open_balance' => $openBalance + $advanceUsed
                ]);

                // wallet ledger
                \App\Models\CustomerWallet::create([
                    'customer_id' => $customer->id,
                    'type'        => 'debit',
                    'amount'      => $advanceUsed,
                    'balance'     => $customer->open_balance,
                    'reference'   => 'Adjusted against Invoice ' . $sale->invoice_no,
                ]);

                // payment record
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount'  => $advanceUsed,
                    'method'  => 'advance',
                    'status'  => 'paid',
                ]);
            }

            /* ================= UPDATE SALE STATUS ================= */
            $sale->update([
                'payment_status' => $remainingPayable <= 0
                    ? 'paid'
                    : ($advanceUsed > 0 ? 'partial' : 'unpaid'),
            ]);
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale created successfully');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}


    /* =========================================================
       SHOW SALE (FIXED - Was missing)
    ========================================================= */
     /* ================= SHOW (🔥 EMI AWARE) ================= */
    public function show(Sale $sale)
    {
        $sale->load(['customer','items.product']);

        $emi = EmiPlan::where('sale_id',$sale->id)
            ->where('status','running')
            ->first();

        $emiData = null;

        if ($emi) {
            $total = $emi->emi_amount * $emi->months;

            $paid = Payment::where('sale_id',$sale->id)
                ->whereIn('method',['emi','advance'])
                ->sum('amount');

            $remaining = max($total - $paid, 0);

            $nextPay = min($emi->emi_amount, $remaining);

            $emiData = [
                'emi'        => $emi,
                'total'      => $total,
                'paid'       => $paid,
                'remaining'  => $remaining,
                'nextPay'    => $nextPay,
                'completed'  => $remaining <= 0
            ];
        }

        return view('sales.show', compact('sale','emiData'));
    }
    /* =========================================================
       VIEW SALE (ADDED - This is what's being called)
    ========================================================= */
    public function view(Sale $sale)
    {
        // This method is an alias for show() method
        return $this->show($sale);
    }

    /* =========================================================
       EDIT SALE
    ========================================================= */
    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        $customers = Customer::all();
        $products  = Product::all();

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    /* =========================================================
       UPDATE SALE
    ========================================================= */
  public function update(Request $request, Sale $sale)
{
    $request->validate([
        'items.product_id' => 'required|array|min:1',
        'items.quantity'   => 'required|array|min:1',
        'items.price'      => 'required|array|min:1',
    ]);

    try {
        DB::transaction(function () use ($request, $sale) {

            $customer = \App\Models\Customer::lockForUpdate()
                ->findOrFail($sale->customer_id);

            /* =====================================================
               1️⃣ ROLLBACK OLD EFFECT (IMPORTANT)
            ===================================================== */

            // old paid amount
            $oldPaid = $sale->payments()
                ->where('status', 'paid')
                ->sum('amount');

            // old due effect remove
            if ($sale->payment_status !== 'paid') {
                $customer->open_balance -= ($sale->grand_total - $oldPaid);
            }

            // rollback advance usage
            $advanceUsed = $sale->payments()
                ->where('method', 'advance')
                ->sum('amount');

            if ($advanceUsed > 0) {
                $customer->open_balance -= $advanceUsed;
            }

            /* =====================================================
               2️⃣ RESTORE OLD STOCK
            ===================================================== */
            foreach ($sale->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $sale->items()->delete();

            /* =====================================================
               3️⃣ RECALCULATE TOTAL
            ===================================================== */
            $subTotal = 0;
            foreach ($request->items['product_id'] as $i => $pid) {
                $subTotal += $request->items['quantity'][$i] * $request->items['price'][$i];
            }

            $discount   = (float) ($request->discount ?? 0);
            $tax        = (float) ($request->tax ?? 0);
            $grandTotal = $subTotal - $discount + ($subTotal * $tax / 100);

            $sale->update([
                'sub_total'   => $subTotal,
                'discount'    => $discount,
                'tax'         => $tax,
                'grand_total' => $grandTotal,
            ]);

            /* =====================================================
               4️⃣ ADD NEW ITEMS + STOCK
            ===================================================== */
            foreach ($request->items['product_id'] as $i => $pid) {

                $qty   = $request->items['quantity'][$i];
                $price = $request->items['price'][$i];

                $product = \App\Models\Product::lockForUpdate()->findOrFail($pid);

                if ($product->quantity < $qty) {
                    throw new \Exception("Stock not enough for {$product->name}");
                }

                \App\Models\SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $pid,
                    'quantity'   => $qty,
                    'price'      => $price,
                    'total'      => $qty * $price,
                ]);

                $product->decrement('quantity', $qty);
            }

            /* =====================================================
               5️⃣ APPLY ADVANCE AGAIN (STEP-7 LOGIC)
            ===================================================== */

            $openBalance = $customer->open_balance ?? 0;
            $advanceAvailable = $openBalance < 0 ? abs($openBalance) : 0;

            $advanceUsed = min($advanceAvailable, $grandTotal);
            $remaining   = $grandTotal - $advanceUsed;

            if ($advanceUsed > 0) {
                $customer->open_balance += $advanceUsed;

                \App\Models\Payment::create([
                    'sale_id' => $sale->id,
                    'amount'  => $advanceUsed,
                    'method'  => 'advance',
                    'status'  => 'paid',
                ]);
            }

            if ($remaining <= 0) {

                $sale->payment_status = 'paid';

            } elseif ($advanceUsed > 0) {

                $sale->payment_status = 'partial';
                $customer->open_balance += $remaining;

            } else {

                $sale->payment_status = 'unpaid';
                $customer->open_balance += $grandTotal;
            }
        /* =====================================================
   8️⃣ EMI PLAN SYNC (IF EXISTS)
===================================================== */

$emi = $sale->emiPlan;

if ($emi && $emi->status === 'running') {

    // total EMI already paid
    $emiPaid = \App\Models\Payment::where('sale_id', $sale->id)
        ->where('method', 'emi')
        ->sum('amount');

    $downPayment = $emi->down_payment;

    // NEW remaining based on updated invoice
    $newRemaining = max(0, $sale->grand_total - $downPayment - $emiPaid);

    // update customer open balance
    $customer->open_balance = $newRemaining;

    // EMI completed automatically
    if ($newRemaining <= 0) {

        $emi->status = 'completed';
        $emi->months = 0;

        $sale->payment_status = 'paid';
        $customer->open_balance = 0;

    } else {

        // EMI still running
        $emi->status = 'running';

        // recalc remaining months (safe)
        $emi->months = (int) ceil($newRemaining / $emi->emi_amount);

        $sale->payment_status = 'emi';
    }

    $emi->save();
}

            $customer->save();
            $sale->save();


        });

        return redirect()
            ->route('customers.sales', $sale->customer_id)
            ->with('success', 'Invoice updated successfully');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}


 /* =========================================================
   INVOICE PDF (FIXED + AMOUNT IN WORDS)
========================================================= */
 public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);

        /**
         * Amount in Words
         * intl ho to words
         * intl na ho to fallback
         */
        if (class_exists('\NumberFormatter')) {
            $formatter = new \NumberFormatter('en_IN', \NumberFormatter::SPELLOUT);
            $amountInWords = ucfirst($formatter->format($sale->grand_total)) . ' only';
        } else {
            // ❌ intl not available → never crash
            $amountInWords = number_format($sale->grand_total, 2) . ' only';
        }

        return Pdf::loadView('sales.invoice', [
            'sale' => $sale,
            'amountInWords' => $amountInWords
        ])->stream('Invoice-' . $sale->invoice_no . '.pdf');
    }


    /* =========================================================
       DESTROY SALE (ADDED)
    ========================================================= */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Restore stock
            foreach ($sale->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            // Delete sale items
            $sale->items()->delete();

            // Delete sale
            $sale->delete();

            DB::commit();

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale deleted and stock restored successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }

    /* =========================================================
       PRINT INVOICE (OPTIONAL - For browser view)
    ========================================================= */
    public function print(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return view('sales.print', compact('sale'));
    }
}
