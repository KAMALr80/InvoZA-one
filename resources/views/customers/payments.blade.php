{{-- D:\smartErp\resources\views\customers\payments.blade.php --}}
@extends('layouts.app')
@section('page-title', 'Payments History - ' . $customer->name)

@section('content')
    <style>
        /* ================= PROFESSIONAL DESIGN SYSTEM ================= */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
            --purple: #7c3aed;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: var(--text-main);
            line-height: 1.5;
        }

        /* ================= CONTAINER ================= */
        .payment-wrapper {
            min-height: 100vh;
            background: #f1f5f9;
            padding: 2rem 1rem;
        }

        .payment-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ================= HEADER CARD ================= */
        .header-card {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            padding: 1.5rem 2rem;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            color: white;
            box-shadow: var(--shadow-md);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .customer-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .customer-contact {
            color: #cbd5e1;
            margin-top: 0.5rem;
            font-size: 0.95rem;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .btn-outline-light {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-outline-light:hover {
            background: white;
            color: #0f172a;
        }

        /* ================= STATS GRID ================= */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: var(--bg-white);
        }

        .stat-card {
            background: var(--bg-light);
            padding: 1.5rem 1.25rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            display: inline-block;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-main);
        }

        .stat-value.success {
            color: var(--success);
        }

        .stat-value.danger {
            color: var(--danger);
        }

        .stat-value.warning {
            color: var(--warning);
        }

        .stat-value.purple {
            color: var(--purple);
        }

        .stat-value.primary {
            color: var(--primary);
        }

        /* ================= OPEN BALANCE CARD ================= */
        .open-balance-card {
            background: var(--bg-white);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .open-balance-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .open-balance-badge.due {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .open-balance-badge.advance {
            background: #dcfce7;
            color: #059669;
            border: 1px solid #86efac;
        }

        .open-balance-badge.zero {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid var(--border);
        }

        /* ================= TABLE CONTAINER ================= */
        .table-container {
            background: var(--bg-white);
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-main);
        }

        /* ================= BADGES ================= */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            text-align: center;
        }

        .badge-paid {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-partial {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .badge-unpaid {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .badge-emi {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .badge-credit {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-debit {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        /* ================= TABLES ================= */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            font-size: 0.9rem;
            background: var(--bg-white);
            box-shadow: var(--shadow-sm);
        }

        .data-table th {
            background: #f1f5f9;
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid var(--border);
        }

        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background: var(--bg-light);
        }

        .data-table .total-row {
            background: #f1f5f9;
            font-weight: 600;
            border-top: 2px solid var(--border);
        }

        .data-table .total-row td {
            border-bottom: none;
        }

        /* ================= ACTION BUTTONS ================= */
        .btn-group {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid var(--border);
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-sm:hover {
            background: #f1f5f9;
        }

        .btn-danger:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        .btn-primary:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-warning:hover {
            background: var(--warning);
            color: white;
            border-color: var(--warning);
        }

        /* ================= TEXT UTILITIES ================= */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-success {
            color: var(--success);
        }

        .text-danger {
            color: var(--danger);
        }

        .text-primary {
            color: var(--primary);
        }

        .text-purple {
            color: var(--purple);
        }

        .text-warning {
            color: var(--warning);
        }

        /* ================= EMPTY STATE ================= */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            border: 1px dashed var(--border);
            margin: 2rem 0;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        /* ================= LOADING OVERLAY ================= */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ================= TOAST ================= */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 0.75rem 1.5rem;
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            border-left: 4px solid;
            display: none;
            z-index: 10001;
            min-width: 300px;
        }

        .toast.success {
            border-left-color: var(--success);
        }

        .toast.error {
            border-left-color: var(--danger);
        }

        .toast.warning {
            border-left-color: var(--warning);
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header-card {
                padding: 1.5rem;
            }

            .customer-name {
                font-size: 1.5rem;
            }

            .stats-grid {
                padding: 1.5rem;
            }

            .table-container {
                padding: 1.5rem;
                overflow-x: auto;
            }

            .data-table {
                min-width: 1000px;
            }
        }

        @media print {

            .btn-group,
            .loading-overlay,
            .toast {
                display: none !important;
            }

            .header-card {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    @php
        // Helper function to format currency with ₹ symbol
        function formatCurrency($amount)
        {
            return '₹' . number_format($amount, 2);
        }

        // ================= GET ALL DATA =================
        $walletTransactions = \App\Models\CustomerWallet::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $allPayments = \App\Models\Payment::where('customer_id', $customer->id)
            ->with('sale')
            ->orderBy('created_at', 'desc')
            ->get();

        // Wallet Summary
        $totalAdded = $walletTransactions->where('type', 'credit')->sum('amount');
        $totalUsed = $walletTransactions->where('type', 'debit')->sum('amount');
        $currentBalance = $walletTransactions->first()?->balance ?? 0;

        // Payment Calculations
        $totalReceived = $allPayments->sum('amount');
        $invoicePaymentsTotal = $allPayments->whereIn('remarks', ['INVOICE', 'EMI_DOWN'])->sum('amount');
        $walletUsedTotal = $allPayments->where('remarks', 'ADVANCE_USED')->sum('amount');
        $walletAdditionsTotal = $allPayments
            ->whereIn('remarks', ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD'])
            ->sum('amount');
        $appliedToInvoices = $invoicePaymentsTotal + $walletUsedTotal;
        $totalInvoiceGrandTotal = $invoices->sum('grand_total');
        $totalDueAmount = $totalInvoiceGrandTotal - $appliedToInvoices;
        $netPosition = $currentBalance - $totalDueAmount;

        // Open Balance Status
        $openBalance = $customer->open_balance ?? 0;
        $openBalanceStatus = $openBalance > 0 ? 'due' : ($openBalance < 0 ? 'advance' : 'zero');
        $openBalanceDisplay =
            $openBalance > 0
                ? 'Due: ₹' . number_format($openBalance, 2)
                : ($openBalance < 0
                    ? 'Advance: ₹' . number_format(abs($openBalance), 2)
                    : 'Clear (₹0)');

        // Invoice Summaries
        $invoiceSummaries = [];
        $totalGrandAmount = 0;
        $totalCashPayments = 0;
        $totalWalletUsed = 0;
        $totalWalletAdded = 0;
        $totalReceivedAmount = 0;
        $totalAppliedToInvoice = 0;
        $totalDueAmt = 0;

        foreach ($invoices as $inv) {
            $invoicePayments = $allPayments->where('sale_id', $inv->id);

            $cashPayments = $invoicePayments->whereIn('remarks', ['INVOICE', 'EMI_DOWN'])->sum('amount');
            $walletUsed = $invoicePayments->where('remarks', 'ADVANCE_USED')->sum('amount');
            $walletAdded = $invoicePayments
                ->whereIn('remarks', ['WALLET_ADD', 'EXCESS_TO_ADVANCE', 'ADVANCE_ONLY'])
                ->sum('amount');

            $totalReceivedForInvoice = $cashPayments + $walletUsed + $walletAdded;
            $appliedToThisInvoice = $cashPayments + $walletUsed;
            $dueAmount = max(0, $inv->grand_total - $appliedToThisInvoice);

            $status = $dueAmount <= 0 ? 'paid' : ($appliedToThisInvoice > 0 ? 'partial' : 'unpaid');
            if ($inv->payment_status === 'emi') {
                $status = 'emi';
            }

            $totalGrandAmount += $inv->grand_total;
            $totalCashPayments += $cashPayments;
            $totalWalletUsed += $walletUsed;
            $totalWalletAdded += $walletAdded;
            $totalReceivedAmount += $totalReceivedForInvoice;
            $totalAppliedToInvoice += $appliedToThisInvoice;
            $totalDueAmt += $dueAmount;

            $invoiceSummaries[] = [
                'id' => $inv->id,
                'date' => $inv->sale_date->format('d-m-Y'),
                'invoice_no' => $inv->invoice_no,
                'grand_total' => $inv->grand_total,
                'cash_paid' => $cashPayments,
                'wallet_used' => $walletUsed,
                'wallet_added' => $walletAdded,
                'total_received' => $totalReceivedForInvoice,
                'applied' => $appliedToThisInvoice,
                'due' => $dueAmount,
                'status' => $status,
                'payment_count' => $invoicePayments->count(),
                'has_emi' => $inv->payment_status === 'emi',
            ];
        }

        usort($invoiceSummaries, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
    @endphp

    <div class="payment-wrapper">
        <div class="payment-container">

            {{-- Loading Overlay --}}
            <div id="loadingOverlay" class="loading-overlay">
                <div class="spinner"></div>
            </div>

            {{-- Header Card --}}
            <div class="header-card">
                <div class="header-content">
                    <div>
                        <h1 class="customer-name">{{ $customer->name }}</h1>
                        <div class="customer-contact">
                            <span>📱 {{ $customer->mobile ?? 'N/A' }}</span>
                            <span>✉️ {{ $customer->email ?? 'N/A' }}</span>
                            <span>🆔 #{{ $customer->id }}</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('customers.index') }}" class="btn-outline-light">← Back</a>
                        <button onclick="window.print()" class="btn-outline-light">🖨️ Print</button>
                    </div>
                </div>
            </div>

            {{-- Open Balance Card --}}
            <div class="open-balance-card">
                <span>📊 Open Balance:</span>
                <span class="open-balance-badge {{ $openBalanceStatus }}">
                    {{ $openBalanceDisplay }}
                </span>
            </div>

            {{-- Stats Cards --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-label">Wallet Balance</div>
                    <div class="stat-value success">{{ formatCurrency($currentBalance) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-label">Total Due</div>
                    <div class="stat-value danger">{{ formatCurrency($totalDueAmount) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⚖️</div>
                    <div class="stat-label">Net Position</div>
                    <div class="stat-value {{ $netPosition >= 0 ? 'success' : 'danger' }}">
                        @if ($netPosition > 0)
                            +{{ formatCurrency($netPosition) }}
                        @elseif($netPosition < 0)
                            -{{ formatCurrency(abs($netPosition)) }}
                        @else
                            {{ formatCurrency(0) }}
                        @endif
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">➕</div>
                    <div class="stat-label">Wallet Added</div>
                    <div class="stat-value purple">{{ formatCurrency($totalAdded) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">➖</div>
                    <div class="stat-label">Wallet Used</div>
                    <div class="stat-value warning">{{ formatCurrency($totalUsed) }}</div>
                </div>
            </div>

            {{-- Payment Breakdown --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-label">Total Received</div>
                    <div class="stat-value primary">{{ formatCurrency($totalReceived) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-label">Invoice Payments</div>
                    <div class="stat-value success">{{ formatCurrency($invoicePaymentsTotal) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔄</div>
                    <div class="stat-label">Wallet Used</div>
                    <div class="stat-value purple">{{ formatCurrency($walletUsedTotal) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">➕</div>
                    <div class="stat-label">Wallet Additions</div>
                    <div class="stat-value warning">{{ formatCurrency($walletAdditionsTotal) }}</div>
                </div>
            </div>

            {{-- Invoice Table --}}
            <div class="table-container">
                <h3 class="section-title">📋 Invoice-wise Summary</h3>

                @if (count($invoiceSummaries) > 0)
                    <table class="data-table" id="invoicesTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th>Status</th>
                                <th class="text-right">Grand Total</th>
                                <th class="text-right">Cash Paid</th>
                                <th class="text-right">Wallet Used</th>
                                <th class="text-right">Wallet Added</th>
                                <th class="text-right">Total Received</th>
                                <th class="text-right">Applied</th>
                                <th class="text-right">Due</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoiceSummaries as $inv)
                                <tr>
                                    <td>{{ $inv['date'] }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $inv['id']) }}"
                                            style="color: var(--primary); text-decoration: none;">
                                            #{{ $inv['invoice_no'] }}
                                        </a>
                                        @if ($inv['has_emi'])
                                            <span class="badge badge-emi">EMI</span>
                                        @endif
                                    </td>
                                    <td><span
                                            class="badge badge-{{ $inv['status'] }}">{{ strtoupper($inv['status']) }}</span>
                                    </td>
                                    <td class="text-right">{{ formatCurrency($inv['grand_total']) }}</td>
                                    <td class="text-right text-primary">{{ formatCurrency($inv['cash_paid']) }}</td>
                                    <td class="text-right text-purple">{{ formatCurrency($inv['wallet_used']) }}</td>
                                    <td class="text-right text-warning">{{ formatCurrency($inv['wallet_added']) }}</td>
                                    <td class="text-right text-success fw-bold">
                                        {{ formatCurrency($inv['total_received']) }}</td>
                                    <td class="text-right text-primary">{{ formatCurrency($inv['applied']) }}</td>
                                    <td class="text-right {{ $inv['due'] > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                        {{ formatCurrency($inv['due']) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('sales.show', $inv['id']) }}" class="btn-sm"
                                                title="View">👁️</a>
                                            @if ($inv['payment_count'] > 0)
                                                <button class="btn-sm btn-danger"
                                                    onclick="bulkDeletePayments({{ $inv['id'] }}, '{{ $inv['invoice_no'] }}', {{ $inv['total_received'] }})"
                                                    title="Delete Payments">🗑️</button>
                                            @endif
                                            <button class="btn-sm btn-warning"
                                                onclick="deleteInvoiceWithPayments({{ $inv['id'] }}, '{{ $inv['invoice_no'] }}', {{ $inv['total_received'] }})"
                                                title="Delete Invoice">❌</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" class="text-right">Total:</td>
                                <td class="text-right">{{ formatCurrency($totalGrandAmount) }}</td>
                                <td class="text-right text-primary">{{ formatCurrency($totalCashPayments) }}</td>
                                <td class="text-right text-purple">{{ formatCurrency($totalWalletUsed) }}</td>
                                <td class="text-right text-warning">{{ formatCurrency($totalWalletAdded) }}</td>
                                <td class="text-right text-success">{{ formatCurrency($totalReceivedAmount) }}</td>
                                <td class="text-right text-primary">{{ formatCurrency($totalAppliedToInvoice) }}</td>
                                <td class="text-right {{ $totalDueAmt > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ formatCurrency($totalDueAmt) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <div class="empty-title">No invoices found</div>
                        <div class="empty-text">Create a new invoice to get started</div>
                        <a href="{{ route('sales.create') }}?customer_id={{ $customer->id }}&customer_name={{ urlencode($customer->name) }}"
                            class="btn-sm" style="padding: 0.75rem 1.5rem;">
                            ➕ Create New Invoice
                        </a>
                    </div>
                @endif
            </div>

            {{-- Wallet Ledger --}}
            @if ($walletTransactions->count() > 0)
                <div class="table-container">
                    <h3 class="section-title">📒 Wallet Ledger</h3>
                    <table class="data-table" id="walletTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Balance</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($walletTransactions as $trans)
                                @php
                                    // Ensure balance is never negative in display
                                    $displayBalance = max(0, $trans->balance);
                                @endphp
                                <tr>
                                    <td>{{ $trans->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $trans->type == 'credit' ? 'credit' : 'debit' }}">
                                            {{ $trans->type == 'credit' ? 'CREDIT' : 'DEBIT' }}
                                        </span>
                                    </td>
                                    <td
                                        class="text-right {{ $trans->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                        {{ $trans->type == 'credit' ? '+' : '-' }} {{ formatCurrency($trans->amount) }}
                                    </td>
                                    <td class="text-right fw-bold">{{ formatCurrency($displayBalance) }}</td>
                                    <td>{{ $trans->reference }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="toast"></div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // Initialize DataTables
        $(document).ready(function() {
            if ($('#invoicesTable').length) {
                $('#invoicesTable').DataTable({
                    pageLength: 25,
                    order: [
                        [0, 'desc']
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            }

            if ($('#walletTable').length) {
                $('#walletTable').DataTable({
                    pageLength: 25,
                    order: [
                        [0, 'desc']
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            }
        });

        // Toast notification
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            toast.innerHTML = msg;
            toast.className = 'toast ' + type;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 3000);
        }

        // Loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Bulk delete payments
        function bulkDeletePayments(id, no, amount) {
            if (!confirm(`Delete all payments for #${no} (₹${amount})?`)) return;
            showLoading();
            fetch(`/payments/bulk/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(r => r.json())
                .then(d => {
                    hideLoading();
                    if (d.success) {
                        showToast('✅ Payments deleted!');
                        setTimeout(() => location.reload(), 1500);
                    } else showToast('❌ ' + d.message, 'error');
                })
                .catch(() => {
                    hideLoading();
                    showToast('❌ Error', 'error');
                });
        }

        // Delete invoice with payments
        function deleteInvoiceWithPayments(id, no, amount) {
            if (!confirm(`Delete Invoice #${no} and all payments?`)) return;
            showLoading();
            fetch(`/invoices/${id}/delete-with-payments`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(r => r.json())
                .then(d => {
                    hideLoading();
                    if (d.success) {
                        showToast('✅ Invoice deleted!');
                        setTimeout(() => location.reload(), 1500);
                    } else showToast('❌ ' + d.message, 'error');
                })
                .catch(() => {
                    hideLoading();
                    showToast('❌ Error', 'error');
                });
        }

        // Print shortcut
        document.addEventListener('keydown', e => {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
@endsection
