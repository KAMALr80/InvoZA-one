@extends('layouts.app')

@section('content')

    <div style="max-width:1300px;margin:auto;">

        {{-- PAGE HEADER --}}
        <div style="margin-bottom:30px;">
            <h2 style="margin:0;font-size:30px;font-weight:800;color:#111827;">
                📊 Admin Dashboard
            </h2>
            <p style="margin-top:6px;color:#6b7280;">
                Business & Organization Overview
            </p>
        </div>

        {{-- ================= BUSINESS STATS ================= --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:22px;margin-bottom:40px;">

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#0ea5e9,#0369a1);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Total Products</div>
                <div style="font-size:34px;font-weight:800;">{{ $totalProducts ?? 0 }}</div>
            </div>

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#22c55e,#15803d);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Today's Sales</div>
                <div style="font-size:32px;font-weight:800;">
                    ₹ {{ number_format($todaySales ?? 0, 2) }}
                </div>
            </div>

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#a855f7,#6b21a8);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Total Revenue</div>
                <div style="font-size:32px;font-weight:800;">
                    ₹ {{ number_format($totalRevenue ?? 0, 2) }}
                </div>
            </div>

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#f97316,#c2410c);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Transactions</div>
                <div style="font-size:34px;font-weight:800;">{{ $totalTransactions ?? 0 }}</div>
            </div>

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#64748b,#334155);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Average Sale</div>
                <div style="font-size:30px;font-weight:800;">
                    ₹ {{ number_format($averageSale ?? 0, 2) }}
                </div>
            </div>

        </div>

        {{-- ================= EMPLOYEE STATS ================= --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:22px;margin-bottom:40px;">

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Total Employees</div>
                <div style="font-size:36px;font-weight:800;">{{ $totalEmployees ?? 0 }}</div>
            </div>

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Present Today</div>
                <div style="font-size:36px;font-weight:800;">{{ $presentToday ?? 0 }}</div>
            </div>

            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#dc2626,#991b1b);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Absent Today</div>
                <div style="font-size:36px;font-weight:800;">{{ $absentToday ?? 0 }}</div>
            </div>

        </div>

        {{-- ================= CHARTS ================= --}}
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:26px;margin-bottom:40px;">

            <div style="background:#fff;padding:26px;border-radius:18px;box-shadow:0 12px 30px rgba(0,0,0,.08);">
                <h3 style="margin:0;font-size:18px;font-weight:700;">📈 Sales (Last 7 Days)</h3>
                <div style="height:320px;margin-top:10px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div style="background:#fff;padding:26px;border-radius:18px;box-shadow:0 12px 30px rgba(0,0,0,.08);">
                <h3 style="margin:0;font-size:18px;font-weight:700;">🕒 Attendance</h3>
                <div style="height:320px;margin-top:10px;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

        </div>

        {{-- ================= LOW STOCK ================= --}}
        <div style="background:#fff;padding:26px;border-radius:18px;box-shadow:0 12px 30px rgba(0,0,0,.08);">

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                <h3 style="margin:0;color:#dc2626;font-weight:700;">⚠️ Low Stock Products</h3>

                <a href="{{ route('purchases.index') }}"
                    style="background:#111827;color:#fff;padding:8px 14px;border-radius:8px;text-decoration:none;">
                    ⚙️ Manage
                </a>
            </div>

            @if (isset($lowStockProducts) && $lowStockProducts->count())
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#fee2e2;">
                            <th style="padding:10px;text-align:left;">Product</th>
                            <th style="padding:10px;">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $p)
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <td style="padding:10px;">{{ $p->name }}</td>
                                <td style="padding:10px;color:#dc2626;font-weight:700;">
                                    {{ $p->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#16a34a;font-weight:600;">
                    ✔ All products sufficiently stocked
                </p>
            @endif
        </div>

    </div>

    {{-- ================= CHART JS ================= --}}
    <script>
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: @json($salesLabels ?? []),
                datasets: [{
                    data: @json($salesData ?? []),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,.25)',
                    tension: .45,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('attendanceChart'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [
                        {{ $presentToday ?? 0 }},
                        {{ $absentToday ?? 0 }}
                    ],
                    backgroundColor: ['#16a34a', '#dc2626']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%'
            }
        });
    </script>

@endsection
