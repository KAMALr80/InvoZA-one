@extends('layouts.app')
<style>
    <style>

    /* ====== DASHBOARD LAYOUT ====== */
    .dashboard-container {
        max-width: 1300px;
        margin: 30px auto;
        padding: 25px;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .section-gap {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
    }

    .page-subtitle {
        color: #64748b;
        margin-top: 5px;
    }

    /* ====== STATS GRID ====== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }

    .stat-card {
        padding: 22px;
        border-radius: 16px;
        color: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        margin-top: 8px;
    }

    /* ====== COLORS ====== */
    .bg-blue {
        background: linear-gradient(135deg, #2563eb, #1e40af);
    }

    .bg-green {
        background: linear-gradient(135deg, #16a34a, #166534);
    }

    .bg-purple {
        background: linear-gradient(135deg, #9333ea, #6b21a8);
    }

    .bg-orange {
        background: linear-gradient(135deg, #f97316, #c2410c);
    }

    .bg-gray {
        background: linear-gradient(135deg, #475569, #1e293b);
    }

    /* ====== SECTIONS ====== */
    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .ai-insight {
        margin-top: 15px;
        padding: 15px;
        border-radius: 12px;
        background: #f1f5f9;
        font-weight: 600;
    }

    /* ====== CHARTS ====== */
    .charts-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    .chart-title {
        font-weight: 700;
        margin-bottom: 5px;
    }

    .chart-subtitle {
        color: #64748b;
        font-size: 14px;
    }

    .chart-wrapper {
        height: 280px;
    }

    /* ====== TABLE CARD ====== */
    .card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 10px;
        background: #f8fafc;
    }

    .table td {
        padding: 10px;
        border-top: 1px solid #e5e7eb;
    }

    /* ====== BUTTON ====== */
    .btn-dark {
        background: #1e293b;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    /* ====== TEXT ====== */
    .text-danger {
        color: #dc2626;
    }

    .text-success {
        color: #16a34a;
    }
</style>

</style>

@section('content')

    <div class="dashboard-container">

        {{-- PAGE HEADER --}}
        <div class="section-gap">
            <h2 class="page-title">📊 Admin Dashboard</h2>
            <p class="page-subtitle">Business & Organization Overview</p>
        </div>

        {{-- ================= BUSINESS STATS ================= --}}
        <div class="stats-grid section-gap">

            <div class="stat-card bg-blue">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
            </div>

            <div class="stat-card bg-green">
                <div class="stat-label">Today's Sales</div>
                <div class="stat-value">
                    ₹ {{ number_format($todaySales ?? 0, 2) }}
                </div>
            </div>

            <div class="stat-card bg-purple">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">
                    ₹ {{ number_format($totalRevenue ?? 0, 2) }}
                </div>
            </div>

            <div class="stat-card bg-orange">
                <div class="stat-label">Transactions</div>
                <div class="stat-value">{{ $totalTransactions ?? 0 }}</div>
            </div>

            <div class="stat-card bg-gray">
                <div class="stat-label">Average Sale</div>
                <div class="stat-value">
                    ₹ {{ number_format($averageSale ?? 0, 2) }}
                </div>
            </div>

        </div>

        {{-- ================= AI SALES PREDICTION ================= --}}
        <div class="section-gap">

            <h3 class="section-title">🤖 AI Sales Forecast</h3>

            <div class="stats-grid">

                <div class="stat-card bg-purple">
                    <div class="stat-label">Expected Sales (Next 30 Days)</div>
                    <div class="stat-value">
                        ₹ {{ number_format($aiPrediction['next_30_days_total'] ?? 0, 2) }}
                    </div>
                </div>

                <div class="stat-card bg-blue">
                    <div class="stat-label">Average Daily Sales (AI)</div>
                    <div class="stat-value">
                        ₹ {{ number_format($aiPrediction['daily_prediction_avg'] ?? 0, 2) }}
                    </div>
                </div>

            </div>

            <div class="ai-insight">
                🧠 AI Insight:
                @if (($aiPrediction['daily_prediction_avg'] ?? 0) > ($averageSale ?? 0))
                    Sales trend is improving 📈. Consider preparing inventory.
                @else
                    Sales are stable. Focus on promotions for growth.
                @endif
            </div>

        </div>

        {{-- ================= EMPLOYEE STATS ================= --}}
        <div class="stats-grid section-gap">

            <div class="stat-card bg-blue">
                <div class="stat-label">Total Employees</div>
                <div class="stat-value">{{ $totalEmployees ?? 0 }}</div>
            </div>

            <div class="stat-card bg-green">
                <div class="stat-label">Present Today</div>
                <div class="stat-value">{{ $presentToday ?? 0 }}</div>
            </div>

            <div class="stat-card bg-orange">
                <div class="stat-label">Absent Today</div>
                <div class="stat-value">{{ $absentToday ?? 0 }}</div>
            </div>

        </div>

        <div class="charts-row">

            {{-- Attendance --}}
            <div class="chart-card">
                <h3 class="chart-title">🕒 Attendance</h3>
                <div class="chart-wrapper">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            {{-- AI Forecast vs Past Sales --}}
            <div class="chart-card">
                <h3 class="chart-title">🤖 AI Forecast vs Past Sales</h3>
                <p class="chart-subtitle">
                    Comparison of historical sales and AI predicted future sales
                </p>
                <div class="chart-wrapper">
                    <canvas id="aiSalesChart"></canvas>
                </div>
            </div>

        </div>


        {{-- ================= LOW STOCK ================= --}}
        <div class="card">

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                <h3 class="text-danger">⚠️ Low Stock Products</h3>
                <a href="{{ route('purchases.index') }}" class="btn-dark">⚙️ Manage</a>
            </div>

            @if (isset($lowStockProducts) && $lowStockProducts->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td class="text-danger">{{ $p->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-success">✔ All products sufficiently stocked</p>
            @endif

        </div>

    </div>

    {{-- ================= CHART JS ================= --}}
    <script>
        new Chart(document.getElementById('attendanceChart'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [{{ $presentToday ?? 0 }}, {{ $absentToday ?? 0 }}],
                    backgroundColor: ['#16a34a', '#dc2626']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%'
            }
        });

        const pastLabels = @json($pastLabels ?? []);
        const pastData = @json($pastData ?? []);
        const futureLabels = @json($futureLabels ?? []);
        const futureData = @json($futureData ?? []);

        new Chart(document.getElementById('aiSalesChart'), {
            type: 'line',
            data: {
                labels: [...pastLabels, ...futureLabels],
                datasets: [{
                        label: 'Past Sales',
                        data: [...pastData, ...Array(futureData.length).fill(null)],
                        borderColor: '#2563eb',
                        fill: true
                    },
                    {
                        label: 'AI Predicted Sales',
                        data: [...Array(pastData.length).fill(null), ...futureData],
                        borderColor: '#9333ea',
                        borderDash: [6, 6]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>

@endsection

@include('partials.ai_assistant')
