{{-- D:\smartErp\resources\views\customers\payments.blade.php --}}
@extends('layouts.app')

@section('content')
    <div
        style="max-width: 1400px; margin: 40px auto; background: #ffffff; padding: 30px; border-radius: 16px; box-shadow: 0 8px 20px rgba(0,0,0,0.08);">

        {{-- Header with Customer Info and Balance Summary --}}
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #1f2937; margin-bottom: 5px;">
                    👤 {{ $customer->name }}
                </h1>
                <div style="display: flex; gap: 20px; color: #6b7280;">
                    <span>📱 {{ $customer->mobile ?? 'N/A' }}</span>
                    <span>✉️ {{ $customer->email ?? 'N/A' }}</span>
                </div>
            </div>

            {{-- Balance Card --}}
            @php
                $balance = $customer->open_balance ?? 0;
            @endphp
            <div
                style="padding: 15px 25px; border-radius: 12px;
            @if ($balance > 0) background: #fee2e2; color: #b91c1c; border-left: 4px solid #dc2626;
            @elseif($balance < 0) background: #dcfce7; color: #166534; border-left: 4px solid #16a34a;
            @else background: #f3f4f6; color: #4b5563; border-left: 4px solid #6b7280; @endif">
                <div style="font-size: 14px; display: flex; align-items: center; gap: 5px;">
                    @if ($balance > 0)
                        ⚠️
                    @elseif($balance < 0)
                        💰
                    @else
                        ✓
                    @endif
                    Current Balance
                </div>
                <div style="font-size: 32px; font-weight: 800;">₹ {{ number_format(abs($balance), 2) }}</div>
                <div style="font-size: 13px; font-weight: 500;">
                    @if ($balance > 0)
                        (Customer owes you)
                    @elseif($balance < 0)
                        (You owe customer - Advance)
                    @else
                        (No balance)
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div style="display: flex; gap: 10px; margin-bottom: 25px;">
            <a href="{{ route('customers.sales', $customer->id) }}"
                style="flex: 1; padding: 14px; text-align: center; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 10px; font-weight: 600;">
                📋 Invoices ({{ $invoices->total() }})
            </a>
            <a href="{{ route('customers.payments', $customer->id) }}"
                style="flex: 1; padding: 14px; text-align: center; background: #2563eb; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                💳 Transactions ({{ $transactions->total() }})
            </a>
        </div>

        @php
            // Calculate total due directly in blade
            $totalDue = 0;
            $totalPaidOverall = 0;
            foreach ($invoices as $inv) {
                $paidAmt = $transactions
                    ->where('sale_id', $inv->id)
                    ->where('status', 'paid')
                    ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
                    ->sum('amount');
                $totalDue += $inv->grand_total - $paidAmt;
                $totalPaidOverall += $paidAmt;
            }

            // Calculate advance balance from customer
            $advanceBalance = $customer->open_balance < 0 ? abs($customer->open_balance) : 0;
            $dueBalance = $customer->open_balance > 0 ? $customer->open_balance : 0;
        @endphp

        {{-- Summary Cards --}}
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 30px;">
            <div style="background: #eff6ff; padding: 15px; border-radius: 12px; border-left: 4px solid #2563eb;">
                <div style="color: #1e40af; font-size: 13px;">Total Invoices</div>
                <div style="font-size: 24px; font-weight: 700;">{{ $invoiceStats['total'] }}</div>
            </div>
            <div style="background: #dcfce7; padding: 15px; border-radius: 12px; border-left: 4px solid #16a34a;">
                <div style="color: #166534; font-size: 13px;">Fully Paid</div>
                <div style="font-size: 24px; font-weight: 700;">{{ $invoiceStats['paid'] }}</div>
            </div>
            <div style="background: #fee2e2; padding: 15px; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="color: #991b1b; font-size: 13px;">Due/Partial</div>
                <div style="font-size: 24px; font-weight: 700;">{{ $invoiceStats['partial'] }}</div>
            </div>
            <div style="background: #fef3c7; padding: 15px; border-radius: 12px; border-left: 4px solid #d97706;">
                <div style="color: #92400e; font-size: 13px;">Total Due</div>
                <div style="font-size: 24px; font-weight: 700;">₹ {{ number_format($totalDue, 2) }}</div>
            </div>
            <div style="background: #f3e8ff; padding: 15px; border-radius: 12px; border-left: 4px solid #9333ea;">
                <div style="color: #6b21a8; font-size: 13px;">Total Paid</div>
                <div style="font-size: 24px; font-weight: 700;">₹
                    {{ number_format($totalReceived ?? $totalPaidOverall, 2) }}</div>
            </div>
        </div>

        {{-- MAIN SECTION: INVOICE WISE BREAKDOWN --}}
        <div style="margin-bottom: 40px;">
            <h2
                style="font-size: 22px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                📊 Invoice-wise Payment Breakdown
                <span style="font-size: 14px; font-weight: normal; color: #6b7280;">
                    (Click invoice to see detailed transactions)
                </span>
            </h2>

            @forelse ($invoices as $invoice)
                @php
                    // Get ALL payments for this invoice
                    $invoicePayments = $transactions->where('sale_id', $invoice->id);

                    // 1. Direct invoice payments (cash/upi/card)
                    $directPayments = $invoicePayments
                        ->where('status', 'paid')
                        ->whereIn('remarks', ['INVOICE', 'EMI_DOWN'])
                        ->sum('amount');

                    // 2. Advance used for this invoice
                    $advanceUsed = $invoicePayments
                        ->where('status', 'paid')
                        ->where('remarks', 'ADVANCE_USED')
                        ->sum('amount');

                    // 3. Excess that went to advance
                    $excessToAdvance = $invoicePayments
                        ->where('status', 'paid')
                        ->where('remarks', 'EXCESS_TO_ADVANCE')
                        ->sum('amount');

                    // 4. Due entries
                    $dueEntries = $invoicePayments->where('remarks', 'INVOICE_DUE')->sum('amount');

                    // 5. Advance generated from this invoice (jo dusre invoices mein use hoga)
                    $advanceGenerated = $invoicePayments
                        ->where('status', 'paid')
                        ->whereIn('remarks', ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY'])
                        ->sum('amount');

                    // TOTAL PAID for this invoice = Direct + Advance Used
                    $totalPaidForInvoice = $directPayments + $advanceUsed;

                    // TOTAL MONEY RECEIVED from customer for this invoice = Direct + Advance Used + Excess
                    $totalMoneyReceived = $directPayments + $advanceUsed + $excessToAdvance;

                    // Remaining due
                    $remainingDue = $invoice->grand_total - $totalPaidForInvoice;

                    // Status calculation
                    $isFullyPaid = $remainingDue <= 0.01;
                    $isPartial = $totalPaidForInvoice > 0 && $remainingDue > 0.01;
                    $isUnpaid = $totalPaidForInvoice <= 0;

                    // Color coding
                    $headerBg = $isFullyPaid ? '#dcfce7' : ($isPartial ? '#fef3c7' : '#fee2e2');
                    $headerColor = $isFullyPaid ? '#166534' : ($isPartial ? '#92400e' : '#991b1b');
                    $borderColor = $isFullyPaid ? '#16a34a' : ($isPartial ? '#d97706' : '#dc2626');

                    // Calculate totals for summary
                    $totalInvoiceAmount = $invoicePayments
                        ->where('status', 'paid')
                        ->whereIn('remarks', ['INVOICE', 'EMI_DOWN'])
                        ->sum('amount');

                    $totalExcessAmount = $invoicePayments
                        ->where('status', 'paid')
                        ->where('remarks', 'EXCESS_TO_ADVANCE')
                        ->sum('amount');

                    $totalAdvanceUsed = $invoicePayments
                        ->where('status', 'paid')
                        ->where('remarks', 'ADVANCE_USED')
                        ->sum('amount');

                    $grandTotalPaid = $totalInvoiceAmount + $totalExcessAmount + $totalAdvanceUsed;
                @endphp

                {{-- Individual Invoice Card --}}
                <div class="invoice-card"
                    style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; margin-bottom: 25px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-left: 4px solid {{ $borderColor }};">

                    {{-- Invoice Header with GRAND TOTAL and TOTAL PAID --}}
                    <div style="background: {{ $headerBg }}; padding: 18px 24px;">
                        {{-- First row: Invoice number and date --}}
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <a href="{{ route('sales.show', $invoice->id) }}"
                                    style="font-size: 20px; font-weight: 700; color: {{ $headerColor }}; text-decoration: none;">
                                    #{{ $invoice->invoice_no }}
                                </a>
                                <span style="margin-left: 15px; color: #4b5563; font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($invoice->sale_date)->format('d M Y') }}
                                </span>
                            </div>
                            <span
                                style="padding: 6px 16px; border-radius: 30px; background: white; color: {{ $headerColor }}; font-weight: 700; font-size: 14px; border: 1px solid {{ $borderColor }};">
                                @if ($isFullyPaid)
                                    ✅ FULLY PAID
                                @elseif($isPartial)
                                    ⚠️ PARTIAL (₹{{ number_format($remainingDue, 2) }} Due)
                                @else
                                    ❌ UNPAID (₹{{ number_format($invoice->grand_total, 2) }} Due)
                                @endif
                            </span>
                        </div>

                        {{-- Second row: Grand Total and Total Paid side by side --}}
                        <div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">
                            {{-- Grand Total --}}
                            <div>
                                <div style="font-size: 13px; color: #4b5563;">📄 Grand Total</div>
                                <div style="font-size: 28px; font-weight: 800; color: #1f2937;">₹
                                    {{ number_format($invoice->grand_total, 2) }}</div>
                            </div>

                            <div style="width: 2px; height: 40px; background: #d1d5db;"></div>

                            {{-- TOTAL PAID --}}
                            <div>
                                <div style="font-size: 13px; color: #4b5563;">💰 Total Paid</div>
                                <div style="font-size: 28px; font-weight: 800; color: #059669;">₹
                                    {{ number_format($totalPaidForInvoice, 2) }}</div>
                                @if ($advanceUsed > 0)
                                    <div style="font-size: 12px; color: #6b7280;">(Includes
                                        ₹{{ number_format($advanceUsed, 2) }} advance)</div>
                                @endif
                            </div>

                            <div style="width: 2px; height: 40px; background: #d1d5db;"></div>

                            {{-- Due Amount --}}
                            <div>
                                <div style="font-size: 13px; color: #4b5563;">⚠️ Due Amount</div>
                                <div style="font-size: 28px; font-weight: 800; color: #dc2626;">₹
                                    {{ number_format($remainingDue, 2) }}</div>
                            </div>
                        </div>

                        {{-- Advance Generated Info --}}
                        @if ($advanceGenerated > 0)
                            <div
                                style="margin-top: 15px; padding: 10px 15px; background: #dbeafe; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
                                <span style="font-size: 20px;">💰</span>
                                <span style="color: #1e40af; font-weight: 600;">
                                    This invoice generated ₹{{ number_format($advanceGenerated, 2) }} advance - Used in
                                    other invoices
                                </span>
                            </div>
                        @endif

                        @if ($advanceUsed > 0)
                            <div
                                style="margin-top: 10px; padding: 10px 15px; background: #ede9fe; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; margin-left: 10px;">
                                <span style="font-size: 20px;">🔄</span>
                                <span style="color: #6d28d9; font-weight: 600;">
                                    Used ₹{{ number_format($advanceUsed, 2) }} advance from other invoices
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Payment Breakdown Cards --}}
                    <div style="padding: 20px 24px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">

                            {{-- Direct Payments --}}
                            <div
                                style="background: white; padding: 15px; border-radius: 12px; border-left: 4px solid #059669;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 20px;">💰</span>
                                    <span style="font-weight: 600; color: #374151;">Direct Payments</span>
                                </div>
                                <div style="font-size: 24px; font-weight: 700; color: #059669;">₹
                                    {{ number_format($directPayments, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                    Cash/UPI/Card/EMI
                                </div>
                            </div>

                            {{-- Advance Used --}}
                            <div
                                style="background: white; padding: 15px; border-radius: 12px; border-left: 4px solid #8b5cf6;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 20px;">🔄</span>
                                    <span style="font-weight: 600; color: #374151;">Advance Used</span>
                                </div>
                                <div style="font-size: 24px; font-weight: 700; color: #8b5cf6;">₹
                                    {{ number_format($advanceUsed, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                    From other invoices
                                </div>
                            </div>

                            {{-- Excess to Advance --}}
                            <div
                                style="background: white; padding: 15px; border-radius: 12px; border-left: 4px solid #3b82f6;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 20px;">➕</span>
                                    <span style="font-weight: 600; color: #374151;">Excess to Advance</span>
                                </div>
                                <div style="font-size: 24px; font-weight: 700; color: #3b82f6;">₹
                                    {{ number_format($excessToAdvance, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                    Will be used in other invoices
                                </div>
                            </div>

                            {{-- Due / Pending --}}
                            <div
                                style="background: white; padding: 15px; border-radius: 12px; border-left: 4px solid #dc2626;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 20px;">⚠️</span>
                                    <span style="font-weight: 600; color: #374151;">Due Amount</span>
                                </div>
                                <div style="font-size: 24px; font-weight: 700; color: #dc2626;">₹
                                    {{ number_format($remainingDue, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                    {{ $dueEntries > 0 ? 'Marked as due' : 'Pending payment' }}
                                </div>
                            </div>
                        </div>

                        {{-- Summary Bar with Transaction Count --}}
                        <div
                            style="margin-top: 20px; padding: 15px; background: white; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                                <span style="font-weight: 600;">Status:</span>
                                <span
                                    style="padding: 4px 12px; border-radius: 20px; background: {{ $headerBg }}; color: {{ $headerColor }}; font-weight: 700;">
                                    @if ($isFullyPaid)
                                        ✅ PAID
                                    @elseif($isPartial)
                                        ⚠️ PARTIAL (Due: ₹{{ number_format($remainingDue, 2) }})
                                    @else
                                        ❌ UNPAID (Due: ₹{{ number_format($invoice->grand_total, 2) }})
                                    @endif
                                </span>

                                @if ($dueEntries > 0)
                                    <span
                                        style="padding: 4px 12px; border-radius: 20px; background: #fee2e2; color: #991b1b; font-weight: 600;">
                                        📝 Due Entry: ₹{{ number_format($dueEntries, 2) }}
                                    </span>
                                @endif

                                @if ($advanceGenerated > 0)
                                    <span
                                        style="padding: 4px 12px; border-radius: 20px; background: #dbeafe; color: #1e40af; font-weight: 600;">
                                        💰 Generated: ₹{{ number_format($advanceGenerated, 2) }}
                                    </span>
                                @endif
                            </div>

                            <div style="color: #4b5563;">
                                <strong>Transactions:</strong> {{ $invoicePayments->count() }}
                            </div>
                        </div>
                    </div>

                    {{-- Transaction Details for this Invoice --}}
                    @if ($invoicePayments->count() > 0)
                        <div style="padding: 0 24px 20px 24px;">
                            <details open>
                                <summary
                                    style="padding: 15px 0; cursor: pointer; color: #2563eb; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                    <span>▼</span> View {{ $invoicePayments->count() }} transaction(s) for this invoice
                                </summary>

                                <table
                                    style="width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                                    <thead style="background: #f3f4f6;">
                                        <tr>
                                            <th style="padding: 12px; text-align: left;">Date</th>
                                            <th style="padding: 12px; text-align: left;">Type</th>
                                            <th style="padding: 12px; text-align: right;">Amount</th>
                                            <th style="padding: 12px; text-align: center;">Method</th>
                                            <th style="padding: 12px; text-align: center;">Effect</th>
                                            <th style="padding: 12px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- SUMMARY ROW - Total Payment with Smart Delete Option --}}
                                        @if ($grandTotalPaid > 0)
                                            <tr
                                                style="background: #f0f9ff; border-bottom: 2px solid #2563eb; font-weight: 700;">
                                                <td style="padding: 12px; font-weight: 600;">{{ now()->format('d M Y') }}
                                                </td>
                                                <td style="padding: 12px;">
                                                    <span
                                                        style="background: #2563eb; color: white; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                                        💰 TOTAL
                                                    </span>
                                                </td>
                                                <td
                                                    style="padding: 12px; text-align: right; font-size: 18px; color: #2563eb; font-weight: 800;">
                                                    ₹ {{ number_format($grandTotalPaid, 2) }}
                                                </td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <span
                                                        style="background: #e0e7ff; padding: 4px 8px; border-radius: 12px; color: #2563eb;">
                                                        MULTIPLE
                                                    </span>
                                                </td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <span style="color: #2563eb; font-weight: 600;">Combined total</span>
                                                </td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <form method="POST"
                                                        action="{{ route('payments.delete-bulk', $invoice->id) }}"
                                                        onsubmit="return confirmSmartDelete('{{ $invoice->invoice_no }}', {{ $grandTotalPaid }}, {{ $invoicePayments->count() }}, {{ $advanceGenerated }}, {{ $advanceUsed }})"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            style="background: #dc2626; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#b91c1c'"
                                                            onmouseout="this.style.background='#dc2626'"
                                                            title="Smart Delete - Removes advance from other invoices">
                                                            🗑️ Delete All
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Individual Transaction Rows --}}
                                        @foreach ($invoicePayments as $trans)
                                            @php
                                                $transColor = match ($trans->remarks) {
                                                    'INVOICE', 'EMI_DOWN' => '#059669',
                                                    'ADVANCE_USED' => '#8b5cf6',
                                                    'EXCESS_TO_ADVANCE' => '#3b82f6',
                                                    'ADVANCE_ONLY' => '#f59e0b',
                                                    'INVOICE_DUE' => '#dc2626',
                                                    default => '#6b7280',
                                                };

                                                $effectText = match ($trans->remarks) {
                                                    'INVOICE', 'EMI_DOWN' => 'Reduces invoice due',
                                                    'ADVANCE_USED' => 'Uses advance from other invoice',
                                                    'EXCESS_TO_ADVANCE' => 'Creates advance for other invoices',
                                                    'ADVANCE_ONLY' => 'Adds to advance balance',
                                                    'INVOICE_DUE' => 'Adds to customer due',
                                                    default => $trans->remarks,
                                                };

                                                $bgColor = $loop->index % 2 == 0 ? '#ffffff' : '#fafafa';
                                            @endphp
                                            <tr
                                                style="background: {{ $bgColor }}; border-bottom: 1px solid #e5e7eb;">
                                                <td style="padding: 12px;">{{ $trans->created_at->format('d M Y') }}</td>
                                                <td style="padding: 12px;">
                                                    <span style="color: {{ $transColor }}; font-weight: 600;">
                                                        {{ str_replace('_', ' ', $trans->remarks) }}
                                                    </span>
                                                </td>
                                                <td
                                                    style="padding: 12px; text-align: right; font-weight: 700; color: {{ $transColor }};">
                                                    ₹ {{ number_format($trans->amount, 2) }}
                                                </td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <span
                                                        style="background: #f3f4f6; padding: 4px 8px; border-radius: 12px;">
                                                        {{ strtoupper($trans->method) }}
                                                    </span>
                                                </td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <span
                                                        style="font-size: 13px; color: #4b5563;">{{ $effectText }}</span>
                                                </td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <form method="POST"
                                                        action="{{ route('payments.destroy', $trans->id) }}"
                                                        onsubmit="return confirmDelete('{{ $trans->remarks }}', {{ $trans->amount }}, '{{ $invoice->invoice_no }}', {{ $advanceGenerated }}, {{ $advanceUsed }})"
                                                        style="display: inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#fecaca'"
                                                            onmouseout="this.style.background='#fee2e2'">
                                                            🗑️ Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </details>
                        </div>
                    @endif
                </div>

            @empty
                <div style="text-align: center; padding: 60px; background: #f9fafb; border-radius: 16px;">
                    <div style="font-size: 48px;">📭</div>
                    <div style="font-size: 18px; color: #6b7280;">No invoices found for this customer</div>
                </div>
            @endforelse

            {{-- Pagination for invoices --}}
            @if (method_exists($invoices, 'links'))
                <div style="margin-top: 20px;">{{ $invoices->links() }}</div>
            @endif
        </div>

        {{-- Back Button --}}
        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ route('customers.index') }}"
                style="background: #f3f4f6; color: #4b5563; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                ← Back to Customers
            </a>
        </div>

    </div>

    <script>
        function confirmSmartDelete(invoiceNo, totalAmount, transactionCount, advanceGenerated, advanceUsed) {
            let message = `🔴 DELETE ALL TRANSACTIONS\n\n`;
            message += `Invoice: #${invoiceNo}\n`;
            message += `Total Amount: ₹${totalAmount.toFixed(2)}\n`;
            message += `Total Transactions: ${transactionCount}\n\n`;

            message += `📊 CURRENT SITUATION:\n`;
            if (advanceGenerated > 0) {
                message += `• This invoice GENERATED ₹${advanceGenerated.toFixed(2)} advance\n`;
                message += `  → This advance was used in OTHER invoices\n`;
            }
            if (advanceUsed > 0) {
                message += `• This invoice USED ₹${advanceUsed.toFixed(2)} advance\n`;
                message += `  → This advance came from OTHER invoices\n`;
            }
            message += `\n`;

            message += `🔥 WHAT WILL HAPPEN:\n`;
            message += `✅ This invoice → ALL transactions deleted (UNPAID)\n\n`;

            if (advanceGenerated > 0) {
                message += `💰 ADVANCE IMPACT:\n`;
                message += `• Advance balance will DECREASE by ₹${advanceGenerated.toFixed(2)}\n`;
                message += `• OTHER invoices that used this advance will become PARTIAL/UNPAID\n`;
                message += `• Their cash payments will REMAIN\n\n`;
            }

            if (advanceUsed > 0) {
                message += `🔄 ADVANCE IMPACT:\n`;
                message += `• Advance balance will INCREASE by ₹${advanceUsed.toFixed(2)}\n`;
                message += `• The invoices that GENERATED this advance get it back\n\n`;
            }

            message += `Are you sure?`;

            return confirm(message);
        }

        function confirmDelete(remarks, amount, invoiceNo, advanceGenerated, advanceUsed) {
            let message = `🔴 DELETE TRANSACTION\n\n`;
            message += `Type: ${remarks.replace(/_/g, ' ')}\n`;
            message += `Amount: ₹${amount.toFixed(2)}\n`;
            message += `Invoice: #${invoiceNo}\n\n`;

            message += `📌 EFFECT:\n`;

            switch (remarks) {
                case 'INVOICE':
                case 'EMI_DOWN':
                    message += `• Invoice due will INCREASE by ₹${amount.toFixed(2)}\n`;
                    message += `• No effect on advance balance\n`;
                    break;

                case 'EXCESS_TO_ADVANCE':
                    message += `• Advance balance will DECREASE by ₹${amount.toFixed(2)}\n`;
                    message += `• Other invoices using this advance will be affected\n`;
                    break;

                case 'ADVANCE_USED':
                    message += `• Advance balance will INCREASE by ₹${amount.toFixed(2)}\n`;
                    message += `• Invoice due will INCREASE by ₹${amount.toFixed(2)}\n`;
                    break;

                case 'ADVANCE_ONLY':
                    message += `• Advance balance will DECREASE by ₹${amount.toFixed(2)}\n`;
                    break;

                case 'INVOICE_DUE':
                    message += `• Customer due will DECREASE by ₹${amount.toFixed(2)}\n`;
                    break;
            }

            message += `\nAre you sure?`;

            return confirm(message);
        }
    </script>

    <style>
        /* Hover effects */
        tbody tr:hover {
            background: #f8fafc !important;
        }

        summary:hover {
            color: #1d4ed8 !important;
        }

        button[type="submit"]:hover {
            transform: scale(1.05);
        }

        details[open] summary span:first-child {
            transform: rotate(90deg);
        }

        .invoice-card {
            transition: all 0.3s ease;
        }

        .invoice-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection
