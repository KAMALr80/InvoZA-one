<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Payment;

class PaymentController extends Controller
{
    /* =======================
       PAYMENT FORM
    ======================= */
    public function create($saleId)
    {
        $sale = Sale::findOrFail($saleId);

        // 🚫 Already paid → block
        if ($sale->payment_status === 'paid') {
            return redirect()
                ->route('sales.show', $sale->id)
                ->with('error', 'Invoice already fully paid');
        }

        return view('payments.create', compact('sale'));
    }

    /* =======================
       STORE PAYMENT
    ======================= */
    public function store(Request $request)
    {
        /* ---------- VALIDATION ---------- */
        $request->validate([
            'sale_id'        => 'required|exists:sales,id',
            'method'         => 'required|in:cash,upi,card,net_banking',
            'amount'         => 'required|numeric|min:1',
            'transaction_id'=> 'nullable|string|max:255',
        ]);

        $sale = Sale::findOrFail($request->sale_id);

        // 🚫 Safety: already paid
        if ($sale->payment_status === 'paid') {
            return redirect()
                ->route('sales.show', $sale->id)
                ->with('error', 'Invoice already fully paid');
        }

        /* ---------- REMAINING AMOUNT ---------- */
        $alreadyPaid = $sale->payments()->sum('amount');
        $remaining   = $sale->grand_total - $alreadyPaid;

        if ($request->amount > $remaining) {
            return back()
                ->withInput()
                ->with('error', 'Payment amount exceeds remaining balance');
        }

        /* ---------- SAVE PAYMENT (ALWAYS PAID) ---------- */
        DB::transaction(function () use ($request, $sale) {

            Payment::create([
                'sale_id'        => $sale->id,
                'amount'         => $request->amount,
                'method'         => $request->method,
                'status'         => 'paid', // 🔥 NO PENDING ANYMORE
                'transaction_id'=> $request->transaction_id,
            ]);

            // 🔄 Recalculate total paid
            $totalPaid = $sale->payments()->sum('amount');

            // 🧾 Update sale status
            if ($totalPaid >= $sale->grand_total) {
                $sale->payment_status = 'paid';
            } elseif ($totalPaid > 0) {
                $sale->payment_status = 'partial';
            } else {
                $sale->payment_status = 'unpaid';
            }

            $sale->save();
        });

        return redirect()
            ->route('sales.show', $sale->id)
            ->with('success', 'Payment successful via ' . strtoupper($request->method));
    }
}
