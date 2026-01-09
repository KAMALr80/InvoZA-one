@extends('layouts.app')

@section('content')
    <div style="max-width:1200px;">

        <h2 style="margin-bottom:25px; color:#111827;">📊 Dashboard</h2>

        {{-- CARDS --}}
        <div
            style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
        gap:20px;
        margin-bottom:35px;
    ">

            <div
                style="
            padding:20px;
            border-radius:12px;
            color:#fff;
            background:linear-gradient(135deg,#2563eb,#1e40af);
        ">
                <h4 style="margin:0; font-size:14px; opacity:0.9;">Total Employees</h4>
                <h1 style="margin-top:10px; font-size:36px;">{{ $totalEmployees }}</h1>
            </div>

            <div
                style="
            padding:20px;
            border-radius:12px;
            color:#fff;
            background:linear-gradient(135deg,#16a34a,#15803d);
        ">
                <h4 style="margin:0; font-size:14px; opacity:0.9;">Present Today</h4>
                <h1 style="margin-top:10px; font-size:36px;">{{ $presentToday }}</h1>
            </div>

            <div
                style="
            padding:20px;
            border-radius:12px;
            color:#fff;
            background:linear-gradient(135deg,#dc2626,#991b1b);
        ">
                <h4 style="margin:0; font-size:14px; opacity:0.9;">Absent Today</h4>
                <h1 style="margin-top:10px; font-size:36px;">{{ $absentToday }}</h1>
            </div>

        </div>

        {{-- CHARTS --}}
        <div style="display:flex; gap:30px; flex-wrap:wrap; align-items:flex-start;">

            {{-- SALES CHART --}}
            <div
                style="
            flex:2;
            background:#fff;
            padding:25px;
            border-radius:12px;
        ">
                <h3 style="margin-top:0; margin-bottom:15px; color:#111827;">
                    📈 Sales (Last 7 Days)
                </h3>
                <div style="height:300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- ATTENDANCE CHART --}}
            <div
                style="
            flex:1;
            background:#fff;
            padding:25px;
            border-radius:12px;
        ">
                <h3 style="margin-top:0; margin-bottom:15px; color:#111827;">
                    🕒 Attendance Today
                </h3>
                <div style="height:300px;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    {{-- CHART JS --}}
    <script>
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: @json($salesLabels),
                datasets: [{
                    data: @json($salesData),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.2)',
                    tension: 0.4,
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
                    data: [{{ $presentToday }}, {{ $absentToday }}],
                    backgroundColor: ['#16a34a', '#dc2626']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
