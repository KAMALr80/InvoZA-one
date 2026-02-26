<!-- Tabler CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">

<!-- Tabler JS -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    {{-- ================= HEADER ================= --}}
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="dashboard-title">
                    📊 Business Intelligence Dashboard
                </h1>
                <p class="dashboard-subtitle">
                    Real-time insights & AI-powered analytics for smarter decisions
                </p>
            </div>
            <div class="header-date">
                <span class="date-icon">📅</span>
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </div>

    {{-- ================= TOP STATS CARDS ================= --}}
    <div class="stats-grid">
        <div class="stat-card stat-card-blue">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                <div class="stat-desc">Active inventory items</div>
            </div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <div class="stat-label">Today's Sales</div>
                <div class="stat-value">₹ {{ number_format($todaySales ?? 0, 2) }}</div>
                <div class="stat-desc">Revenue generated today</div>
            </div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-icon">📈</div>
            <div class="stat-content">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">₹ {{ number_format($totalRevenue ?? 0, 2) }}</div>
                <div class="stat-desc">Lifetime earnings</div>
            </div>
        </div>

        <div class="stat-card stat-card-orange">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <div class="stat-label">Average Sale</div>
                <div class="stat-value">₹ {{ number_format($averageSale ?? 0, 2) }}</div>
                <div class="stat-desc">Per transaction average</div>
            </div>
        </div>
    </div>

    {{-- ================= CHARTS ROW ================= --}}
    <div class="charts-row">
        {{-- Updated Attendance Chart --}}
        <div class="chart-container">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">👥 Employee Attendance Today</h3>
                    <p class="chart-subtitle">Complete workforce status breakdown</p>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #10b981;"></div>
                        <span class="legend-label">Present</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ef4444;"></div>
                        <span class="legend-label">Absent</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #f59e0b;"></div>
                        <span class="legend-label">Late</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #8b5cf6;"></div>
                        <span class="legend-label">Half Day</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #6b7280;"></div>
                        <span class="legend-label">Not Marked</span>
                    </div>
                </div>
            </div>
            <div class="chart-canvas-container">
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="chart-summary">
                <div class="summary-item">
                    <span class="summary-label">Total Employees:</span>
                    <span class="summary-value">{{ $totalEmployees ?? 0 }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Marked Today:</span>
                    <span class="summary-value">
                        {{ ($presentToday ?? 0) + ($absentToday ?? 0) + ($lateToday ?? 0) + ($halfDayToday ?? 0) }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Not Marked:</span>
                    <span class="summary-value">{{ $notMarkedToday ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- AI Sales Forecast Chart --}}
        <div class="chart-container">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">🤖 AI Sales Forecast vs Actual</h3>
                    <p class="chart-subtitle">Past performance and future predictions</p>
                </div>
            </div>
            <div class="chart-canvas-container">
                <canvas id="aiSalesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= EMPLOYEE STATS ================= --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title-container">
                <div class="section-icon-container section-icon-blue">
                    👥
                </div>
                <div>
                    <h2 class="section-title">Employee Overview</h2>
                    <p class="section-subtitle">Workforce management insights</p>
                </div>
            </div>
        </div>

        <div class="employee-stats-grid">
            <div class="employee-stat-card employee-stat-blue">
                <div class="employee-stat-label">Total Employees</div>
                <div class="employee-stat-value">{{ $totalEmployees ?? 0 }}</div>
            </div>

            <div class="employee-stat-card employee-stat-green">
                <div class="employee-stat-label">Present Today</div>
                <div class="employee-stat-value">{{ $presentToday ?? 0 }}</div>
                <div class="employee-stat-percentage">
                    {{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0 }}% attendance
                </div>
            </div>

            <div class="employee-stat-card employee-stat-red">
                <div class="employee-stat-label">Absent Today</div>
                <div class="employee-stat-value">{{ $absentToday ?? 0 }}</div>
                <div class="employee-stat-percentage">
                    {{ $totalEmployees > 0 ? round(($absentToday / $totalEmployees) * 100, 1) : 0 }}% absence rate
                </div>
            </div>

            <div class="employee-stat-card employee-stat-orange">
                <div class="employee-stat-label">Late Today</div>
                <div class="employee-stat-value">{{ $lateToday ?? 0 }}</div>
                <div class="employee-stat-percentage">
                    {{ $totalEmployees > 0 ? round(($lateToday / $totalEmployees) * 100, 1) : 0 }}% late
                </div>
            </div>

            <div class="employee-stat-card employee-stat-purple">
                <div class="employee-stat-label">Half Day</div>
                <div class="employee-stat-value">{{ $halfDayToday ?? 0 }}</div>
                <div class="employee-stat-percentage">
                    {{ $totalEmployees > 0 ? round(($halfDayToday / $totalEmployees) * 100, 1) : 0 }}% half day
                </div>
            </div>

            <div class="employee-stat-card employee-stat-gray">
                <div class="employee-stat-label">Not Marked</div>
                <div class="employee-stat-value">{{ $notMarkedToday ?? 0 }}</div>
                <div class="employee-stat-percentage">
                    {{ $totalEmployees > 0 ? round(($notMarkedToday / $totalEmployees) * 100, 1) : 0 }}% pending
                </div>
            </div>
        </div>
    </div>

    {{-- ================= LOW STOCK ALERT ================= --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title-container">
                <div class="section-icon-container section-icon-red">
                    ⚠️
                </div>
                <div>
                    <h2 class="section-title">Low Stock Alert</h2>
                    <p class="section-subtitle">Products requiring immediate attention</p>
                </div>
            </div>
            <a href="{{ route('purchases.index') }}" class="inventory-btn">
                ⚙️ Manage Inventory
            </a>
        </div>

        @if (isset($lowStockProducts) && $lowStockProducts->count())
            <div class="low-stock-table-container">
                <table class="low-stock-table">
                    <thead>
                        <tr>
                            <th class="table-header">Product Name</th>
                            <th class="table-header text-center">Current Stock</th>
                            <th class="table-header text-center">Status</th>
                            <th class="table-header text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $p)
                            <tr class="table-row">
                                <td class="table-cell product-name">{{ $p->name }}</td>
                                <td class="table-cell text-center">
                                    <span class="stock-badge">
                                        {{ $p->quantity }} units
                                    </span>
                                </td>
                                <td class="table-cell text-center">
                                    @if ($p->quantity <= 5)
                                        <span class="status-badge status-critical">
                                            🔴 Critical
                                        </span>
                                    @elseif($p->quantity <= 10)
                                        <span class="status-badge status-warning">
                                            🟡 Warning
                                        </span>
                                    @else
                                        <span class="status-badge status-low">
                                            ⚪ Low
                                        </span>
                                    @endif
                                </td>
                                <td class="table-cell text-right">
                                    <a href="{{ route('purchases.create') }}" class="reorder-btn">
                                        📦 Reorder
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-stock-alert">
                <div class="no-stock-icon">✅</div>
                <h3 class="no-stock-title">All products sufficiently stocked</h3>
                <p class="no-stock-text">Your inventory levels are healthy. No immediate action needed.</p>
            </div>
        @endif
    </div>
</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle responsive chart resizing
        function handleResize() {
            if (attendanceChart) attendanceChart.resize();
            if (aiSalesChart) aiSalesChart.resize();
        }

        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(handleResize, 250);
        });

        // Updated Attendance Chart with all 5 categories
        const attendanceCtx = document.getElementById('attendanceChart');
        let attendanceChart;
        if (attendanceCtx) {
            attendanceChart = new Chart(attendanceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent', 'Late', 'Half Day', 'Not Marked'],
                    datasets: [{
                        data: [
                            {{ $presentToday ?? 0 }},
                            {{ $absentToday ?? 0 }},
                            {{ $lateToday ?? 0 }},
                            {{ $halfDayToday ?? 0 }},
                            {{ $notMarkedToday ?? 0 }}
                        ],
                        backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#6b7280'],
                        borderWidth: 0,
                        borderRadius: 10,
                        borderColor: '#ffffff',
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: window.innerWidth < 768 ? '60%' : '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: window.innerWidth < 768 ? 8 : 12,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // AI Sales Chart
        const aiSalesCtx = document.getElementById('aiSalesChart');
        let aiSalesChart;
        if (aiSalesCtx) {
            const pastLabels = @json($pastLabels ?? []);
            const pastData = @json($pastData ?? []);
            const futureLabels = @json($futureLabels ?? []);
            const futureData = @json($futureData ?? []);

            aiSalesChart = new Chart(aiSalesCtx, {
                type: 'line',
                data: {
                    labels: [...pastLabels, ...futureLabels],
                    datasets: [
                        {
                            label: 'Past Sales',
                            data: [...pastData, ...Array(futureData.length).fill(null)],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: window.innerWidth < 768 ? 2 : 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: window.innerWidth < 768 ? 3 : 5,
                            pointHoverRadius: window.innerWidth < 768 ? 5 : 7
                        },
                        {
                            label: 'AI Predicted Sales',
                            data: [...Array(pastData.length).fill(null), ...futureData],
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.05)',
                            borderWidth: window.innerWidth < 768 ? 2 : 3,
                            borderDash: [8, 4],
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#8b5cf6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: window.innerWidth < 768 ? 3 : 5,
                            pointHoverRadius: window.innerWidth < 768 ? 5 : 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: window.innerWidth < 768 ? 11 : 13,
                                    weight: '600'
                                },
                                padding: window.innerWidth < 768 ? 15 : 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: window.innerWidth < 768 ? 8 : 12
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(229, 231, 235, 0.5)' },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 9 : 11
                                },
                                maxRotation: window.innerWidth < 768 ? 45 : 30
                            }
                        },
                        y: {
                            grid: { color: 'rgba(229, 231, 235, 0.5)' },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 9 : 11
                                },
                                callback: function(value) {
                                    if (window.innerWidth < 480) {
                                        return '₹' + (value/1000).toFixed(1) + 'k';
                                    }
                                    return '₹' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<style>
    /* Base Styles */
    .dashboard-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
        width: 100%;
    }

    /* Header Styles */
    .dashboard-header {
        margin-bottom: 30px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-left {
        flex: 1;
        min-width: 280px;
    }

    .dashboard-title {
        margin: 0;
        font-size: clamp(24px, 4vw, 32px);
        font-weight: 900;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
        word-break: break-word;
    }

    .dashboard-subtitle {
        margin: 8px 0 0 0;
        color: #64748b;
        font-size: clamp(14px, 2vw, 16px);
        font-weight: 500;
        word-break: break-word;
    }

    .header-date {
        background: white;
        padding: 10px 18px;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        font-weight: 600;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        font-size: clamp(13px, 2vw, 15px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        width: 100%;
    }

    .stat-card {
        padding: clamp(16px, 3vw, 24px);
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
        min-height: 140px;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card-blue {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    }

    .stat-card-green {
        background: linear-gradient(135deg, #16a34a 0%, #166534 100%);
    }

    .stat-card-purple {
        background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
    }

    .stat-card-orange {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }

    .stat-icon {
        position: absolute;
        right: 15px;
        top: 15px;
        opacity: 0.2;
        font-size: clamp(36px, 6vw, 48px);
    }

    .stat-content {
        position: relative;
        z-index: 2;
    }

    .stat-label {
        font-size: clamp(12px, 2vw, 14px);
        font-weight: 600;
        opacity: 0.9;
        margin-bottom: 8px;
        word-break: break-word;
    }

    .stat-value {
        font-size: clamp(28px, 5vw, 36px);
        font-weight: 900;
        margin-bottom: 4px;
        line-height: 1;
        word-break: break-word;
    }

    .stat-desc {
        font-size: clamp(11px, 1.5vw, 12px);
        opacity: 0.8;
        margin-top: 8px;
        word-break: break-word;
    }

    /* Charts Row */
    .charts-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        width: 100%;
    }

    .chart-container {
        background: white;
        border-radius: 24px;
        padding: clamp(16px, 3vw, 25px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(229, 231, 235, 0.8);
        width: 100%;
    }

    .chart-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .chart-title {
        margin: 0;
        font-size: clamp(16px, 3vw, 20px);
        font-weight: 800;
        color: #1f2937;
        word-break: break-word;
    }

    .chart-subtitle {
        margin: 4px 0 0 0;
        color: #6b7280;
        font-size: clamp(12px, 2vw, 14px);
        word-break: break-word;
    }

    .chart-legend {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 2px 0;
    }

    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-label {
        font-size: 11px;
        color: #4b5563;
        white-space: nowrap;
    }

    .chart-canvas-container {
        height: clamp(250px, 40vw, 300px);
        width: 100%;
        margin-bottom: 15px;
    }

    .chart-summary {
        display: flex;
        justify-content: space-around;
        background: #f8fafc;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-top: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .summary-item {
        text-align: center;
        flex: 1;
        min-width: 100px;
    }

    .summary-label {
        display: block;
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 4px;
        font-weight: 600;
        word-break: break-word;
    }

    .summary-value {
        display: block;
        font-size: clamp(16px, 3vw, 20px);
        font-weight: 800;
        color: #1f2937;
        word-break: break-word;
    }

    /* Section Cards */
    .section-card {
        background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
        border-radius: 24px;
        padding: clamp(20px, 4vw, 30px);
        margin-bottom: 30px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(229, 231, 235, 0.8);
        width: 100%;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .section-title-container {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .section-icon-container {
        width: clamp(40px, 8vw, 50px);
        height: clamp(40px, 8vw, 50px);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(20px, 4vw, 24px);
        color: white;
        flex-shrink: 0;
    }

    .section-icon-blue {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .section-icon-red {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .section-title {
        margin: 0;
        font-size: clamp(18px, 4vw, 24px);
        font-weight: 800;
        color: #1f2937;
        word-break: break-word;
    }

    .section-subtitle {
        margin: 4px 0 0 0;
        color: #6b7280;
        font-size: clamp(12px, 2vw, 14px);
        word-break: break-word;
    }

    /* Updated Employee Stats Grid - 6 cards */
    .employee-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        width: 100%;
    }

    .employee-stat-card {
        padding: clamp(12px, 3vw, 20px);
        border-radius: 18px;
        text-align: center;
        border: 2px solid;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .employee-stat-card:hover {
        transform: translateY(-3px);
    }

    .employee-stat-blue {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #bfdbfe;
    }

    .employee-stat-green {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-color: #bbf7d0;
    }

    .employee-stat-red {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-color: #fecaca;
    }

    .employee-stat-orange {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-color: #fde68a;
    }

    .employee-stat-purple {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border-color: #ddd6fe;
    }

    .employee-stat-gray {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-color: #e5e7eb;
    }

    .employee-stat-label {
        font-size: clamp(12px, 2vw, 14px);
        font-weight: 600;
        margin-bottom: 8px;
        color: #374151;
        word-break: break-word;
    }

    .employee-stat-value {
        font-size: clamp(24px, 5vw, 32px);
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 8px;
        line-height: 1;
        word-break: break-word;
    }

    .employee-stat-percentage {
        font-size: clamp(11px, 2vw, 13px);
        color: #6b7280;
        font-weight: 500;
        word-break: break-word;
    }

    /* Low Stock Table */
    .low-stock-table-container {
        background: #fef2f2;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #fecaca;
        overflow-x: auto;
        width: 100%;
    }

    .low-stock-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .table-header {
        padding: 12px 16px;
        text-align: left;
        color: #991b1b;
        font-weight: 700;
        font-size: clamp(12px, 2vw, 14px);
        background: #fecaca;
        white-space: nowrap;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .table-row {
        border-bottom: 1px solid #fecaca;
        transition: background 0.2s ease;
    }

    .table-row:hover {
        background-color: #fef2f2;
    }

    .table-row:last-child {
        border-bottom: none;
    }

    .table-cell {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: clamp(12px, 2vw, 14px);
    }

    .product-name {
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
    }

    .stock-badge {
        background: #fee2e2;
        color: #dc2626;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 800;
        font-size: clamp(13px, 2vw, 15px);
        display: inline-block;
        min-width: 70px;
        white-space: nowrap;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: clamp(11px, 2vw, 13px);
        display: inline-block;
        min-width: 80px;
        white-space: nowrap;
    }

    .status-critical {
        background: #dc2626;
        color: white;
    }

    .status-warning {
        background: #f59e0b;
        color: white;
    }

    .status-low {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Buttons */
    .inventory-btn {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: clamp(13px, 2vw, 14px);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(30, 41, 59, 0.2);
        white-space: nowrap;
    }

    .inventory-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 41, 59, 0.3);
        color: white;
    }

    .reorder-btn {
        background: #dc2626;
        color: white;
        padding: 6px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: clamp(12px, 2vw, 13px);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .reorder-btn:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        color: white;
    }

    /* No Stock Alert */
    .no-stock-alert {
        background: #f0fdf4;
        border: 2px dashed #bbf7d0;
        border-radius: 16px;
        padding: clamp(20px, 5vw, 40px);
        text-align: center;
    }

    .no-stock-icon {
        font-size: clamp(36px, 8vw, 48px);
        margin-bottom: 16px;
    }

    .no-stock-title {
        margin: 0 0 8px 0;
        color: #166534;
        font-weight: 700;
        font-size: clamp(16px, 3vw, 18px);
        word-break: break-word;
    }

    .no-stock-text {
        color: #15803d;
        margin: 0;
        font-size: clamp(13px, 2vw, 14px);
        word-break: break-word;
    }

    /* ================= RESPONSIVE BREAKPOINTS ================= */
    
    /* Large Desktop (1200px and above) */
    @media (min-width: 1200px) {
        .charts-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Desktop (992px to 1199px) */
    @media (max-width: 1199px) {
        .employee-stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Tablet (768px to 991px) */
    @media (max-width: 991px) {
        .dashboard-container {
            padding: 15px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .employee-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .chart-legend {
            width: 100%;
        }

        .legend-item {
            flex: 1 1 auto;
        }
    }

    /* Mobile Landscape (576px to 767px) */
    @media (max-width: 767px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-date {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            min-height: 120px;
        }

        .charts-row {
            grid-template-columns: 1fr;
        }

        .chart-container {
            padding: 15px;
        }

        .chart-summary {
            flex-direction: column;
            gap: 10px;
        }

        .employee-stats-grid {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: stretch;
        }

        .inventory-btn {
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }

        .low-stock-table {
            min-width: 500px;
        }
    }

    /* Mobile Portrait (up to 575px) */
    @media (max-width: 575px) {
        .dashboard-container {
            padding: 12px;
        }

        .section-title-container {
            width: 100%;
        }

        .section-icon-container {
            width: 35px;
            height: 35px;
            font-size: 18px;
        }

        .table-cell {
            padding: 10px;
        }

        .stock-badge {
            padding: 3px 10px;
            min-width: 60px;
        }

        .status-badge {
            padding: 3px 10px;
            min-width: 70px;
        }

        .reorder-btn {
            padding: 5px 12px;
        }
    }

    /* Extra Small Devices (up to 360px) */
    @media (max-width: 360px) {
        .dashboard-container {
            padding: 10px;
        }

        .dashboard-title {
            font-size: 20px;
        }

        .header-date {
            padding: 8px 12px;
            font-size: 12px;
        }

        .stat-value {
            font-size: 24px;
        }

        .stat-icon {
            font-size: 32px;
        }

        .employee-stat-value {
            font-size: 24px;
        }

        .low-stock-table {
            min-width: 400px;
        }

        .table-cell {
            padding: 8px;
            font-size: 11px;
        }

        .stock-badge,
        .status-badge {
            font-size: 11px;
            padding: 2px 8px;
        }

        .reorder-btn {
            padding: 4px 10px;
            font-size: 11px;
        }
    }

    /* Print Styles */
    @media print {
        .dashboard-container {
            background: white;
            padding: 0;
        }

        .inventory-btn,
        .reorder-btn,
        .header-date {
            display: none !important;
        }

        .stat-card {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .chart-container {
            break-inside: avoid;
        }
    }
</style>
@endsection

