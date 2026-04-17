@extends('layouts.app')

@section('content')
    <div class="dashboard-container">
        <!-- ================= HEADER ================= -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="dashboard-title">📊 Business Intelligence Dashboard</h1>
                    <p class="dashboard-subtitle">Real-time insights & AI-powered analytics for smarter decisions</p>
                </div>
                <div class="header-date">
                    <span class="date-icon">📅</span>
                    {{ now()->format('l, F j, Y') }}
                </div>
            </div>
        </div>

        <!-- ================= TOP STATS CARDS ================= -->
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
                    <div class="stat-value" id="todaySalesValue">
                        @if (($todaySales ?? 0) > 0)
                            ₹ {{ number_format($todaySales, 2) }}
                        @else
                            <span class="no-sale">🚫 No sales today</span>
                        @endif
                    </div>
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

        <!-- ================= CHARTS ROW ================= -->
        <div class="charts-row">
            <!-- Attendance Chart -->
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
                        <span
                            class="summary-value">{{ ($presentToday ?? 0) + ($absentToday ?? 0) + ($lateToday ?? 0) + ($halfDayToday ?? 0) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Not Marked:</span>
                        <span class="summary-value">{{ $notMarkedToday ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <!-- AI Sales Forecast Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title" id="aiChartTitle">🤖 Chronos-2 AI Forecast</h3>
                        <p class="chart-subtitle">Last 15 days actual + Next 15 days AI prediction</p>
                    </div>
                    <div class="api-status" id="apiStatus">
                        <span class="status-indicator loading"></span>
                        <span id="statusText">Connecting to Chronos-2 AI...</span>
                    </div>
                </div>
                <div class="chart-canvas-container" style="position: relative;">
                    <canvas id="aiSalesChart"></canvas>
                </div>
                <div id="aiInsightsContainer"></div>
            </div>
        </div>

        <!-- ================= EMPLOYEE STATS ================= -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title-container">
                    <div class="section-icon-container section-icon-blue">👥</div>
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
                        {{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0 }}% attendance</div>
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
                        {{ $totalEmployees > 0 ? round(($lateToday / $totalEmployees) * 100, 1) : 0 }}% late</div>
                </div>
                <div class="employee-stat-card employee-stat-purple">
                    <div class="employee-stat-label">Half Day</div>
                    <div class="employee-stat-value">{{ $halfDayToday ?? 0 }}</div>
                    <div class="employee-stat-percentage">
                        {{ $totalEmployees > 0 ? round(($halfDayToday / $totalEmployees) * 100, 1) : 0 }}% half day</div>
                </div>
                <div class="employee-stat-card employee-stat-gray">
                    <div class="employee-stat-label">Not Marked</div>
                    <div class="employee-stat-value">{{ $notMarkedToday ?? 0 }}</div>
                    <div class="employee-stat-percentage">
                        {{ $totalEmployees > 0 ? round(($notMarkedToday / $totalEmployees) * 100, 1) : 0 }}% pending</div>
                </div>
            </div>
        </div>

        <!-- ================= LOW STOCK ALERT & RECENT ACTIVITY ================= -->
        <div class="dashboard-row">
            <!-- Low Stock Alert -->
            <div class="low-stock-section">
                <div class="section-header">
                    <div class="section-title-container">
                        <div class="section-icon-container section-icon-red">⚠️</div>
                        <div>
                            <h2 class="section-title">Low Stock Alert</h2>
                            <p class="section-subtitle">Products requiring immediate attention</p>
                        </div>
                    </div>
                    <a href="{{ route('purchases.index') }}" class="inventory-btn">⚙️ Manage Inventory</a>
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
                                        <td class="table-cell text-center"><span class="stock-badge">{{ $p->quantity }}
                                                units</span></td>
                                        <td class="table-cell text-center">
                                            @if ($p->quantity <= 5)
                                                <span class="status-badge status-critical">🔴 Critical</span>
                                            @elseif($p->quantity <= 10)
                                                <span class="status-badge status-warning">🟡 Warning</span>
                                            @else
                                                <span class="status-badge status-low">⚪ Low</span>
                                            @endif
                                        </td>
                                        <td class="table-cell text-right"><a href="{{ route('purchases.create') }}"
                                                class="reorder-btn">📦 Reorder</a></td>
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
            <!-- Recent Activity Feed -->
            <div class="recent-activity-section">
                <div class="section-header">
                    <div class="section-title-container">
                        <div class="section-icon-container section-icon-purple">🔄</div>
                        <div>
                            <h2 class="section-title">Recent Activity</h2>
                            <p class="section-subtitle">Latest system updates and actions</p>
                        </div>
                    </div>
                    <div class="activity-filter">
                        <select class="activity-filter-select" id="activityFilter"
                            onchange="filterActivities(this.value)">
                            <option value="all">All Activities</option>
                            <option value="sales">Sales</option>
                            <option value="purchases">Purchases</option>
                            <option value="attendance">Attendance</option>
                            <option value="products">Products</option>
                        </select>
                    </div>
                </div>
                <div class="activity-feed-container" id="activityFeedContainer">
                    @if (isset($recentActivities) && count($recentActivities) > 0)
                        @foreach ($recentActivities as $activity)
                            <div class="activity-item activity-{{ $activity['color'] ?? 'blue' }}"
                                data-type="{{ $activity['type'] ?? 'all' }}">
                                <div class="activity-marker">
                                    <div class="activity-icon">{{ $activity['icon'] ?? '📌' }}</div>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-header">
                                        <h4 class="activity-title">{{ $activity['title'] ?? 'Activity' }}</h4>
                                        <span class="activity-time">{{ $activity['time'] ?? 'Just now' }}</span>
                                    </div>
                                    <p class="activity-description">
                                        {{ $activity['description'] ?? 'No description available' }}</p>
                                    <div class="activity-footer">
                                        <span class="activity-user">
                                            <span class="user-avatar">👤</span>
                                            {{ $activity['user'] ?? 'System' }}
                                        </span>
                                        <span
                                            class="activity-type type-{{ $activity['type'] ?? 'system' }}">{{ ucfirst($activity['type'] ?? 'System') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-activities">
                            <div class="no-activities-icon">📭</div>
                            <h3 class="no-activities-title">No recent activities</h3>
                            <p class="no-activities-text">Activities will appear here when system events occur.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= EMPLOYEES ON LEAVE NEXT 2 DAYS ================= -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title-container">
                    <div class="section-icon-container"
                        style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">🏖️</div>
                    <div>
                        <h2 class="section-title">Employees on Leave - Next 2 Days</h2>
                        <p class="section-subtitle">Upcoming leave schedule for workforce planning</p>
                    </div>
                </div>
            </div>
            @if (count($employeesOnLeaveNext2Days ?? []) > 0)
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                                <th
                                    style="padding: 12px 15px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">
                                    Employee</th>
                                <th
                                    style="padding: 12px 15px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">
                                    Department</th>
                                <th
                                    style="padding: 12px 15px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">
                                    Leave Type</th>
                                <th
                                    style="padding: 12px 15px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">
                                    From Date</th>
                                <th
                                    style="padding: 12px 15px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">
                                    To Date</th>
                                <th
                                    style="padding: 12px 15px; text-align: center; font-weight: 600; color: #374151; font-size: 13px;">
                                    Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeesOnLeaveNext2Days as $leave)
                                <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;"
                                    onmouseover="this.style.background='#f9fafb'"
                                    onmouseout="this.style.background='transparent'">
                                    <td style="padding: 12px 15px;">
                                        <div class="employee-cell" style="display: flex; align-items: center; gap: 10px;">
                                            <div
                                                style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">
                                                {{ strtoupper(substr($leave['employee_name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: #1f2937; font-size: 14px;">
                                                    {{ $leave['employee_name'] }}</div>
                                                <div style="font-size: 12px; color: #6b7280;">
                                                    {{ $leave['employee_code'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span
                                            style="display: inline-block; padding: 4px 10px; border-radius: 20px; background: #eef2ff; color: #3730a3; font-size: 13px; font-weight: 600;">
                                            {{ $leave['department'] ?? 'Not Assigned' }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span
                                            style="display: inline-block; padding: 4px 10px; border-radius: 20px; background: #fef3c7; color: #92400e; font-size: 13px; font-weight: 600; border: 1px solid #fcd34d;">
                                            {{ $leave['leave_type_label'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px 15px; color: #374151; font-size: 14px;">
                                        {{ $leave['from_date'] }}</td>
                                    <td style="padding: 12px 15px; color: #374151; font-size: 14px;">
                                        {{ $leave['to_date'] }}</td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        <span style="font-weight: 600; color: #3b82f6; font-size: 14px;">
                                            {{ $leave['total_days'] }} day{{ $leave['total_days'] > 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
                    <h3 style="color: #6b7280; margin-bottom: 5px; font-size: 16px; font-weight: 600;">No upcoming leaves
                        in next 2 days</h3>
                    <p style="color: #9ca3af; font-size: 14px; margin-bottom: 15px;">All employees are available for the
                        next 2 days</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ================= CHART JS ================= -->
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

            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart');
            let attendanceChart;
            if (attendanceCtx) {
                attendanceChart = new Chart(attendanceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Present', 'Absent', 'Late', 'Half Day', 'Not Marked'],
                        datasets: [{
                            data: [{{ $presentToday ?? 0 }}, {{ $absentToday ?? 0 }},
                                {{ $lateToday ?? 0 }}, {{ $halfDayToday ?? 0 }},
                                {{ $notMarkedToday ?? 0 }}
                            ],
                            backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#8b5cf6',
                                '#6b7280'
                            ],
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
                                        const percentage = total > 0 ? Math.round((value / total) *
                                            100) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // AI Sales Forecast Chart
            const aiSalesCtx = document.getElementById('aiSalesChart');
            let aiSalesChart;

            if (aiSalesCtx) {
                updateAPIStatus('loading', 'Connecting to Chronos-2 AI...');
                fetch('http://localhost:5001/api/sales-forecast')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            createLiveSalesChart(data.data);
                            const modelName = data.model_used || 'Chronos-2 AI';
                            updateAPIStatus('online', `🤖 ${modelName}`);
                            const todayValue = data.data.analysis?.today_actual || (data.data.past_data ? data
                                .data.past_data[data.data.past_data.length - 1] : 0);
                            const todayCard = document.getElementById('todaySalesValue');
                            if (todayCard) {
                                todayCard.innerHTML = todayValue > 0 ? '₹ ' + parseFloat(todayValue).toFixed(
                                    2) : '<span class="no-sale">🚫 No sales today</span>';
                            }
                            if (data.data.analysis) updateAIInsights(data.data.analysis);
                        } else {
                            throw new Error('Invalid data format');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching forecast:', error);
                        updateAPIStatus('offline', '⚠️ Using sample data (API offline)');
                        createSampleChart();
                    });
            }

            function createLiveSalesChart(chartData) {
                const ctx = document.getElementById('aiSalesChart').getContext('2d');
                if (!chartData.past_labels || !chartData.past_data || !chartData.future_labels || !chartData
                    .future_data) {
                    createSampleChart();
                    return;
                }

                const todayValue = chartData.past_data[chartData.past_data.length - 1];
                const titleEl = document.getElementById('aiChartTitle');
                if (titleEl) {
                    titleEl.innerHTML = todayValue > 0 ?
                        `🤖 Chronos-2 AI Forecast (Today: ₹${parseFloat(todayValue).toFixed(0)})` :
                        `🤖 Chronos-2 AI Forecast (No sales today)`;
                }

                const currentYear = new Date().getFullYear().toString();
                const pastLabels = chartData.past_labels.map(date => {
                    const dateYear = date.substring(0, 4);
                    return dateYear !== currentYear ? currentYear + date.substring(4) : date;
                });
                const futureLabels = chartData.future_labels.map(date => {
                    const dateYear = date.substring(0, 4);
                    return dateYear !== currentYear ? currentYear + date.substring(4) : date;
                });

                const allLabels = [...pastLabels, ...futureLabels];
                const allValues = [...chartData.past_data, ...chartData.future_data].filter(v => v !== null);
                const maxValue = allValues.length > 0 ? Math.max(...allValues) * 1.1 : 2000;
                const suggestedMax = Math.ceil(maxValue / 500) * 500;

                const pastGradient = ctx.createLinearGradient(0, 0, 0, 400);
                pastGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                pastGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

                const futureGradient = ctx.createLinearGradient(0, 0, 0, 400);
                futureGradient.addColorStop(0, 'rgba(139, 92, 246, 0.2)');
                futureGradient.addColorStop(1, 'rgba(139, 92, 246, 0.0)');

                if (aiSalesChart) aiSalesChart.destroy();

                aiSalesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: allLabels,
                        datasets: [{
                            label: 'Actual Sales (Last 15 Days)',
                            data: [...chartData.past_data, ...Array(chartData.future_data.length)
                                .fill(null)
                            ],
                            borderColor: '#3b82f6',
                            backgroundColor: pastGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3b82f6',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }, {
                            label: 'Chronos-2 AI Prediction',
                            data: [...Array(chartData.past_data.length).fill(null), ...chartData
                                .future_data
                            ],
                            borderColor: '#8b5cf6',
                            backgroundColor: futureGradient,
                            borderWidth: 3,
                            borderDash: [8, 4],
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#8b5cf6',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                titleColor: '#1f2937',
                                bodyColor: '#4b5563',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (context.raw !== null) {
                                            label += ': ₹' + parseFloat(context.raw).toLocaleString(
                                                'en-IN', {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                });
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(229, 231, 235, 0.5)'
                                },
                                ticks: {
                                    maxTicksLimit: 8,
                                    maxRotation: 45,
                                    minRotation: 45,
                                    font: {
                                        size: 10
                                    },
                                    callback: function(val, index) {
                                        const label = this.getLabelForValue(val);
                                        if (label && label.length >= 10) {
                                            const monthDay = label.substring(5);
                                            const date = new Date(label);
                                            const dayName = date.toLocaleDateString('en-US', {
                                                weekday: 'short'
                                            });
                                            return `${monthDay} (${dayName})`;
                                        }
                                        return label;
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(229, 231, 235, 0.5)'
                                },
                                suggestedMax: suggestedMax,
                                ticks: {
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString('en-IN');
                                    },
                                    font: {
                                        size: 10
                                    },
                                    stepSize: Math.ceil(suggestedMax / 10)
                                }
                            }
                        }
                    }
                });
                addPredictionLine(chartData.past_labels.length);
            }

            function addPredictionLine(pastCount) {
                const container = document.querySelector('.chart-canvas-container');
                if (!container) return;
                const existingLine = document.getElementById('prediction-line');
                if (existingLine) existingLine.remove();
                const line = document.createElement('div');
                line.id = 'prediction-line';
                line.style.cssText =
                    `position: absolute; top: 0; bottom: 0; width: 2px; background-color: #ef4444; left: calc(${pastCount / 30 * 100}% - 1px); z-index: 10; pointer-events: none;`;
                const label = document.createElement('span');
                label.innerText = 'CHRONOS-2 PREDICTION START';
                label.style.cssText =
                    'position: absolute; top: 10px; left: 10px; background-color: #ef4444; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; transform: translateX(-50%); white-space: nowrap;';
                line.appendChild(label);
                container.style.position = 'relative';
                container.appendChild(line);
            }

            function createSampleChart() {
                const today = new Date();
                const pastLabels = [],
                    futureLabels = [];
                for (let i = 15; i > 0; i--) {
                    const d = new Date(today);
                    d.setDate(d.getDate() - i);
                    pastLabels.push(d.toISOString().split('T')[0]);
                }
                for (let i = 1; i <= 15; i++) {
                    const d = new Date(today);
                    d.setDate(d.getDate() + i);
                    futureLabels.push(d.toISOString().split('T')[0]);
                }
                const samplePast = [1030, 1000, 1040, 575, 1950, 1110, 530, 1065, 550, 1140, 500, 1350, 480, 995,
                    510
                ];
                const sampleFuture = [1050, 1070, 1100, 1080, 1120, 1150, 1180, 1200, 1220, 1250, 1280, 1300, 1320,
                    1350, 1380
                ];
                const ctx = document.getElementById('aiSalesChart').getContext('2d');
                if (aiSalesChart) aiSalesChart.destroy();
                aiSalesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [...pastLabels, ...futureLabels],
                        datasets: [{
                            label: 'Actual Sales (Sample)',
                            data: [...samplePast, ...Array(15).fill(null)],
                            borderColor: '#3b82f6',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: false
                        }, {
                            label: 'Chronos-2 AI (Sample)',
                            data: [...Array(15).fill(null), ...sampleFuture],
                            borderColor: '#8b5cf6',
                            borderWidth: 3,
                            borderDash: [8, 4],
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    maxTicksLimit: 8,
                                    callback: function(val) {
                                        const label = this.getLabelForValue(val);
                                        return label.substring(5);
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                suggestedMax: 2000,
                                ticks: {
                                    stepSize: 500
                                }
                            }
                        }
                    }
                });
                addPredictionLine(15);
                updateAIInsights({
                    today_actual: 1030,
                    tomorrow_prediction: 1050,
                    trend: 'increasing',
                    percentage_change: 2.5,
                    confidence_score: 85,
                    best_day: {
                        date: futureLabels[14],
                        sales: 1380
                    },
                    worst_day: {
                        date: futureLabels[0],
                        sales: 1050
                    }
                });
            }

            function updateAIInsights(analysis) {
                const container = document.getElementById('aiInsightsContainer');
                if (!container) return;
                if (!analysis) {
                    container.innerHTML = '';
                    return;
                }

                let confidenceClass = '';
                if (analysis.confidence_score >= 95) confidenceClass = 'excellent';
                else if (analysis.confidence_score >= 90) confidenceClass = 'good';
                else if (analysis.confidence_score >= 80) confidenceClass = 'average';
                else confidenceClass = 'low';

                container.innerHTML = `
        <div class="ai-insights-panel">
            <h4>🤖 Chronos-2 AI Insights</h4>
            <div class="insight-grid">
                <div class="insight-item">
                    <span class="insight-label">Today's Actual</span>
                    <span class="insight-value">${analysis.today_actual > 0 ? '₹' + analysis.today_actual.toFixed(2) : '🚫 No sales'}</span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Tomorrow's Prediction</span>
                    <span class="insight-value">₹${(analysis.tomorrow_prediction || 0).toFixed(2)}</span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Trend</span>
                    <span class="insight-value ${analysis.trend || 'stable'}">
                        ${analysis.trend === 'increasing' ? '📈' : analysis.trend === 'decreasing' ? '📉' : '📊'} ${analysis.trend ? analysis.trend.charAt(0).toUpperCase() + analysis.trend.slice(1) : 'Stable'}
                    </span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Change</span>
                    <span class="insight-value ${analysis.percentage_change > 0 ? 'positive' : 'negative'}">${analysis.percentage_change > 0 ? '+' : ''}${(analysis.percentage_change || 0).toFixed(1)}%</span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Best Day</span>
                    <span class="insight-value">${analysis.best_day?.date || 'N/A'}<br><small>₹${analysis.best_day?.sales?.toLocaleString() || 0}</small></span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Confidence</span>
                    <span class="insight-value ${confidenceClass}">${analysis.confidence_score?.toFixed(1) || 85}%</span>
                </div>
            </div>
            <div class="model-badge">🚀 Powered by Chronos-2 Foundation Model (2026)</div>
        </div>`;
            }

            function updateAPIStatus(status, message) {
                const statusEl = document.getElementById('apiStatus');
                if (statusEl) statusEl.innerHTML =
                    `<span class="status-indicator ${status}"></span><span>${message}</span>`;
            }
        });

        function filterActivities(type) {
            const activities = document.querySelectorAll('.activity-item');
            activities.forEach(activity => {
                activity.style.display = (type === 'all' || activity.dataset.type === type) ? 'flex' : 'none';
            });
            const visibleActivities = Array.from(activities).filter(a => a.style.display !== 'none');
            const noActivitiesMsg = document.querySelector('.no-activities');
            if (visibleActivities.length === 0 && !noActivitiesMsg) {
                const container = document.getElementById('activityFeedContainer');
                const noActivities = document.createElement('div');
                noActivities.className = 'no-activities';
                noActivities.innerHTML =
                    `<div class="no-activities-icon">🔍</div><h3 class="no-activities-title">No ${type} activities</h3><p class="no-activities-text">There are no ${type} activities to display.</p>`;
                container.appendChild(noActivities);
            } else if (visibleActivities.length > 0 && noActivitiesMsg) {
                noActivitiesMsg.remove();
            }
        }
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

        .no-sale {
            font-size: 18px;
            color: #ffd700;
            background: rgba(255, 215, 0, 0.1);
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
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

        .section-icon-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
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

        /* Dashboard Row */
        .dashboard-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            width: 100%;
        }

        .low-stock-section,
        .recent-activity-section {
            background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
            border-radius: 24px;
            padding: clamp(20px, 4vw, 30px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(229, 231, 235, 0.8);
            width: 100%;
        }

        /* Employee Stats Grid */
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

        /* API Status */
        .api-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 20px;
            font-size: 13px;
            border: 1px solid #e2e8f0;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-indicator.online {
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            animation: pulse 2s infinite;
        }

        .status-indicator.offline {
            background: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .status-indicator.loading {
            background: #f59e0b;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }

            100% {
                opacity: 1;
            }
        }

        /* AI Insights Panel */
        .ai-insights-panel {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .ai-insights-panel h4 {
            margin: 0 0 15px 0;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .insight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .insight-item {
            padding: 12px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .insight-label {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .insight-value {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .insight-value small {
            font-size: 11px;
            font-weight: 400;
            color: #64748b;
        }

        .insight-value.increasing {
            color: #10b981;
        }

        .insight-value.decreasing {
            color: #ef4444;
        }

        .insight-value.positive {
            color: #10b981;
        }

        .insight-value.negative {
            color: #ef4444;
        }

        .insight-value.excellent {
            color: #10b981;
            font-weight: 800;
        }

        .insight-value.good {
            color: #3b82f6;
        }

        .insight-value.average {
            color: #f59e0b;
        }

        .insight-value.low {
            color: #ef4444;
        }

        .model-badge {
            margin-top: 15px;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
        }

        /* Activity Feed */
        .activity-filter {
            min-width: 150px;
        }

        .activity-filter-select {
            padding: 8px 16px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            background: white;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            width: 100%;
        }

        .activity-filter-select:hover {
            border-color: #8b5cf6;
        }

        .activity-filter-select:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .activity-feed-container {
            min-height: 300px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .activity-feed-container::-webkit-scrollbar {
            width: 6px;
        }

        .activity-feed-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .activity-feed-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .activity-feed-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .activity-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #8b5cf6;
        }

        .activity-marker {
            flex-shrink: 0;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid;
        }

        .activity-green .activity-icon {
            border-color: #10b981;
            color: #10b981;
        }

        .activity-blue .activity-icon {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .activity-purple .activity-icon {
            border-color: #8b5cf6;
            color: #8b5cf6;
        }

        .activity-orange .activity-icon {
            border-color: #f97316;
            color: #f97316;
        }

        .activity-red .activity-icon {
            border-color: #ef4444;
            color: #ef4444;
        }

        .activity-content {
            flex: 1;
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .activity-title {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #1f2937;
        }

        .activity-time {
            font-size: 11px;
            color: #6b7280;
            background: #e5e7eb;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 500;
            white-space: nowrap;
        }

        .activity-description {
            margin: 0 0 8px 0;
            color: #4b5563;
            font-size: 13px;
            line-height: 1.5;
        }

        .activity-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .activity-user {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #6b7280;
        }

        .user-avatar {
            font-size: 12px;
        }

        .activity-type {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .type-sales {
            background: #d1fae5;
            color: #065f46;
        }

        .type-purchases {
            background: #ede9fe;
            color: #5b21b6;
        }

        .type-attendance {
            background: #dbeafe;
            color: #1e40af;
        }

        .type-products {
            background: #fed7aa;
            color: #92400e;
        }

        .type-system {
            background: #e5e7eb;
            color: #374151;
        }

        .no-activities {
            text-align: center;
            padding: 50px 20px;
            background: #f8fafc;
            border-radius: 16px;
        }

        .no-activities-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .no-activities-title {
            margin: 0 0 8px 0;
            color: #374151;
            font-size: 18px;
            font-weight: 600;
        }

        .no-activities-text {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-row {
                grid-template-columns: 1fr;
            }
        }

        /* Print */
        @media print {
            .dashboard-container {
                background: white;
                padding: 0;
            }

            .inventory-btn,
            .reorder-btn,
            .header-date,
            .api-status,
            .model-badge,
            .activity-filter {
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
