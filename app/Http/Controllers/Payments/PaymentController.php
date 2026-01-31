<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\EmiPlan;
use App\Models\Customer;

class PaymentController extends Controller
{
    /* =======================
       PAYMENT FORM
    ======================= */
    public function create($saleId)
{
    $sale = Sale::with('emiPlan')->findOrFail($saleId);

    // 🔒 EMI started → no manual payment allowed
    if ($sale->payment_status === 'emi') {
        return redirect()
            ->route('sales.show', $sale->id)
            ->with('error', 'EMI is running. Please pay EMI only.');
    }

    if ($sale->payment_status === 'paid') {
        return redirect()
            ->route('sales.show', $sale->id)
            ->with('error', 'Invoice already fully paid');
    }

    // existing remaining logic
    $totalPaid = $sale->payments()
        ->where('status', 'paid')
        ->sum('amount');

    $remaining = max(0, $sale->grand_total - $totalPaid);

    return view('payments.create', compact('sale', 'remaining'));
}


    /* =======================
       STORE PAYMENT
    ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'method'  => 'required|in:cash,upi,card,net_banking,emi,advance',

            'amount' => [
                'exclude_if:method,advance',
                'exclude_if:method,emi',
                'required',
                'numeric',
                'min:1'
            ],

            'emi_months' => [
                'exclude_unless:method,emi',
                'required',
                'integer',
                'min:1'
            ],
            'emi_amount' => [
                'exclude_unless:method,emi',
                'required',
                'numeric',
                'min:1'
            ],

            'advance_amount' => [
                'exclude_unless:method,advance',
                'required',
                'numeric',
                'min:1'
            ],

            'transaction_id' => 'nullable|string|max:255',
        ]);

        $sale     = Sale::with('payments')->findOrFail($request->sale_id);
        $customer = Customer::lockForUpdate()->findOrFail($sale->customer_id);

        DB::transaction(function () use ($request, $sale, $customer) {

            /* =====================
               SINGLE SOURCE OF TRUTH
            ===================== */
            $totalPaidBefore = $sale->payments()
                ->where('status', 'paid')
                ->sum('amount');

            $remainingBefore = max(0, $sale->grand_total - $totalPaidBefore);


/* ================= ADVANCE ================= */
if ($request->method === 'advance') {

    $paid = $request->advance_amount;

    /*
    |----------------------------------------------------
    | STEP 1: Calculate how much bill is actually pending
    |----------------------------------------------------
    */
    $totalPaidBefore = $sale->payments()
        ->where('status', 'paid')
        ->sum('amount');

    $remainingBefore = max(0, $sale->grand_total - $totalPaidBefore);

    /*
    |----------------------------------------------------
    | STEP 2: Split advance into bill-payment + extra
    |----------------------------------------------------
    */
    $billPayment  = min($paid, $remainingBefore);
    $extraAdvance = max(0, $paid - $remainingBefore);

    /*
    |----------------------------------------------------
    | STEP 3: Record ONLY bill part as sale payment
    |----------------------------------------------------
    */
    if ($billPayment > 0) {
        Payment::create([
            'sale_id' => $sale->id,
            'amount'  => $billPayment,
            'method'  => 'advance',
            'status'  => 'paid',
        ]);
    }

    /*
    |----------------------------------------------------
    | STEP 4: Update sale status
    |----------------------------------------------------
    */
    if ($billPayment == $remainingBefore) {
        $sale->payment_status = 'paid';
    } else {
        $sale->payment_status = 'partial';
    }

    /*
    |----------------------------------------------------
    | STEP 5: Handle EXTRA advance correctly
    |----------------------------------------------------
    | open_balance:
    | +ve = due
    | -ve = advance
    */
    if ($extraAdvance > 0) {
        $customer->open_balance -= $extraAdvance;
    }

    $sale->save();
    $customer->save();
    return;
}


            /* ================= EMI START ================= */
           if ($request->method === 'emi') {

    $downPayment = $request->amount;

    if ($downPayment >= $remainingBefore) {
        throw new \Exception('EMI not allowed for full payment');
    }

    Payment::create([
        'sale_id' => $sale->id,
        'amount'  => $downPayment,
        'method'  => 'emi',
        'status'  => 'paid',
    ]);

    $emiRemaining = $remainingBefore - $downPayment;

    EmiPlan::create([
        'sale_id'      => $sale->id,
        'total_amount' => $remainingBefore,
        'down_payment' => $downPayment,
        'months'       => $request->emi_months,
        'emi_amount'   => $request->emi_amount,
        'status'       => 'running',
    ]);

    // 🔥 EMI due add ONLY ONCE
    $customer->open_balance += $emiRemaining;

    $sale->payment_status = 'emi';

    $sale->save();
    $customer->save();
    return;
}


            /* ================= NORMAL PAYMENT ================= */
          $paid = $request->amount;

Payment::create([
    'sale_id' => $sale->id,
    'amount'  => $paid,
    'method'  => $request->method,
    'status'  => 'paid',
]);

$remainingAfter = max(0, $remainingBefore - $paid);

// 🔥 same difference logic
$customer->open_balance += ($remainingAfter - $remainingBefore);

$sale->payment_status = $remainingAfter == 0 ? 'paid' : 'partial';

$sale->save();
$customer->save();

        });

        return redirect()
            ->route('sales.show', $sale->id)
            ->with('success', 'Payment processed successfully');
    }
}
