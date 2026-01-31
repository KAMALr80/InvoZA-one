{{-- resources/views/dashboard/hr.blade.php --}}
@extends('layouts.app')

@section('page-title', 'HR Dashboard')

@section('content')
<div style="max-width:1400px; margin:0 auto;">
    <!-- Page Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <div>
            <h1 style="margin:0; font-size:28px; color:#1f2937; font-weight:800;">HR Dashboard</h1>
            <p style="margin:8px 0 0 0; color:#6b7280; font-size:15px;">
                {{ now()->format('l, F j, Y') }} • Total Employees: {{ $totalEmployees }}
            </p>
        </div>
        <button onclick="window.location.reload()" style="
            background:#6366f1;
            color:white;
            border:none;
            padding:10px 20px;
            border-radius:10px;
            font-weight:600;
            cursor:pointer;
            display:flex;
            align-items:center;
            gap:8px;
            font-size:14px;
        ">
            🔄 Refresh
        </button>
    </div>

    <!-- Stats Cards -->
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
        gap:20px;
        margin-bottom:30px;
    ">
        <!-- Total Employees -->
        <div style="
            background:white;
            border-radius:16px;
            padding:25px;
            box-shadow:0 4px 20px rgba(0,0,0,0.05);
            border:1px solid #e5e7eb;
            transition:transform 0.3s ease;
        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                <div>
                    <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Total Employees</div>
                    <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $totalEmployees }}</div>
                </div>
                <div style="
                    width:60px;
                    height:60px;
                    border-radius:14px;
                    background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:24px;
                ">
                    👥
                </div>
            </div>
            <div style="font-size:13px; color:#10b981; display:flex; align-items:center; gap:5px;">
                <span>📈</span> <span>Active workforce</span>
            </div>
        </div>

        <!-- Present Today -->
        <div style="
            background:white;
            border-radius:16px;
            padding:25px;
            box-shadow:0 4px 20px rgba(0,0,0,0.05);
            border:1px solid #e5e7eb;
            transition:transform 0.3s ease;
        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                <div>
                    <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Present Today</div>
                    <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $presentToday }}</div>
                </div>
                <div style="
                    width:60px;
                    height:60px;
                    border-radius:14px;
                    background:linear-gradient(135deg, #10b981 0%, #059669 100%);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:24px;
                ">
                    ✅
                </div>
            </div>
            <div style="font-size:13px; color:#10b981; display:flex; align-items:center; gap:5px;">
                <span>🎯</span> <span>{{ $totalEmployees > 0 ? round(($presentToday/$totalEmployees)*100, 1) : 0 }}% attendance</span>
            </div>
        </div>

        <!-- Absent Today -->
        <div style="
            background:white;
            border-radius:16px;
            padding:25px;
            box-shadow:0 4px 20px rgba(0,0,0,0.05);
            border:1px solid #e5e7eb;
            transition:transform 0.3s ease;
        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                <div>
                    <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">Absent Today</div>
                    <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $absentToday }}</div>
                </div>
                <div style="
                    width:60px;
                    height:60px;
                    border-radius:14px;
                    background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:24px;
                ">
                    ⚠️
                </div>
            </div>
            <div style="font-size:13px; color:#ef4444; display:flex; align-items:center; gap:5px;">
                <span>📉</span> <span>Requires attention</span>
            </div>
        </div>

        <!-- On Leave Today -->
        <div style="
            background:white;
            border-radius:16px;
            padding:25px;
            box-shadow:0 4px 20px rgba(0,0,0,0.05);
            border:1px solid #e5e7eb;
            transition:transform 0.3s ease;
        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;">
                <div>
                    <div style="font-size:14px; color:#6b7280; margin-bottom:5px;">On Leave Today</div>
                    <div style="font-size:36px; font-weight:800; color:#1f2937;">{{ $onLeaveToday }}</div>
                </div>
                <div style="
                    width:60px;
                    height:60px;
                    border-radius:14px;
                    background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:24px;
                ">
                    🏖️
                </div>
            </div>
            <div style="font-size:13px; color:#f59e0b; display:flex; align-items:center; gap:5px;">
                <span>📅</span> <span>Approved leaves</span>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:30px;
        margin-bottom:30px;
    ">
        <!-- Left Column: Recent Employees -->
        <div style="
            background:white;
            border-radius:16px;
            padding:25px;
            box-shadow:0 4px 20px rgba(0,0,0,0.05);
            border:1px solid #e5e7eb;
        ">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <h2 style="margin:0; font-size:20px; color:#1f2937; font-weight:700;">
                    👥 Recent Employees
                </h2>
                <a href="{{ route('employees.index') }}" style="
                    color:#6366f1;
                    text-decoration:none;
                    font-size:14px;
                    font-weight:600;
                    padding:6px 12px;
                    border-radius:8px;
                    background:#eef2ff;
                ">View All</a>
            </div>

            @if($recentEmployees->count() > 0)
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Employee</th>
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Department</th>
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Position</th>
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentEmployees as $employee)
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td style="padding:12px 0;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="
                                            width:40px;
                                            height:40px;
                                            border-radius:10px;
                                            background:linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            color:white;
                                            font-weight:bold;
                                            font-size:16px;
                                        ">
                                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600; color:#1f2937;">{{ $employee->name }}</div>
                                            <div style="font-size:12px; color:#6b7280;">{{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:12px 0; color:#374151;">
                                    <span style="
                                        background:#eef2ff;
                                        color:#4f46e5;
                                        padding:4px 10px;
                                        border-radius:20px;
                                        font-size:12px;
                                        font-weight:600;
                                    ">
                                        {{ $employee->department ?? 'Not Assigned' }}
                                    </span>
                                </td>
                                <td style="padding:12px 0; color:#374151;">{{ $employee->position ?? '-' }}</td>
                                <td style="padding:12px 0; color:#6b7280; font-size:13px;">
                                    {{ $employee->created_at->format('d M, Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align:center; padding:40px 20px;">
                    <div style="font-size:48px; margin-bottom:10px;">👤</div>
                    <p style="color:#6b7280; margin-bottom:5px;">No employees found</p>
                    <a href="{{ route('employees.index') }}" style="
                        color:#6366f1;
                        text-decoration:none;
                        font-weight:600;
                        font-size:14px;
                    ">Add your first employee →</a>
                </div>
            @endif
        </div>

        <!-- Right Column: Pending Leave Requests -->
        <div style="
            background:white;
            border-radius:16px;
            padding:25px;
            box-shadow:0 4px 20px rgba(0,0,0,0.05);
            border:1px solid #e5e7eb;
        ">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <h2 style="margin:0; font-size:20px; color:#1f2937; font-weight:700;">
                    📝 Pending Leave Requests ({{ $pendingLeaves }})
                </h2>
                <a href="{{ route('leaves.manage') }}" style="
                    color:#6366f1;
                    text-decoration:none;
                    font-size:14px;
                    font-weight:600;
                    padding:6px 12px;
                    border-radius:8px;
                    background:#eef2ff;
                ">Manage</a>
            </div>

            @if($recentLeaves->count() > 0)
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Employee</th>
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Type</th>
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Date</th>
                                <th style="text-align:left; padding:12px 0; color:#6b7280; font-weight:600; font-size:13px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLeaves as $leave)
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td style="padding:12px 0;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="
                                            width:40px;
                                            height:40px;
                                            border-radius:10px;
                                            background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            color:white;
                                            font-weight:bold;
                                            font-size:16px;
                                        ">
                                            {{ strtoupper(substr($leave->employee->name ?? 'E', 0, 1)) }}
                                        </div>
                                        <div style="font-weight:600; color:#1f2937;">
                                            {{ $leave->employee->name ?? 'Unknown Employee' }}
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:12px 0; color:#374151;">
                                    <span style="
                                        background:#fef3c7;
                                        color:#92400e;
                                        padding:4px 10px;
                                        border-radius:20px;
                                        font-size:12px;
                                        font-weight:600;
                                    ">
                                        {{ $leave->type ?? 'Leave' }}
                                    </span>
                                </td>
                                <td style="padding:12px 0; color:#6b7280; font-size:13px;">
                                    @if(isset($leave->from_date) && isset($leave->to_date))
                                        {{ \Carbon\Carbon::parse($leave->from_date)->format('d M') }} -
                                        {{ \Carbon\Carbon::parse($leave->to_date)->format('d M') }}
                                    @else
                                        {{ $leave->created_at->format('d M, Y') }}
                                    @endif
                                </td>
                                <td style="padding:12px 0;">
                                    <span style="
                                        background:#fef3c7;
                                        color:#92400e;
                                        padding:6px 12px;
                                        border-radius:20px;
                                        font-size:12px;
                                        font-weight:600;
                                        display:inline-flex;
                                        align-items:center;
                                        gap:5px;
                                    ">
                                        ⏳ Pending
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($pendingLeaves > 5)
                <div style="text-align:center; padding-top:15px; border-top:1px solid #f3f4f6;">
                    <a href="{{ route('leaves.manage') }}" style="
                        color:#6366f1;
                        text-decoration:none;
                        font-weight:600;
                        font-size:14px;
                    ">View all {{ $pendingLeaves }} pending requests →</a>
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

    <!-- Third Row: Department Stats -->
    <div style="
        background:white;
        border-radius:16px;
        padding:25px;
        box-shadow:0 4px 20px rgba(0,0,0,0.05);
        border:1px solid #e5e7eb;
        margin-bottom:30px;
    ">
        <h2 style="margin:0 0 20px 0; font-size:20px; color:#1f2937; font-weight:700;">
            📊 Department Statistics
        </h2>

        @if($departmentStats->count() > 0)
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                @foreach($departmentStats as $dept)
                <div style="
                    background:{{ $dept['color'] }}10;
                    border:1px solid {{ $dept['color'] }}30;
                    border-radius:12px;
                    padding:20px;
                    display:flex;
                    align-items:center;
                    gap:15px;
                ">
                    <div style="
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
                        {{ $dept['icon'] }}
                    </div>
                    <div>
                        <div style="font-size:28px; font-weight:800; color:#1f2937;">{{ $dept['count'] }}</div>
                        <div style="font-size:14px; color:#6b7280; font-weight:600;">{{ $dept['name'] }}</div>
                        <div style="font-size:12px; color:#9ca3af;">
                            {{ $totalEmployees > 0 ? round(($dept['count']/$totalEmployees)*100, 1) : 0 }}% of total
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

    <!-- Fourth Row: Upcoming Holidays -->
    <div style="
        background:white;
        border-radius:16px;
        padding:25px;
        box-shadow:0 4px 20px rgba(0,0,0,0.05);
        border:1px solid #e5e7eb;
    ">
        <h2 style="margin:0 0 20px 0; font-size:20px; color:#1f2937; font-weight:700;">
            🎉 Upcoming Holidays
        </h2>

        @if(count($upcomingHolidays) > 0)
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:15px;">
                @foreach($upcomingHolidays as $holiday)
                <div style="
                    background:#f0f9ff;
                    border:1px solid #e0f2fe;
                    border-radius:12px;
                    padding:20px;
                    display:flex;
                    align-items:center;
                    gap:15px;
                ">
                    <div style="
                        width:50px;
                        height:50px;
                        border-radius:12px;
                        background:#0ea5e9;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:24px;
                        color:white;
                    ">
                        🎊
                    </div>
                    <div>
                        <div style="font-size:16px; font-weight:700; color:#1f2937; margin-bottom:5px;">
                            {{ $holiday['name'] }}
                        </div>
                        <div style="font-size:13px; color:#6b7280; margin-bottom:5px;">
                            {{ $holiday['date']->format('l, F j, Y') }}
                        </div>
                        <div style="font-size:12px; color:#0ea5e9; font-weight:600;">
                            In {{ $holiday['days_until'] }} day{{ $holiday['days_until'] != 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:40px 20px;">
                <div style="font-size:48px; margin-bottom:10px;">📅</div>
                <p style="color:#6b7280; margin-bottom:5px;">No upcoming holidays scheduled</p>
                <p style="color:#9ca3af; font-size:13px;">Add holidays in settings</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions Footer -->
<div style="
    position:fixed;
    bottom:20px;
    right:20px;
    display:flex;
    gap:10px;
    z-index:100;
">
    <button onclick="window.location.href='{{ route('employees.index') }}'" style="
        background:white;
        color:#6366f1;
        border:1px solid #e5e7eb;
        padding:12px 20px;
        border-radius:12px;
        font-weight:600;
        cursor:pointer;
        display:flex;
        align-items:center;
        gap:8px;
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
        transition:all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.15)';"
    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'">
        👥 Add Employee
    </button>
    <button onclick="window.location.href='{{ route('leaves.manage') }}'" style="
        background:#6366f1;
        color:white;
        border:none;
        padding:12px 20px;
        border-radius:12px;
        font-weight:600;
        cursor:pointer;
        display:flex;
        align-items:center;
        gap:8px;
        box-shadow:0 4px 12px rgba(99,102,241,0.3);
        transition:all 0.3s ease;
    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(99,102,241,0.4)';"
    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(99,102,241,0.3)'">
        📝 Manage Leaves
    </button>
</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr;
        }

        div[style*="grid-template-columns:repeat(auto-fit, minmax(250px, 1fr))"] {
            grid-template-columns: 1fr;
        }

        .quick-actions-footer {
            flex-direction: column;
            align-items: flex-end;
        }
    }
</style>
@endsection
