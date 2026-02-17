<?php
// app/Http/Controllers/Payments/PaymentController.php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\EmiPlan;

class PaymentController extends Controller
{
    /**
     * Show payment form
     */
    public function create($saleId)
    {
        $sale = Sale::with('customer', 'payments')->findOrFail($saleId);

        // If already paid
        if ($sale->payment_status === 'paid') {
            return redirect()->route('sales.show', $sale->id)
                ->with('error', 'Invoice already fully paid.');
        }

        // If EMI is running
        if ($sale->payment_status === 'emi') {
            return redirect()->route('sales.show', $sale->id)
                ->with('error', 'EMI is running. Please pay EMI only.');
        }

        // Calculate paid amount for this invoice only
        $paidAmount = Payment::where('sale_id', $sale->id)
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->where('remarks', 'INVOICE')
                    ->orWhere('remarks', 'EMI_DOWN')
                    ->orWhere('remarks', 'ADVANCE_USED');
            })
            ->sum('amount');

        $remaining = $sale->grand_total - $paidAmount;

        // Get customer balance details
        $advanceBalance = 0;
        $dueBalance = 0;

        if ($sale->customer) {
            $customer = $sale->customer;
            // open_balance > 0 means customer owes us (due from previous invoices)
            // open_balance < 0 means customer has advance with us
            if ($customer->open_balance > 0) {
                $dueBalance = $customer->open_balance;
            } elseif ($customer->open_balance < 0) {
                $advanceBalance = abs($customer->open_balance);
            }
        }

        return view('payments.create', compact(
            'sale',
            'remaining',
            'advanceBalance',
            'dueBalance',
            'paidAmount'
        ));
    }

    /**
     * Store payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'payment_type' => 'required|in:full,partial,excess',
            'method' => 'required|in:cash,upi,card,net_banking,emi,advance',
            'payment_amount' => 'nullable|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'advance_used' => 'nullable|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'emi_months' => 'nullable|integer|min:1',
            'emi_amount' => 'nullable|numeric|min:0',
            'transaction_id' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'is_advance_only' => 'nullable|in:0,1',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::lockForUpdate()->findOrFail($request->sale_id);
            $customer = Customer::lockForUpdate()->findOrFail($sale->customer_id);

            // Check payment type from tabs
            if ($request->is_advance_only == '1') {
                // PURE ADVANCE PAYMENT
                $this->processPureAdvance($request, $customer);
            } elseif ($request->method == 'emi') {
                // EMI PAYMENT
                $this->processEmiPayment($request, $sale, $customer);
            } else {
                // INVOICE PAYMENT (with or without advance)
                $this->processInvoicePayment($request, $sale, $customer);
            }

            DB::commit();

            return redirect()->route('sales.show', $sale->id)
                ->with('success', 'Payment recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Process pure advance payment
     */
    private function processPureAdvance($request, $customer)
    {
        $amount = $request->advance_amount ?? 0;

        if ($amount <= 0) {
            throw new \Exception('Please enter a valid advance amount.');
        }

        // Create advance payment record
        Payment::create([
            'customer_id' => $customer->id,
            'amount' => $amount,
            'method' => $request->method,
            'status' => 'paid',
            'remarks' => 'ADVANCE_ONLY',
            'transaction_id' => $request->transaction_id,
        ]);

        // Update customer balance (negative = advance given to us)
        // When customer gives advance, open_balance decreases (becomes more negative)
        $customer->open_balance -= $amount;
        $customer->save();
    }

    /**
     * Process invoice payment
     */
    private function processInvoicePayment($request, $sale, $customer)
    {
        $cashAmount = $request->payment_amount ?? 0;
        $advanceUsed = $request->advance_used ?? 0;

        $totalPayment = $cashAmount + $advanceUsed;

        if ($totalPayment <= 0) {
            throw new \Exception('Please enter a payment amount or use advance.');
        }

        // Get total paid for this invoice so far (including both INVOICE and ADVANCE_USED)
        $paidSoFar = Payment::where('sale_id', $sale->id)
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->where('remarks', 'INVOICE')
                    ->orWhere('remarks', 'EMI_DOWN')
                    ->orWhere('remarks', 'ADVANCE_USED'); // IMPORTANT: Include advance used
            })
            ->sum('amount');

        $remainingDue = $sale->grand_total - $paidSoFar;

        // Check if using advance
        if ($advanceUsed > 0) {
            // Check if customer has enough advance (open_balance negative means advance)
            $availableAdvance = $customer->open_balance < 0 ? abs($customer->open_balance) : 0;

            if ($advanceUsed > $availableAdvance) {
                throw new \Exception('Insufficient advance balance. Available: ₹' . number_format($availableAdvance, 2));
            }

            // Record advance usage - THIS COUNTS TOWARD INVOICE PAYMENT
            Payment::create([
                'sale_id' => $sale->id,
                'customer_id' => $customer->id,
                'amount' => $advanceUsed,
                'method' => 'advance',
                'status' => 'paid',
                'remarks' => 'ADVANCE_USED', // This now counts toward invoice
                'transaction_id' => $request->transaction_id,
            ]);

            // Reduce advance balance (move towards zero/positive)
            $customer->open_balance += $advanceUsed;
        }

        // Record cash payment
        if ($cashAmount > 0) {
            // Calculate how much goes to invoice vs excess
            $remainingAfterAdvance = $remainingDue - $advanceUsed;
            $invoicePortion = min($cashAmount, max(0, $remainingAfterAdvance));
            $excess = $cashAmount - $invoicePortion;

            // Invoice payment
            if ($invoicePortion > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'customer_id' => $customer->id,
                    'amount' => $invoicePortion,
                    'method' => $request->method,
                    'status' => 'paid',
                    'remarks' => 'INVOICE',
                    'transaction_id' => $request->transaction_id,
                ]);
            }

            // Excess goes to advance (customer overpaid)
            if ($excess > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'customer_id' => $customer->id,
                    'amount' => $excess,
                    'method' => $request->method,
                    'status' => 'paid',
                    'remarks' => 'EXCESS_TO_ADVANCE',
                    'transaction_id' => $request->transaction_id,
                ]);

                $customer->open_balance -= $excess;
            }
        }

        // Recalculate total paid for invoice including ALL payment types
        $newPaidAmount = Payment::where('sale_id', $sale->id)
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->where('remarks', 'INVOICE')
                    ->orWhere('remarks', 'EMI_DOWN')
                    ->orWhere('remarks', 'ADVANCE_USED'); // Include advance used
            })
            ->sum('amount');

        $newRemaining = $sale->grand_total - $newPaidAmount;

        // Update sale payment status - Using allowed enum values: unpaid, partial, paid, emi
        if ($newRemaining <= 0.01) { // Small tolerance for floating point
            $sale->payment_status = 'paid';

            // If invoice becomes paid, remove any due record if exists
            Payment::where('sale_id', $sale->id)
                ->where('remarks', 'INVOICE_DUE')
                ->delete();
        } elseif ($newRemaining < $sale->grand_total) {
            $sale->payment_status = 'partial';
        } else {
            $sale->payment_status = 'unpaid'; // Changed from 'pending' to 'unpaid'
        }

        $sale->save();
        $customer->save();
    }

    /**
     * Process EMI payment
     */
    private function processEmiPayment($request, $sale, $customer)
    {
        $downPayment = $request->down_payment ?? 0;
        $emiMonths = $request->emi_months ?? 0;
        $emiAmount = $request->emi_amount ?? 0;

        if ($downPayment <= 0) {
            throw new \Exception('Please enter down payment amount.');
        }

        if ($emiMonths <= 0) {
            throw new \Exception('Please select EMI months.');
        }

        if ($emiAmount <= 0) {
            throw new \Exception('EMI amount calculation failed.');
        }

        // Get paid amount so far
        $paidSoFar = Payment::where('sale_id', $sale->id)
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->where('remarks', 'INVOICE')
                    ->orWhere('remarks', 'EMI_DOWN');
            })
            ->sum('amount');

        $remainingDue = $sale->grand_total - $paidSoFar;

        if ($downPayment >= $remainingDue) {
            throw new \Exception('Down payment cannot be equal to or greater than remaining amount. Use regular payment instead.');
        }

        // Record down payment
        Payment::create([
            'sale_id' => $sale->id,
            'customer_id' => $customer->id,
            'amount' => $downPayment,
            'method' => $request->method,
            'status' => 'paid',
            'remarks' => 'EMI_DOWN',
            'transaction_id' => $request->transaction_id,
        ]);

        // Create EMI plan
        EmiPlan::create([
            'sale_id' => $sale->id,
            'total_amount' => $remainingDue,
            'down_payment' => $downPayment,
            'months' => $emiMonths,
            'emi_amount' => $emiAmount,
            'status' => 'running',
        ]);

        // Update sale status
        $sale->payment_status = 'emi';
        $sale->save();

        // No change to customer balance for EMI
        // Balance will be updated when monthly EMIs are paid
    }

    /**
     * Mark invoice as due (add to customer balance)
     */
    public function markAsDue(Sale $sale)
    {
        DB::beginTransaction();

        try {
            // Calculate paid amount
            $paidAmount = Payment::where('sale_id', $sale->id)
                ->where('status', 'paid')
                ->where(function ($q) {
                    $q->where('remarks', 'INVOICE')
                        ->orWhere('remarks', 'EMI_DOWN')
                        ->orWhere('remarks', 'ADVANCE_USED');
                })
                ->sum('amount');

            $remaining = $sale->grand_total - $paidAmount;

            if ($remaining <= 0) {
                return back()->with('error', 'Invoice is already fully paid.');
            }

            // Check if already marked as due
            $alreadyDue = Payment::where('sale_id', $sale->id)
                ->where('remarks', 'INVOICE_DUE')
                ->exists();

            if ($alreadyDue) {
                return back()->with('error', 'Invoice already marked as due.');
            }

            // Create due record
            Payment::create([
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'amount' => $remaining,
                'method' => 'due',
                'status' => 'pending',
                'remarks' => 'INVOICE_DUE',
            ]);

            // Update customer balance (positive = they owe us)
            $customer = $sale->customer;
            $customer->open_balance += $remaining;
            $customer->save();

            DB::commit();

            return back()->with('success', 'Invoice marked as due. ₹' . number_format($remaining, 2) . ' added to customer balance.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error marking invoice as due: ' . $e->getMessage());
        }
    }


  public function deleteBulk($saleId)
    {
        DB::beginTransaction();

        try {
            $currentSale = Sale::lockForUpdate()->findOrFail($saleId);
            $customer = Customer::lockForUpdate()->findOrFail($currentSale->customer_id);

            // Get all payments for current sale
            $currentPayments = Payment::where('sale_id', $saleId)->get();

            if ($currentPayments->isEmpty()) {
                return back()->with('error', 'No transactions found for this invoice.');
            }

            $transactionCount = $currentPayments->count();
            $totalAmount = $currentPayments->sum('amount');

            // STEP 1: Calculate advance generated from this invoice (EXCESS_TO_ADVANCE + ADVANCE_ONLY)
            $advanceGenerated = $currentPayments
                ->where('status', 'paid')
                ->whereIn('remarks', ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY'])
                ->sum('amount');

            // STEP 2: Calculate advance used in this invoice
            $advanceUsed = $currentPayments
                ->where('status', 'paid')
                ->where('remarks', 'ADVANCE_USED')
                ->sum('amount');

            // STEP 3: Find and delete advance used in OTHER invoices (jo advance generate hua tha yahan se)
            if ($advanceGenerated > 0) {
                // Find all ADVANCE_USED entries in OTHER invoices
                $advanceUsedInOtherInvoices = Payment::where('customer_id', $customer->id)
                    ->where('sale_id', '!=', $saleId) // Current invoice ke alawa
                    ->where('remarks', 'ADVANCE_USED')
                    ->where('status', 'paid')
                    ->orderBy('created_at', 'asc')
                    ->get();

                $advanceToDelete = $advanceGenerated;
                $deletedFromInvoices = [];

                foreach ($advanceUsedInOtherInvoices as $advancePayment) {
                    if ($advanceToDelete <= 0) break;

                    $amountToDelete = min($advancePayment->amount, $advanceToDelete);

                    if ($amountToDelete >= $advancePayment->amount) {
                        // Poora payment delete karo
                        $otherSaleId = $advancePayment->sale_id;
                        $advancePayment->delete();

                        // Store for recalculation
                        if (!in_array($otherSaleId, $deletedFromInvoices)) {
                            $deletedFromInvoices[] = $otherSaleId;
                        }
                    } else {
                        // Partial delete - amount kam karo
                        $advancePayment->amount -= $amountToDelete;
                        $advancePayment->save();

                        if (!in_array($advancePayment->sale_id, $deletedFromInvoices)) {
                            $deletedFromInvoices[] = $advancePayment->sale_id;
                        }
                    }

                    $advanceToDelete -= $amountToDelete;
                }

                // STEP 4: Recalculate all affected invoices
                foreach ($deletedFromInvoices as $affectedSaleId) {
                    $this->recalculateSingleInvoice($affectedSaleId);
                }

                // STEP 5: Update customer balance - Advance generated hat raha hai
                // open_balance negative tha (advance), ab aur negative hoga? Ya positive?
                // EXCESS_TO_ADVANCE: open_balance negative hota hai (customer ne advance diya)
                // Isko delete karne se advance kam hoga, to open_balance positive ki taraf jayega
                $customer->open_balance += $advanceGenerated;
            }

            // STEP 6: Handle advance used in current invoice
            if ($advanceUsed > 0) {
                // Jo advance use hua tha current invoice mein, wo ab wapas jayega
                // open_balance negative tha, ab aur negative hoga (advance badhega)
                $customer->open_balance -= $advanceUsed;
            }

            // STEP 7: Delete ALL payments of current invoice
            foreach ($currentPayments as $payment) {
                $payment->delete();
            }

            $customer->save();

            // STEP 8: Update current invoice status to unpaid
            $currentSale->payment_status = 'unpaid';
            $currentSale->save();

            // STEP 9: Delete EMI plan if exists
            if ($currentSale->emiPlan) {
                $currentSale->emiPlan->delete();
            }

            // STEP 10: Recalculate ALL invoices for this customer to ensure everything is correct
            $this->recalculateAllCustomerInvoices($customer->id);

            DB::commit();

            $message = "✅ SMART DELETE COMPLETED!\n\n";
            $message .= "📄 Invoice #{$currentSale->invoice_no}:\n";
            $message .= "   - {$transactionCount} transactions deleted\n";
            $message .= "   - Invoice is now UNPAID (Due: ₹" . number_format($currentSale->grand_total, 2) . ")\n\n";

            if ($advanceGenerated > 0) {
                $message .= "💰 ADVANCE IMPACT:\n";
                $message .= "   - Advance generated: ₹" . number_format($advanceGenerated, 2) . "\n";
                $message .= "   - Removed from " . count($deletedFromInvoices) . " other invoice(s)\n";
                $message .= "   - Advance balance DECREASED by ₹" . number_format($advanceGenerated, 2) . "\n\n";
            }

            if ($advanceUsed > 0) {
                $message .= "🔄 ADVANCE USAGE IMPACT:\n";
                $message .= "   - Advance used in this invoice: ₹" . number_format($advanceUsed, 2) . "\n";
                $message .= "   - Advance balance INCREASED by ₹" . number_format($advanceUsed, 2) . "\n\n";
            }

            $message .= "💰 NEW CUSTOMER BALANCE: ₹" . number_format($customer->open_balance, 2);

            return redirect()->back()->with('success', nl2br($message));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete single payment
     */
    public function destroy(Payment $payment)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::lockForUpdate()->findOrFail($payment->customer_id);
            $saleId = $payment->sale_id;
            $remarks = $payment->remarks;
            $amount = $payment->amount;

            // Reverse the effect based on payment type
            switch ($remarks) {
                case 'ADVANCE_ONLY':
                    // Customer gave advance, deleting it reduces advance balance
                    $customer->open_balance += $amount;
                    break;

                case 'EXCESS_TO_ADVANCE':
                    // Overpaid amount that went to advance
                    $customer->open_balance += $amount;
                    break;

                case 'ADVANCE_USED':
                    // Customer used advance for invoice
                    $customer->open_balance -= $amount;
                    break;

                case 'INVOICE_DUE':
                    // Invoice was marked as due
                    $customer->open_balance -= $amount;
                    break;

                case 'INVOICE':
                case 'EMI_DOWN':
                    // Direct invoice payment - no effect on customer balance
                    break;
            }

            $customer->save();

            // Delete the payment
            $payment->delete();

            // Recalculate affected invoice
            if ($saleId) {
                $this->recalculateSingleInvoice($saleId);
            }

            // Recalculate all invoices (advance balance change)
            $this->recalculateAllCustomerInvoices($customer->id);

            DB::commit();

            $balanceMsg = $customer->open_balance > 0 ?
                "Due: ₹" . number_format($customer->open_balance, 2) :
                "Advance: ₹" . number_format(abs($customer->open_balance), 2);

            return redirect()->back()->with('success',
                "✅ Transaction deleted successfully!\n" .
                "Type: " . str_replace('_', ' ', $remarks) . "\n" .
                "Amount: ₹" . number_format($amount, 2) . "\n" .
                "New Balance: " . $balanceMsg
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Recalculate single invoice status
     */
    private function recalculateSingleInvoice($saleId)
    {
        $sale = Sale::find($saleId);
        if (!$sale) return;

        // Calculate total paid for this invoice
        $totalPaid = Payment::where('sale_id', $saleId)
            ->where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');

        $grandTotal = $sale->grand_total;
        $remaining = $grandTotal - $totalPaid;

        // Determine new status
        if ($remaining <= 0.01) {
            $newStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $newStatus = 'partial';
        } else {
            $newStatus = 'unpaid';
        }

        $sale->payment_status = $newStatus;
        $sale->save();
    }

    /**
     * Recalculate all invoices for a customer
     */
    private function recalculateAllCustomerInvoices($customerId)
    {
        $invoices = Sale::where('customer_id', $customerId)->get();

        foreach ($invoices as $invoice) {
            $this->recalculateSingleInvoice($invoice->id);
        }
    }
}





