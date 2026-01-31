@extends('layouts.app')

@section('page-title', 'HR Dashboard')

@section('content')
    <div style="max-width:1400px; margin:0 auto;">
        <!-- Page Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
            <div>
                <h1 style="margin:0; font-size:28px; color:#1f2937; font-weight:800;">HR Dashboard</h1>
                <p style="margin:8px 0 0 0; color:#6b7280; font-size:15px;">
                    {{ now()->format('l, F j, Y') }} • Welcome, {{ auth()->user()->name }}
                </p>
            </div>
            <div style="display:flex; gap:10px;">
                <button onclick="window.location.href='{{ route('employees.index') }}'"
                    style="background:#6366f1; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; font-size:14px;">
                    👥 Manage Employees
                </button>
                <button onclick="window.location.href='{{ route('attendance.mark') }}'"
                    style="background:#10b981; color:white; border:none; padding:10px 20px; border-radius:10px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; font-size:14px;">
                    📝 Mark Attendance
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:30px;">
            <!-- Total Employees -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Total Employees</div>
                        <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $totalEmployees ?? 0 }}</div>
                    </div>
                    <div
                        style="width:60px; height:60px; border-radius:14px; background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display:flex; align-items:center; justify-content:center; font-size:24px;">
                        👥
                    </div>
                </div>
                <a href="{{ route('employees.index') }}"
                    style="color:#3b82f6; text-decoration:none; font-size:13px; font-weight:600; display:flex; align-items:center; gap:5px;">
                    <span>→</span> <span>View all employees</span>
                </a>
            </div>

            <!-- Present Today -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Present Today</div>
                        <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $presentToday ?? 0 }}</div>
                    </div>
                    <div
                        style="width:60px; height:60px; border-radius:14px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); display:flex; align-items:center; justify-content:center; font-size:24px;">
                        ✅
                    </div>
                </div>
                <div style="font-size:13px; color:#10b981; font-weight:600;">
                    {{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0 }}% attendance
                </div>
            </div>

            <!-- Absent Today -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Absent Today</div>
                        <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $absentToday ?? 0 }}</div>
                    </div>
                    <div
                        style="width:60px; height:60px; border-radius:14px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); display:flex; align-items:center; justify-content:center; font-size:24px;">
                        ⚠️
                    </div>
                </div>
                @if ($employeesWithoutAttendance->count() > 0)
                    <div style="font-size:13px; color:#ef4444; font-weight:600;">
                        {{ $employeesWithoutAttendance->count() }} pending
                    </div>
                @endif
            </div>

            <!-- Pending Leaves -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Pending Leaves</div>
                        <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $pendingLeaves ?? 0 }}</div>
                    </div>
                    <div
                        style="width:60px; height:60px; border-radius:14px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display:flex; align-items:center; justify-content:center; font-size:24px;">
                        📝
                    </div>
                </div>
                <a href="{{ route('leaves.manage') }}"
                    style="color:#f59e0b; text-decoration:none; font-size:13px; font-weight:600; display:flex; align-items:center; gap:5px;">
                    <span>→</span> <span>Manage leaves</span>
                </a>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:30px; margin-bottom:30px;">
            <!-- Left: Attendance Summary -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                    <h2 style="margin:0; font-size:20px; color:#1f2937; font-weight:700;">
                        🕒 Today's Attendance ({{ $todayAttendance->count() }})
                    </h2>
                    <a href="{{ route('attendance.mark') }}"
                        style="color:#6366f1; text-decoration:none; font-size:14px; font-weight:600; padding:6px 12px; border-radius:8px; background:#eef2ff;">
                        Mark Attendance
                    </a>
                </div>

                @if ($todayAttendance->count() > 0)
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:1px solid #e5e7eb;">
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Employee</th>
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Status</th>
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Time</th>
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todayAttendance as $attendance)
                                    <tr style="border-bottom:1px solid #f3f4f6;">
                                        <td style="padding:12px 0;">
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <div
                                                    style="width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:16px;">
                                                    {{ strtoupper(substr($attendance->employee->name ?? 'E', 0, 1)) }}
                                                </div>
                                                <div style="font-weight:600; color:#1f2937;">
                                                    {{ $attendance->employee->name ?? 'Unknown' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:12px 0;">
                                            @if (in_array($attendance->status, ['present', 'Present']))
                                                <span
                                                    style="background:#d1fae5; color:#065f46; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                                    ✅ Present
                                                </span>
                                            @elseif(in_array($attendance->status, ['absent', 'Absent']))
                                                <span
                                                    style="background:#fee2e2; color:#991b1b; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                                    ❌ Absent
                                                </span>
                                            @elseif(in_array($attendance->status, ['late', 'Late']))
                                                <span
                                                    style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                                    ⏰ Late
                                                </span>
                                            @else
                                                <span
                                                    style="background:#e5e7eb; color:#374151; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                                    {{ $attendance->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding:12px 0; color:#6b7280; font-size:13px;">
                                            {{ $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '--:--' }}
                                        </td>
                                        <td style="padding:12px 0;">
                                            <button onclick="editAttendance({{ $attendance->id }})"
                                                style="background:#e0f2fe; color:#0369a1; border:none; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($employeesWithoutAttendance->count() > 0)
                        <div style="margin-top:15px; padding-top:15px; border-top:1px solid #f3f4f6;">
                            <div style="font-size:13px; color:#6b7280; margin-bottom:10px;">
                                ⚠️ {{ $employeesWithoutAttendance->count() }} employees without attendance
                            </div>
                            <a href="{{ route('attendance.mark') }}"
                                style="color:#ef4444; text-decoration:none; font-size:13px; font-weight:600;">
                                Mark attendance for missing employees →
                            </a>
                        </div>
                    @endif
                @else
                    <div style="text-align:center; padding:40px 20px;">
                        <div style="font-size:48px; margin-bottom:10px;">🕒</div>
                        <p style="color:#6b7280; margin-bottom:5px;">No attendance marked today</p>
                        <a href="{{ route('attendance.mark') }}"
                            style="color:#6366f1; text-decoration:none; font-weight:600; font-size:14px;">
                            Mark today's attendance →
                        </a>
                    </div>
                @endif
            </div>

            <!-- Right: Pending Leaves -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                    <h2 style="margin:0; font-size:20px; color:#1f2937; font-weight:700;">
                        📝 Pending Leave Requests ({{ $pendingLeaves }})
                    </h2>
                    <a href="{{ route('leaves.manage') }}"
                        style="color:#6366f1; text-decoration:none; font-size:14px; font-weight:600; padding:6px 12px; border-radius:8px; background:#eef2ff;">
                        Manage All
                    </a>
                </div>

                @if ($recentLeaves->count() > 0)
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:1px solid #e5e7eb;">
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Employee</th>
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Type</th>
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Date</th>
                                    <th
                                        style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentLeaves as $leave)
                                    <tr style="border-bottom:1px solid #f3f4f6;">
                                        <td style="padding:12px 0;">
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <div
                                                    style="width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:16px;">
                                                    {{ strtoupper(substr($leave->employee->name ?? 'E', 0, 1)) }}
                                                </div>
                                                <div style="font-weight:600; color:#1f2937;">
                                                    {{ $leave->employee->name ?? 'Unknown' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:12px 0; color:#374151;">
                                            <span
                                                style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                                                {{ $leave->type ?? 'Leave' }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 0; color:#6b7280; font-size:13px;">
                                            @if (isset($leave->from_date) && isset($leave->to_date))
                                                {{ Carbon\Carbon::parse($leave->from_date)->format('d M') }} -
                                                {{ Carbon\Carbon::parse($leave->to_date)->format('d M') }}
                                            @else
                                                {{ $leave->created_at->format('d M, Y') }}
                                            @endif
                                        </td>
                                        <td style="padding:12px 0;">
                                            <div style="display:flex; gap:5px;">
                                                <button onclick="approveLeave({{ $leave->id }})"
                                                    style="background:#d1fae5; color:#065f46; border:none; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                                                    Approve
                                                </button>
                                                <button onclick="rejectLeave({{ $leave->id }})"
                                                    style="background:#fee2e2; color:#991b1b; border:none; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($pendingLeaves > 5)
                        <div style="text-align:center; padding-top:15px; border-top:1px solid #f3f4f6;">
                            <a href="{{ route('leaves.manage') }}"
                                style="color:#6366f1; text-decoration:none; font-weight:600; font-size:14px;">
                                View all {{ $pendingLeaves }} pending requests →
                            </a>
                        </div>
                    @endif
                @else
                    <div style="text-align:center; padding:40px 20px;">
                        <div style="font-size:48px; margin-bottom:10px;">✅</div>
                        <p style="color:#6b7280; margin-bottom:5px;">No pending leave requests</p>
                        <p style="color:#9ca3af; font-size:13px;">All leave requests have been processed</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bottom Row: Charts and Quick Actions -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:30px; margin-bottom:30px;">
            <!-- Attendance Chart -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <h2 style="margin:0 0 20px 0; font-size:20px; color:#1f2937; font-weight:700;">
                    📈 Weekly Attendance Trend
                </h2>
                <canvas id="attendanceChart" style="width:100%; height:250px;"></canvas>
            </div>

            <!-- Quick Actions -->
            <div
                style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
                <h2 style="margin:0 0 20px 0; font-size:20px; color:#1f2937; font-weight:700;">🚀 Quick Actions</h2>
                <div style="display:grid; grid-template-columns:1fr; gap:15px;">
                    <a href="{{ route('employees.create') }}"
                        style="background:#eef2ff; border:1px solid #c7d2fe; border-radius:12px; padding:20px; text-decoration:none; display:flex; align-items:center; gap:15px;">
                        <div
                            style="width:50px; height:50px; border-radius:12px; background:#4f46e5; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                            ➕
                        </div>
                        <div>
                            <div style="font-weight:700; color:#1f2937;">Add New Employee</div>
                            <div style="font-size:13px; color:#6b7280;">Register new employee in system</div>
                        </div>
                    </a>

                    <a href="{{ route('attendance.mark') }}"
                        style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:20px; text-decoration:none; display:flex; align-items:center; gap:15px;">
                        <div
                            style="width:50px; height:50px; border-radius:12px; background:#0ea5e9; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                            🕒
                        </div>
                        <div>
                            <div style="font-weight:700; color:#1f2937;">Mark Bulk Attendance</div>
                            <div style="font-size:13px; color:#6b7280;">Mark attendance for multiple employees</div>
                        </div>
                    </a>

                    <a href="{{ route('hr.dashboard') }}"
                        style="background:#f3e8ff; border:1px solid #e9d5ff; border-radius:12px; padding:20px; text-decoration:none; display:flex; align-items:center; gap:15px;">
                        <div
                            style="width:50px; height:50px; border-radius:12px; background:#a855f7; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                            📊
                        </div>
                        <div>
                            <div style="font-weight:700; color:#1f2937;">HR Analytics</div>
                            <div style="font-size:13px; color:#6b7280;">Detailed reports and analytics</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Department Stats -->
        <div
            style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb; margin-bottom:30px;">
            <h2 style="margin:0 0 20px 0; font-size:20px; color:#1f2937; font-weight:700;">🏢 Department Statistics</h2>
            @if (count($departmentStats) > 0)
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                    @foreach ($departmentStats as $dept)
                        <div
                            style="
                background:{{ $dept['color'] }}10;
                border:1px solid {{ $dept['color'] }}30;
                border-radius:12px;
                padding:20px;
                display:flex;
                align-items:center;
                gap:15px;
            ">
                            <div
                                style="
                    width:50px;
                    height:50px;
                    border-radius:12px;
                    background:{{ $dept['color'] }};
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:24px;
                    color:white;
                ">
                                {{ $dept['icon'] ?? '🏢' }}
                            </div>
                            <div>
                                <!-- FIX: Use array syntax instead of object -->
                                <div style="font-size:24px; font-weight:800; color:#1f2937;">{{ $dept['count'] }}</div>
                                <div style="font-size:14px; color:#6b7280; font-weight:600;">{{ $dept['name'] }}</div>
                                <div style="font-size:12px; color:#9ca3af;">
                                    {{ $totalEmployees > 0 ? round(($dept['count'] / $totalEmployees) * 100, 1) : 0 }}% of
                                    total
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding:40px 20px;">
                    <div style="font-size:48px; margin-bottom:10px;">🏢</div>
                    <p style="color:#6b7280; margin-bottom:5px;">No department data available</p>
                    <p style="color:#9ca3af; font-size:13px;">Add departments to employee records</p>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Charts and Actions -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($attendanceTrend['labels'] ?? []),
                datasets: [{
                    label: 'Present',
                    data: @json($attendanceTrend['present'] ?? []),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Absent',
                    data: @json($attendanceTrend['absent'] ?? []),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Leave Actions
        function approveLeave(leaveId) {
            if (confirm('Approve this leave request?')) {
                fetch(`/leaves/${leaveId}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Leave approved successfully');
                            location.reload();
                        }
                    });
            }
        }

        function rejectLeave(leaveId) {
            if (confirm('Reject this leave request?')) {
                fetch(`/leaves/${leaveId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Leave rejected successfully');
                            location.reload();
                        }
                    });
            }
        }

        function editAttendance(attendanceId) {
            window.location.href = `/attendance/${attendanceId}/edit`;
        }
    </script>

    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns:repeat(auto-fit, minmax(250px, 1fr))"] {
                grid-template-columns: 1fr;
            }

            div[style*="grid-template-columns:1fr 1fr"] {
                grid-template-columns: 1fr;
            }

            div[style*="grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))"] {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
