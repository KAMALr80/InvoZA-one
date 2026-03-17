@extends('layouts.app')

@section('page-title', 'Logistics Reports & Analytics')

@section('content')
<style>
    /* ================= PROFESSIONAL REPORTS DASHBOARD STYLES ================= */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #1e293b;
        line-height: 1.5;
    }

    /* ================= MAIN CONTAINER ================= */
    .reports-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        padding: clamp(16px, 3vw, 30px);
        width: 100%;
    }

    .container {
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
    }

    /* ================= MAIN CARD ================= */
    .reports-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        width: 100%;
        border: 1px solid #e5e7eb;
    }

    /* ================= HEADER ================= */
    .reports-header {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        padding: clamp(1.5rem, 4vw, 2rem);
        color: white;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-left {
        flex: 1;
        min-width: 280px;
    }

    .header-title {
        font-size: clamp(1.5rem, 5vw, 2rem);
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .header-subtitle {
        opacity: 0.9;
        font-size: clamp(0.9rem, 2.5vw, 1rem);
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .header-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: clamp(0.9rem, 2vw, 1rem);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .header-btn:hover {
        background: white;
        color: #0f172a;
    }

    /* ================= DATE FILTER ================= */
    .date-filter-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .filter-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        background: white;
        transition: all 0.2s;
    }

    .filter-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        white-space: nowrap;
    }

    .filter-btn-primary {
        background: #3b82f6;
        color: white;
    }

    .filter-btn-primary:hover {
        background: #2563eb;
    }

    .filter-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e5e7eb;
    }

    .filter-btn-secondary:hover {
        background: #e2e8f0;
    }

    /* ================= STATS CARDS ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.25rem;
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        border-bottom: 1px solid #e5e7eb;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .stat-title {
        font-size: 0.9rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon.blue {
        background: #dbeafe;
        color: #1e40af;
    }

    .stat-icon.green {
        background: #d1fae5;
        color: #065f46;
    }

    .stat-icon.yellow {
        background: #fef3c7;
        color: #92400e;
    }

    .stat-icon.purple {
        background: #ede9fe;
        color: #5b21b6;
    }

    .stat-icon.red {
        background: #fee2e2;
        color: #991b1b;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .stat-sub {
        font-size: 0.85rem;
        color: #64748b;
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .stat-trend.up {
        color: #10b981;
    }

    .stat-trend.down {
        color: #ef4444;
    }

    /* ================= CHARTS SECTION ================= */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        border-bottom: 1px solid #e5e7eb;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-legend {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }

    .legend-color.blue {
        background: #3b82f6;
    }

    .legend-color.green {
        background: #10b981;
    }

    .legend-color.yellow {
        background: #f59e0b;
    }

    .legend-color.purple {
        background: #8b5cf6;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    canvas {
        max-width: 100%;
        height: auto !important;
    }

    /* ================= TABLES SECTION ================= */
    .tables-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .export-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .export-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #e5e7eb;
        background: white;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .export-btn:hover {
        background: #f1f5f9;
        border-color: #3b82f6;
    }

    /* ================= COURIER TABLE ================= */
    .table-responsive {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: white;
        margin-bottom: 2rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .data-table th {
        background: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.95rem;
        color: #1e293b;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .progress-cell {
        width: 150px;
    }

    .progress-bar-sm {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-fill-sm {
        height: 100%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        width: 0%;
        transition: width 0.3s ease;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge.success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge.danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge.info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* ================= CITY GRID ================= */
    .city-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .city-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .city-name {
        font-weight: 600;
        color: #1e293b;
    }

    .city-count {
        background: white;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-weight: 600;
        color: #3b82f6;
        border: 1px solid #dbeafe;
    }

    /* ================= TOAST ================= */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-left: 4px solid;
        display: none;
        z-index: 9999;
        max-width: 400px;
        width: calc(100% - 40px);
        animation: slideIn 0.3s ease;
    }

    .toast.success {
        border-left-color: #10b981;
    }

    .toast.error {
        border-left-color: #ef4444;
    }

    .toast.warning {
        border-left-color: #f59e0b;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* ================= LOADING ================= */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        z-index: 11000;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e5e7eb;
        border-top-color: #3b82f6;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        color: #1e293b;
        font-weight: 500;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .chart-container {
            height: 250px;
        }

        .export-buttons {
            flex-direction: column;
            width: 100%;
        }

        .export-btn {
            width: 100%;
            justify-content: center;
        }

        .city-grid {
            grid-template-columns: 1fr;
        }
    }

    @media print {
        .header-actions,
        .filter-form,
        .export-buttons,
        .btn {
            display: none !important;
        }

        .stat-card {
            break-inside: avoid;
            border: 1px solid #000;
        }

        .chart-container {
            page-break-inside: avoid;
        }
    }
</style>

<div class="reports-page">
    <div class="container">
        <div class="reports-card">
            {{-- Loading Overlay --}}
            <div id="loadingOverlay" class="loading-overlay">
                <div class="spinner"></div>
                <div class="loading-text">Loading reports...</div>
            </div>

            {{-- Header --}}
            <div class="reports-header">
                <div class="header-content">
                    <div class="header-left">
                        <h1 class="header-title">Logistics Reports</h1>
                        <p class="header-subtitle">Analytics and performance metrics for shipments</p>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn" onclick="refreshReports()">
                            🔄 Refresh
                        </button>
                        <button class="header-btn" onclick="exportData('pdf')">
                            📄 Export PDF
                        </button>
                        <button class="header-btn" onclick="exportData('excel')">
                            📊 Export Excel
                        </button>
                    </div>
                </div>
            </div>

            {{-- Date Filter --}}
            <div class="date-filter-section">
                <form method="GET" action="{{ route('logistics.reports') }}" class="filter-form" id="filterForm">
                    <div class="filter-group">
                        <label class="filter-label">From Date</label>
                        <input type="date" name="from_date" class="filter-input" value="{{ $fromDate ?? now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">To Date</label>
                        <input type="date" name="to_date" class="filter-input" value="{{ $toDate ?? now()->format('Y-m-d') }}">
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn filter-btn-primary">
                            Apply Filter
                        </button>
                        <a href="{{ route('logistics.reports') }}" class="filter-btn filter-btn-secondary">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Stats Cards --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Total Shipments</span>
                        <span class="stat-icon blue">📦</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['total_shipments'] ?? 0) }}</div>
                    <div class="stat-sub">{{ $stats['total_shipments'] ?? 0 }} shipments in selected period</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Delivered</span>
                        <span class="stat-icon green">✅</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['delivered'] ?? 0) }}</div>
                    <div class="stat-sub">{{ $stats['delivered'] ?? 0 }} delivered successfully</div>
                    <div class="stat-trend up">
                        ↑ {{ $stats['delivery_rate'] ?? 0 }}% delivery rate
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">In Transit</span>
                        <span class="stat-icon yellow">🚚</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['in_transit'] ?? 0) }}</div>
                    <div class="stat-sub">{{ $stats['in_transit'] ?? 0 }} shipments on the way</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Pending</span>
                        <span class="stat-icon purple">⏳</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['pending'] ?? 0) }}</div>
                    <div class="stat-sub">{{ $stats['pending'] ?? 0 }} pending pickup</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Failed/Returned</span>
                        <span class="stat-icon red">❌</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['failed'] ?? 0) }}</div>
                    <div class="stat-sub">{{ $stats['failed'] ?? 0 }} failed deliveries</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Total Revenue</span>
                        <span class="stat-icon blue">💰</span>
                    </div>
                    <div class="stat-value">₹{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                    <div class="stat-sub">From shipping charges</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Avg. Delivery Time</span>
                        <span class="stat-icon green">⏱️</span>
                    </div>
                    <div class="stat-value">{{ $stats['avg_delivery_time'] ?? 0 }} hrs</div>
                    <div class="stat-sub">Average delivery time</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">COD Shipments</span>
                        <span class="stat-icon yellow">💵</span>
                    </div>
                    <div class="stat-value">{{ number_format($stats['cod_shipments'] ?? 0) }}</div>
                    <div class="stat-sub">Cash on Delivery orders</div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="charts-grid">
                {{-- Daily Trend Chart --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <span>📈</span>
                            <span>Daily Shipment Trend</span>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-color blue"></span>
                                <span>Shipments</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="dailyTrendChart"></canvas>
                    </div>
                </div>

                {{-- Courier Distribution --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <span>🥧</span>
                            <span>Shipments by Courier</span>
                        </div>
                        <div class="chart-legend" id="courierLegend"></div>
                    </div>
                    <div class="chart-container">
                        <canvas id="courierChart"></canvas>
                    </div>
                </div>

                {{-- Status Distribution --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <span>📊</span>
                            <span>Shipment Status</span>
                        </div>
                        <div class="chart-legend" id="statusLegend"></div>
                    </div>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                {{-- City-wise Distribution --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <span>🏙️</span>
                            <span>Top Cities</span>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-color purple"></span>
                                <span>Shipments</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="cityChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tables Section --}}
            <div class="tables-section">
                {{-- Courier Partner Performance --}}
                <div class="section-header">
                    <div class="section-title">
                        <span>🚚</span>
                        <span>Courier Partner Performance</span>
                    </div>
                    <div class="export-buttons">
                        <button class="export-btn" onclick="exportTable('courier')">
                            📥 Export Courier Data
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table" id="courierTable">
                        <thead>
                            <tr>
                                <th>Courier Partner</th>
                                <th>Total Shipments</th>
                                <th>Delivered</th>
                                <th>Pending</th>
                                <th>Failed</th>
                                <th>Success Rate</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byCourier as $courier)
                            <tr>
                                <td><strong>{{ $courier->courier_partner ?? 'Not Assigned' }}</strong></td>
                                <td>{{ number_format($courier->total) }}</td>
                                <td>{{ number_format($courier->delivered ?? 0) }}</td>
                                <td>{{ number_format($courier->pending ?? 0) }}</td>
                                <td>{{ number_format($courier->failed ?? 0) }}</td>
                                <td>
                                    @php
                                        $successRate = $courier->total > 0 ? round(($courier->delivered / $courier->total) * 100) : 0;
                                    @endphp
                                    <span class="badge {{ $successRate >= 80 ? 'success' : ($successRate >= 50 ? 'warning' : 'danger') }}">
                                        {{ $successRate }}%
                                    </span>
                                    <div class="progress-bar-sm">
                                        <div class="progress-fill-sm" style="width: {{ $successRate }}%"></div>
                                    </div>
                                </td>
                                <td>₹{{ number_format($courier->revenue ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                    No courier data available for selected period
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Top Cities --}}
                <div class="section-header" style="margin-top: 2rem;">
                    <div class="section-title">
                        <span>🏙️</span>
                        <span>Top Delivery Cities</span>
                    </div>
                    <div class="export-buttons">
                        <button class="export-btn" onclick="exportTable('cities')">
                            📥 Export Cities Data
                        </button>
                    </div>
                </div>

                <div class="city-grid">
                    @forelse($byCity as $city)
                    <div class="city-card">
                        <span class="city-name">{{ $city->city }}</span>
                        <span class="city-count">{{ number_format($city->total) }}</span>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 2rem; color: #64748b; grid-column: 1/-1;">
                        No city data available for selected period
                    </div>
                    @endforelse
                </div>

                {{-- Daily Trend Table --}}
                <div class="section-header" style="margin-top: 2rem;">
                    <div class="section-title">
                        <span>📅</span>
                        <span>Daily Shipment Trend</span>
                    </div>
                    <div class="export-buttons">
                        <button class="export-btn" onclick="exportTable('daily')">
                            📥 Export Daily Data
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table" id="dailyTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Shipments</th>
                                <th>Delivered</th>
                                <th>In Transit</th>
                                <th>Pending</th>
                                <th>Failed</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyTrend ?? [] as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                                <td>{{ number_format($day->total) }}</td>
                                <td>{{ number_format($day->delivered ?? 0) }}</td>
                                <td>{{ number_format($day->in_transit ?? 0) }}</td>
                                <td>{{ number_format($day->pending ?? 0) }}</td>
                                <td>{{ number_format($day->failed ?? 0) }}</td>
                                <td>₹{{ number_format($day->revenue ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                    No daily data available for selected period
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="toast"></div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // Chart data
    const dailyData = @json($dailyTrend ?? []);
    const courierData = @json($byCourier ?? []);
    const cityData = @json($byCity ?? []);

    // Stats data
    const stats = @json($stats ?? []);

    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function showToast(msg, type = 'success') {
        const toast = document.getElementById('toast');
        toast.innerHTML = msg;
        toast.className = 'toast ' + type;
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 3000);
    }

    function refreshReports() {
        showLoading();
        window.location.reload();
    }

    function exportData(format) {
        showLoading();

        const fromDate = document.querySelector('[name="from_date"]').value;
        const toDate = document.querySelector('[name="to_date"]').value;

        // Simulate export
        setTimeout(() => {
            hideLoading();
            showToast(`✅ Exporting as ${format.toUpperCase()}...`, 'success');
        }, 1500);
    }

    function exportTable(type) {
        showLoading();

        // Simulate export
        setTimeout(() => {
            hideLoading();
            showToast(`✅ Exporting ${type} data...`, 'success');
        }, 1000);
    }

    // Initialize charts when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Trend Chart
        if (document.getElementById('dailyTrendChart') && dailyData.length > 0) {
            const ctx = document.getElementById('dailyTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dailyData.map(d => d.date),
                    datasets: [{
                        label: 'Shipments',
                        data: dailyData.map(d => d.total),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Courier Distribution Chart
        if (document.getElementById('courierChart') && courierData.length > 0) {
            const ctx = document.getElementById('courierChart').getContext('2d');
            const colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#6b7280'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: courierData.map(c => c.courier_partner || 'Not Assigned'),
                    datasets: [{
                        data: courierData.map(c => c.total),
                        backgroundColor: colors.slice(0, courierData.length),
                        borderWidth: 0
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
                                padding: 15
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        // Status Distribution Chart
        if (document.getElementById('statusChart')) {
            const ctx = document.getElementById('statusChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Delivered', 'In Transit', 'Pending', 'Failed/Returned'],
                    datasets: [{
                        data: [
                            stats.delivered || 0,
                            stats.in_transit || 0,
                            stats.pending || 0,
                            stats.failed || 0
                        ],
                        backgroundColor: [
                            '#10b981',
                            '#f59e0b',
                            '#8b5cf6',
                            '#ef4444'
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // City Chart
        if (document.getElementById('cityChart') && cityData.length > 0) {
            const ctx = document.getElementById('cityChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: cityData.slice(0, 10).map(c => c.city),
                    datasets: [{
                        label: 'Shipments',
                        data: cityData.slice(0, 10).map(c => c.total),
                        backgroundColor: '#8b5cf6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });

    // Auto-refresh every 10 minutes
    setInterval(() => {
        if (!document.getElementById('loadingOverlay').style.display === 'flex') {
            location.reload();
        }
    }, 600000);
</script>
@endsection
