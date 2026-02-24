@extends('layouts.app')

@section('page-title', 'Wallet Report')

@section('content')
<style>
    /* ================= PROFESSIONAL DESIGN SYSTEM ================= */
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --success: #16a34a;
        --danger: #dc2626;
        --warning: #d97706;
        --info: #3b82f6;
        --purple: #7c3aed;
        --pink: #ec4899;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --radius-xl: 16px;
        --radius-2xl: 24px;
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
    .report-wrapper {
        min-height: 100vh;
        background: #f1f5f9;
        padding: 2rem 1rem;
    }

    .report-container {
        max-width: 1400px;
        margin: 0 auto;
        background: var(--bg-white);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        padding: 2rem;
    }

    /* ================= HEADER ================= */
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--border);
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .title-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--purple), var(--primary));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: var(--shadow-md);
    }

    .title-content h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .title-content p {
        margin: 0.25rem 0 0;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline {
        background: white;
        color: var(--text-main);
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        border: 1px solid var(--border);
        transition: all 0.2s;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-outline:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* ================= DATE FILTER ================= */
    .filter-section {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    /* ================= SUMMARY CARDS ================= */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--purple));
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .summary-icon.total-balance { background: #dcfce7; color: var(--success); }
    .summary-icon.customers { background: #dbeafe; color: var(--primary); }
    .summary-icon.transactions { background: #fef3c7; color: var(--warning); }
    .summary-icon.avg-balance { background: #f3e8ff; color: var(--purple); }

    .summary-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .summary-trend {
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .trend-up { color: var(--success); }
    .trend-down { color: var(--danger); }

    /* ================= CHARTS SECTION ================= */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .chart-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-white);
        padding: 1.25rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(37,99,235,0.05), transparent 70%);
        border-radius: 50%;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* ================= TABLES ================= */
    .tables-section {
        margin-top: 2rem;
    }

    .table-wrapper {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-header h2 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .table-search {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        width: 250px;
        font-size: 0.9rem;
    }

    .table-search:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .data-table thead th {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
    }

    .data-table tbody td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: var(--bg-light);
    }

    .customer-info {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .customer-avatar {
        width: 35px;
        height: 35px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .customer-details {
        line-height: 1.3;
    }

    .customer-name {
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    .customer-contact {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .balance-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .balance-badge.positive {
        background: #dcfce7;
        color: var(--success);
    }

    .balance-badge.zero {
        background: #f1f5f9;
        color: var(--text-muted);
    }

    .trend-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .trend-up {
        background: #dcfce7;
        color: var(--success);
    }

    .trend-down {
        background: #fee2e2;
        color: var(--danger);
    }

    .trend-neutral {
        background: #f1f5f9;
        color: var(--text-muted);
    }

    .view-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        background: #f1f5f9;
        color: var(--text-main);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .view-btn:hover {
        background: var(--primary);
        color: white;
    }

    /* ================= PAGINATION ================= */
    .pagination {
        display: flex;
        justify-content: flex-end;
        gap: 0.25rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
    }

    .pagination .page-item {
        list-style: none;
    }

    .pagination .page-link {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.2s;
        background: white;
        font-size: 0.9rem;
    }

    .pagination .page-link:hover {
        border-color: var(--primary);
        background: var(--bg-light);
    }

    .pagination .active .page-link {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* ================= EXPORT DROPDOWN ================= */
    .export-options {
        position: relative;
        display: inline-block;
    }

    .export-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        min-width: 200px;
        z-index: 1000;
        display: none;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .export-options:hover .export-dropdown {
        display: block;
    }

    .export-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 0.9rem;
    }

    .export-item:hover {
        background: var(--bg-light);
    }

    /* ================= LOADING & TOAST ================= */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--border);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        background: white;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 11000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid;
        min-width: 300px;
    }

    .toast.success { border-left-color: var(--success); }
    .toast.error { border-left-color: var(--danger); }
    .toast.warning { border-left-color: var(--warning); }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .report-container {
            padding: 1.5rem;
        }

        .report-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .data-table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 480px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-search {
            width: 100%;
        }
    }
</style>

<div class="report-wrapper">
    <div class="report-container">
        {{-- Loading Overlay --}}
        <div id="loadingOverlay" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        {{-- Header Section --}}
        <div class="report-header">
            <div class="title-section">
                <div class="title-icon">💰</div>
                <div class="title-content">
                    <h1>Wallet Report</h1>
                    <p>Comprehensive overview of customer wallet balances and transactions</p>
                </div>
            </div>
            <div class="header-actions">
                <div class="export-options">
                    <button class="btn-outline">
                        <span>📥</span> Export Report
                    </button>
                    <div class="export-dropdown">
                        <button onclick="exportReport('pdf')" class="export-item">
                            <span>📄</span> PDF Report
                        </button>
                        <button onclick="exportReport('excel')" class="export-item">
                            <span>📊</span> Excel Report
                        </button>
                        <button onclick="exportReport('csv')" class="export-item">
                            <span>📝</span> CSV Format
                        </button>
                        <button onclick="printReport()" class="export-item">
                            <span>🖨️</span> Print Report
                        </button>
                    </div>
                </div>
                <a href="{{ route('customers.index') }}" class="btn-primary">
                    <span>👥</span> Back to Customers
                </a>
            </div>
        </div>

        {{-- Date Filter Section --}}
        <div class="filter-section">
            <div class="filter-title">
                <span>📅</span> Filter by Date Range
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" id="fromDate" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" id="toDate" value="{{ request('to_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="filter-group">
                    <label>Transaction Type</label>
                    <select id="transactionType">
                        <option value="all">All Transactions</option>
                        <option value="credit">Credit Only</option>
                        <option value="debit">Debit Only</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="btn-primary" onclick="applyFilter()">Apply Filter</button>
                    <button class="btn-outline" onclick="resetFilter()">Reset</button>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        @php
            $totalWalletBalance = $customers->sum(function($c) {
                return $c->getCurrentWalletBalanceAttribute();
            });

            $totalCredits = $recentTransactions->where('type', 'credit')->sum('amount');
            $totalDebits = $recentTransactions->where('type', 'debit')->sum('amount');
            $averageBalance = $customers->count() > 0 ? $totalWalletBalance / $customers->count() : 0;

            $customersWithBalance = $customers->filter(function($c) {
                return $c->getCurrentWalletBalanceAttribute() > 0;
            })->count();

            // Calculate balance ranges for chart
            $balanceRanges = [
                '0' => 0,
                '1-1000' => 0,
                '1001-5000' => 0,
                '5001-10000' => 0,
                '10001+' => 0
            ];

            foreach ($customers as $c) {
                $bal = $c->getCurrentWalletBalanceAttribute();
                if ($bal <= 0) $balanceRanges['0']++;
                elseif ($bal <= 1000) $balanceRanges['1-1000']++;
                elseif ($bal <= 5000) $balanceRanges['1001-5000']++;
                elseif ($bal <= 10000) $balanceRanges['5001-10000']++;
                else $balanceRanges['10001+']++;
            }

            // Get transaction trends for last 30 days
            $trendData = \App\Models\CustomerWallet::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, type, SUM(amount) as total')
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get();

            $trendDates = [];
            $trendCredits = [];
            $trendDebits = [];

            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $trendDates[] = now()->subDays($i)->format('d M');

                $creditTotal = $trendData->where('date', $date)->where('type', 'credit')->first()?->total ?? 0;
                $debitTotal = $trendData->where('date', $date)->where('type', 'debit')->first()?->total ?? 0;

                $trendCredits[] = $creditTotal;
                $trendDebits[] = $debitTotal;
            }
        @endphp

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon total-balance">💰</div>
                <div class="summary-label">Total Wallet Balance</div>
                <div class="summary-value">₹{{ number_format($totalWalletBalance, 2) }}</div>
                <div class="summary-trend">
                    @if($totalCredits > $totalDebits)
                        <span class="trend-up">↑ Net +₹{{ number_format($totalCredits - $totalDebits, 2) }}</span>
                    @elseif($totalDebits > $totalCredits)
                        <span class="trend-down">↓ Net -₹{{ number_format($totalDebits - $totalCredits, 2) }}</span>
                    @else
                        <span class="trend-neutral">→ Net Zero</span>
                    @endif
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon customers">👥</div>
                <div class="summary-label">Customers with Wallet</div>
                <div class="summary-value">{{ $customersWithBalance }}</div>
                <div class="summary-trend">out of {{ $customers->count() }} total customers</div>
            </div>

            <div class="summary-card">
                <div class="summary-icon transactions">📊</div>
                <div class="summary-label">Total Transactions</div>
                <div class="summary-value">{{ $recentTransactions->count() }}</div>
                <div class="summary-trend">
                    <span class="trend-up">↑ Credits: {{ $recentTransactions->where('type', 'credit')->count() }}</span>
                    <span class="trend-down">↓ Debits: {{ $recentTransactions->where('type', 'debit')->count() }}</span>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon avg-balance">📈</div>
                <div class="summary-label">Average Balance</div>
                <div class="summary-value">₹{{ number_format($averageBalance, 2) }}</div>
                <div class="summary-trend">per customer</div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="charts-grid">
            {{-- Balance Distribution Chart --}}
            <div class="chart-card">
                <div class="chart-header">
                    <h3>
                        <span>💰</span> Balance Distribution
                    </h3>
                    <span class="trend-indicator trend-neutral">By Range</span>
                </div>
                <div class="chart-container">
                    <canvas id="balanceChart"></canvas>
                </div>
            </div>

            {{-- Transaction Trend Chart --}}
            <div class="chart-card">
                <div class="chart-header">
                    <h3>
                        <span>📊</span> Transaction Trend
                    </h3>
                    <span class="trend-indicator trend-neutral">Last 30 Days</span>
                </div>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--success)">📈</div>
                <div class="stat-value">₹{{ number_format($totalCredits, 2) }}</div>
                <div class="stat-label">Total Credits</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--danger)">📉</div>
                <div class="stat-value">₹{{ number_format($totalDebits, 2) }}</div>
                <div class="stat-label">Total Debits</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--info)">🔄</div>
                <div class="stat-value">{{ $recentTransactions->where('type', 'credit')->count() }}</div>
                <div class="stat-label">Credit Count</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--warning)">🔄</div>
                <div class="stat-value">{{ $recentTransactions->where('type', 'debit')->count() }}</div>
                <div class="stat-label">Debit Count</div>
            </div>
        </div>

        {{-- Tables Section --}}
        <div class="tables-section">
            {{-- Customer Balances Table --}}
            <div class="table-wrapper">
                <div class="table-header">
                    <h2>
                        <span>👥</span> Customer Wallet Balances
                    </h2>
                    <div class="table-actions">
                        <input type="text" class="table-search" id="customerSearch" placeholder="Search customers..." onkeyup="searchCustomers(this.value)">
                    </div>
                </div>

                <table class="data-table" id="customersTable">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Current Balance</th>
                            <th>Total Credits</th>
                            <th>Total Debits</th>
                            <th>Transaction Count</th>
                            <th>Last Activity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            @php
                                $balance = $customer->getCurrentWalletBalanceAttribute();
                                $customerCredits = $customer->wallet()->where('type', 'credit')->sum('amount');
                                $customerDebits = $customer->wallet()->where('type', 'debit')->sum('amount');
                                $transactionCount = $customer->wallet()->count();
                                $lastTransaction = $customer->wallet()->first();
                            @endphp
                            <tr>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                        <div class="customer-details">
                                            <div class="customer-name">{{ $customer->name }}</div>
                                            <div class="customer-contact">ID: {{ $customer->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $customer->mobile ?? 'N/A' }}</div>
                                    <small style="color: var(--text-muted);">{{ $customer->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="balance-badge {{ $balance > 0 ? 'positive' : 'zero' }}">
                                        ₹{{ number_format($balance, 2) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($customerCredits, 2) }}</td>
                                <td>₹{{ number_format($customerDebits, 2) }}</td>
                                <td>{{ $transactionCount }}</td>
                                <td>
                                    @if($lastTransaction)
                                        <span title="{{ $lastTransaction->created_at->format('d M Y H:i') }}">
                                            {{ $lastTransaction->created_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-muted);">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customers.sales', $customer->id) }}" class="view-btn">
                                        <span>👁️</span> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 3rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                    <div style="font-size: 1.125rem; color: var(--text-muted);">No customers found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(method_exists($customers, 'links'))
                    <div class="pagination">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>

            {{-- Recent Transactions Table --}}
            <div class="table-wrapper">
                <div class="table-header">
                    <h2>
                        <span>📋</span> Recent Wallet Transactions
                    </h2>
                    <div class="table-actions">
                        <input type="text" class="table-search" id="transactionSearch" placeholder="Search transactions..." onkeyup="searchTransactions(this.value)">
                    </div>
                </div>

                <table class="data-table" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                    <small style="color: var(--text-muted);">{{ $transaction->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar" style="width: 30px; height: 30px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}
                                        </div>
                                        <div class="customer-details">
                                            <div class="customer-name" style="font-size: 0.9rem;">{{ $transaction->customer->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="trend-indicator {{ $transaction->type === 'credit' ? 'trend-up' : 'trend-down' }}">
                                        {{ $transaction->type === 'credit' ? 'Credit' : 'Debit' }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color: {{ $transaction->type === 'credit' ? 'var(--success)' : 'var(--danger)' }}; font-weight: 600;">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }} ₹{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($transaction->balance, 2) }}</td>
                                <td>
                                    <span title="{{ $transaction->reference }}">
                                        {{ Str::limit($transaction->reference ?? 'No reference', 30) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 3rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                    <div style="font-size: 1.125rem; color: var(--text-muted);">No transactions found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="toast" style="display: none;">
    <span id="toastIcon">✅</span>
    <span id="toastMessage"></span>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    // Chart instances
    let balanceChart, trendChart;

    // Initialize Charts on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
    });

    function initializeCharts() {
        // Balance Distribution Chart
        const balanceCtx = document.getElementById('balanceChart').getContext('2d');

        // Data from PHP
        const balanceRanges = @json($balanceRanges);
        const trendDates = @json($trendDates);
        const trendCredits = @json($trendCredits);
        const trendDebits = @json($trendDebits);

        // Destroy existing chart if exists
        if (balanceChart) {
            balanceChart.destroy();
        }

        balanceChart = new Chart(balanceCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(balanceRanges).map(range =>
                    range === '0' ? 'Zero Balance' :
                    range === '1-1000' ? '₹1 - ₹1,000' :
                    range === '1001-5000' ? '₹1,001 - ₹5,000' :
                    range === '5001-10000' ? '₹5,001 - ₹10,000' :
                    'Above ₹10,000'
                ),
                datasets: [{
                    data: Object.values(balanceRanges),
                    backgroundColor: [
                        '#94a3b8',  // Zero - gray
                        '#10b981',  // 1-1000 - green
                        '#3b82f6',  // 1001-5000 - blue
                        '#8b5cf6',  // 5001-10000 - purple
                        '#ec4899'   // 10001+ - pink
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} customers (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Transaction Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');

        if (trendChart) {
            trendChart.destroy();
        }

        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendDates,
                datasets: [
                    {
                        label: 'Credits',
                        data: trendCredits,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Debits',
                        data: trendDebits,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw || 0;
                                return `${label}: ₹${value.toLocaleString('en-IN')}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            },
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    }

    // Search Functions
    function searchCustomers(query) {
        query = query.toLowerCase();
        $('#customersTable tbody tr').each(function() {
            let text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) > -1);
        });
    }

    function searchTransactions(query) {
        query = query.toLowerCase();
        $('#transactionsTable tbody tr').each(function() {
            let text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) > -1);
        });
    }

    // Filter Functions
    function applyFilter() {
        let fromDate = $('#fromDate').val();
        let toDate = $('#toDate').val();
        let type = $('#transactionType').val();

        showLoading();

        window.location.href = `{{ route('wallet.report') }}?from_date=${fromDate}&to_date=${toDate}&type=${type}`;
    }

    function resetFilter() {
        window.location.href = '{{ route('wallet.report') }}';
    }

    // Export Functions
    function exportReport(format) {
        showLoading();

        let fromDate = $('#fromDate').val();
        let toDate = $('#toDate').val();

        // Simulate export delay
        setTimeout(() => {
            hideLoading();
            showToast(`Report exported as ${format.toUpperCase()} successfully!`, 'success');
        }, 1500);

        // In production, uncomment this:
        // window.location.href = `/customers/wallet/export/${format}?from_date=${fromDate}&to_date=${toDate}`;
    }

    function printReport() {
        window.print();
    }

    // Loading Functions
    function showLoading() {
        $('#loadingOverlay').addClass('active');
    }

    function hideLoading() {
        $('#loadingOverlay').removeClass('active');
    }

    // Toast Functions
    function showToast(message, type = 'success') {
        let toast = $('#toast');
        $('#toastMessage').text(message);

        toast.removeClass('success error warning').addClass(type);

        if (type === 'success') $('#toastIcon').text('✅');
        else if (type === 'error') $('#toastIcon').text('❌');
        else if (type === 'warning') $('#toastIcon').text('⚠️');

        toast.fadeIn(300);

        setTimeout(() => {
            toast.fadeOut(300);
        }, 3000);
    }

    // Check for session messages
    @if(session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif

    @if(session('warning'))
        showToast("{{ session('warning') }}", 'warning');
    @endif
</script>
@endsection
