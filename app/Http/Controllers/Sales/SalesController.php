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

        /* 🔒 LEVEL-2 DUPLICATE PROTECTION */
        $existingSale = Sale::where('invoice_token', $request->invoice_token)->first();
        if ($existingSale) {
            return redirect()
                ->route('sales.show', $existingSale->id)
                ->with('error', 'Invoice already generated');
        }

        try {
            DB::transaction(function () use ($request) {

                /* ===== CALCULATE TOTAL ===== */
                $subTotal = 0;
                foreach ($request->items['product_id'] as $i => $productId) {
                    $qty   = (int) $request->items['quantity'][$i];
                    $price = (float) $request->items['price'][$i];
                    $subTotal += ($qty * $price);
                }

                $discount   = (float) ($request->discount ?? 0);
                $tax        = (float) ($request->tax ?? 0);
                $grandTotal = $subTotal - $discount + ($subTotal * $tax / 100);

                /* ===== INVOICE NUMBER ===== */
                $invoiceNo = 'INV-' . str_pad((Sale::max('id') + 1), 6, '0', STR_PAD_LEFT);

                /* ===== CREATE SALE ===== */
                $sale = Sale::create([
                    'customer_id'   => $request->customer_id,
                    'invoice_no'    => $invoiceNo,
                    'invoice_token' => $request->invoice_token,
                    'sale_date'     => now(),
                    'sub_total'     => $subTotal,
                    'discount'      => $discount,
                    'tax'           => $tax,
                    'grand_total'   => $grandTotal,
                ]);

                /* ===== SALE ITEMS + STOCK UPDATE ===== */
                foreach ($request->items['product_id'] as $i => $productId) {

                    $qty   = (int) $request->items['quantity'][$i];
                    $price = (float) $request->items['price'][$i];

                    $product = Product::lockForUpdate()->findOrFail($productId);

                    if ($product->quantity < $qty) {
                        throw new \Exception(
                            "Stock not enough for {$product->name}. Available: {$product->quantity}"
                        );
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
       SHOW SALE
    ========================================================= */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return view('sales.show', compact('sale'));
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

                /* RESTORE OLD STOCK */
                foreach ($sale->items as $item) {
                    $item->product->increment('quantity', $item->quantity);
                }

                $sale->items()->delete();

                $subTotal = 0;
                foreach ($request->items['product_id'] as $i => $pid) {
                    $subTotal += $request->items['quantity'][$i] * $request->items['price'][$i];
                }

                $discount   = (float) ($request->discount ?? 0);
                $tax        = (float) ($request->tax ?? 0);
                $grandTotal = $subTotal - $discount + ($subTotal * $tax / 100);

                $sale->update([
                    'customer_id' => $request->customer_id,
                    'sub_total'   => $subTotal,
                    'discount'    => $discount,
                    'tax'         => $tax,
                    'grand_total' => $grandTotal,
                ]);

                foreach ($request->items['product_id'] as $i => $pid) {

                    $qty   = $request->items['quantity'][$i];
                    $price = $request->items['price'][$i];

                    $product = Product::lockForUpdate()->findOrFail($pid);

                    if ($product->quantity < $qty) {
                        throw new \Exception("Stock not enough for {$product->name}");
                    }

                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $pid,
                        'quantity'   => $qty,
                        'price'      => $price,
                        'total'      => $qty * $price,
                    ]);

                    $product->decrement('quantity', $qty);
                }
            });

            return redirect()
                ->route('customers.sales', $sale->customer_id)
                ->with('success', 'Invoice updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /* =========================================================
       INVOICE PDF
    ========================================================= */
    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return Pdf::loadView('sales.invoice', compact('sale'))
            ->stream('invoice-' . $sale->invoice_no . '.pdf');
    }
}
