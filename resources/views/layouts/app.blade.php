<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>INVOZA One</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        /* DataTable Custom Styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            margin-left: 10px !important;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
        }

        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
        }

        .dt-button {
            background: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 8px 16px !important;
            margin-right: 5px !important;
            cursor: pointer !important;
        }

        .dt-button:hover {
            background: #4338ca !important;
        }
    </style>
</head>

<body
    style="margin:0; font-family:'Segoe UI', Tahoma, sans-serif; background:#f3f4f6; overflow-x:hidden; display:flex; min-height:100vh;">

    {{-- ================= SIDEBAR ================= --}}
    <div id="sidebar"
        style="
        width:220px;
        background:linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        color:#fff;
        padding:25px 20px;
        display:flex;
        flex-direction:column;
        position:fixed;
        left:0;
        top:0;
        bottom:0;
        height:100vh;
        border-right:1px solid rgba(255,255,255,0.1);
        box-shadow:4px 0 20px rgba(0,0,0,0.2);
        z-index:1000;
        overflow-y:auto;
        scrollbar-width:thin;
        scrollbar-color:#4b5563 #1f2937;
    ">
        {{-- Custom scrollbar for Webkit browsers --}}
        <style>
            #sidebar::-webkit-scrollbar {
                width: 6px;
            }

            #sidebar::-webkit-scrollbar-track {
                background: #1f2937;
            }

            #sidebar::-webkit-scrollbar-thumb {
                background: #4b5563;
                border-radius: 3px;
            }

            #sidebar::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }
        </style>

        {{-- Logo --}}
        <div
            style="
            text-align:center;
            margin-bottom:25px;
            padding-bottom:20px;
            border-bottom:1px solid rgba(255,255,255,0.1);
            position:relative;
            top:0;
            background:linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index:1;
        ">
            <div
                style="
                background:linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                width:50px;
                height:50px;
                border-radius:14px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:24px;
                margin:0 auto 15px auto;
                box-shadow:0 8px 20px rgba(99,102,241,0.3);
            ">
                ⚡
            </div>
            <h2
                style="
                margin:0;
                font-size:22px;
                font-weight:900;
                letter-spacing:0.5px;
                background:linear-gradient(135deg, #fff 0%, #cbd5e1 100%);
                -webkit-background-clip:text;
                -webkit-text-fill-color:transparent;
            ">
                INVOZA One
            </h2>
            <div style="margin-top:8px; font-size:12px; color:#94a3b8; font-weight:500;">
                Business Intelligence System
            </div>
        </div>

        {{-- User Info --}}
        @auth
            <div
                style="
                background:rgba(255,255,255,0.05);
                border-radius:12px;
                padding:15px;
                margin-bottom:20px;
                border:1px solid rgba(255,255,255,0.08);
                backdrop-filter:blur(10px);
            ">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="
                        background:linear-gradient(135deg, #10b981 0%, #059669 100%);
                        width:40px;
                        height:40px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        font-weight:bold;
                    ">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:15px; margin-bottom:2px;">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="font-size:12px; color:#cbd5e1;">
                            {{ auth()->user()->role }}
                        </div>
                    </div>
                </div>
            </div>
        @endauth

        {{-- Navigation Menu --}}
        <div style="flex:1; display:flex; flex-direction:column; gap:8px; margin-bottom:20px;">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
                style="
                    color:#e2e8f0;
                    text-decoration:none;
                    padding:14px 16px;
                    margin-bottom:4px;
                    border-radius:12px;
                    text-align:left;
                    font-size:15px;
                    transition:all 0.3s ease;
                    display:flex;
                    align-items:center;
                    gap:12px;
                    border:1px solid {{ request()->is('dashboard') ? 'rgba(99,102,241,0.3)' : 'transparent' }};
                    background:{{ request()->is('dashboard') ? 'rgba(99,102,241,0.15)' : 'transparent' }};
                    font-weight:{{ request()->is('dashboard') ? '700' : '500' }};
               "
                onmouseover="this.style.backgroundColor='rgba(99,102,241,0.1)'; this.style.transform='translateX(4px)';"
                onmouseout="this.style.backgroundColor='{{ request()->is('dashboard') ? 'rgba(99,102,241,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                <span
                    style="
                    width:36px;
                    height:36px;
                    border-radius:10px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:18px;
                    background:{{ request()->is('dashboard') ? '#6366f1' : '#475569' }};
                ">
                    📊
                </span>
                Dashboard
            </a>

            {{-- ADMIN MENU --}}
            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('employees.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('employees*') ? 'rgba(59,130,246,0.3)' : 'transparent' }};
                        background:{{ request()->is('employees*') ? 'rgba(59,130,246,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('employees*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('employees*') ? 'rgba(59,130,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('employees*') ? '#3b82f6' : '#475569' }};
                    ">
                        👥
                    </span>
                    Employees
                </a>

                <a href="{{ route('inventory.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('inventory*') ? 'rgba(249,115,22,0.3)' : 'transparent' }};
                        background:{{ request()->is('inventory*') ? 'rgba(249,115,22,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('inventory*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(249,115,22,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('inventory*') ? 'rgba(249,115,22,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('inventory*') ? '#f97316' : '#475569' }};
                    ">
                        📦
                    </span>
                    Inventory
                </a>

                <a href="{{ route('customers.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('customers*') ? 'rgba(16,185,129,0.3)' : 'transparent' }};
                        background:{{ request()->is('customers*') ? 'rgba(16,185,129,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('customers*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(16,185,129,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('customers*') ? 'rgba(16,185,129,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('customers*') ? '#10b981' : '#475569' }};
                    ">
                        👤
                    </span>
                    Customers
                </a>

                <a href="{{ route('sales.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('sales*') ? 'rgba(234,88,12,0.3)' : 'transparent' }};
                        background:{{ request()->is('sales*') ? 'rgba(234,88,12,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('sales*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(234,88,12,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('sales*') ? 'rgba(234,88,12,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('sales*') ? '#ea580c' : '#475569' }};
                    ">
                        💰
                    </span>
                    Sales
                </a>

                <a href="{{ route('purchases.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('purchases*') ? 'rgba(168,85,247,0.3)' : 'transparent' }};
                        background:{{ request()->is('purchases*') ? 'rgba(168,85,247,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('purchases*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(168,85,247,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('purchases*') ? 'rgba(168,85,247,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('purchases*') ? '#a855f7' : '#475569' }};
                    ">
                        🛒
                    </span>
                    Purchases
                </a>

                <a href="{{ route('attendance.manage') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('attendance/manage') ? 'rgba(59,130,246,0.3)' : 'transparent' }};
                        background:{{ request()->is('attendance/manage') ? 'rgba(59,130,246,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('attendance/manage') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('attendance/manage') ? 'rgba(59,130,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('attendance/manage') ? '#3b82f6' : '#475569' }};
                    ">
                        🕒
                    </span>
                    Attendance
                </a>

                <a href="{{ route('leaves.manage') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('leaves/manage') ? 'rgba(16,185,129,0.3)' : 'transparent' }};
                        background:{{ request()->is('leaves/manage') ? 'rgba(16,185,129,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('leaves/manage') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(16,185,129,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('leaves/manage') ? 'rgba(16,185,129,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('leaves/manage') ? '#10b981' : '#475569' }};
                    ">
                        ✅
                    </span>
                    Manage Leaves
                </a>

                <a href="{{ route('admin.staff.approval') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('admin/staff-approval') ? 'rgba(239,68,68,0.3)' : 'transparent' }};
                        background:{{ request()->is('admin/staff-approval') ? 'rgba(239,68,68,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('admin/staff-approval') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(239,68,68,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('admin/staff-approval') ? 'rgba(239,68,68,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('admin/staff-approval') ? '#ef4444' : '#475569' }};
                    ">
                        🧑‍⚖️
                    </span>
                    Staff Approval
                </a>

                <!-- HR Dashboard Menu Item -->
                <a href="{{ route('hr.dashboard') }}"
                    style="
        color:#e2e8f0;
        text-decoration:none;
        padding:14px 16px;
        margin-bottom:4px;
        border-radius:12px;
        text-align:left;
        font-size:15px;
        transition:all 0.3s ease;
        display:flex;
        align-items:center;
        gap:12px;
        border:1px solid {{ request()->is('hr/dashboard') ? 'rgba(139,92,246,0.3)' : 'transparent' }};
        background:{{ request()->is('hr/dashboard') ? 'rgba(139,92,246,0.15)' : 'transparent' }};
        font-weight:{{ request()->is('hr/dashboard') ? '700' : '500' }};
   "
                    onmouseover="this.style.backgroundColor='rgba(139,92,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('hr/dashboard') ? 'rgba(139,92,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
        width:36px;
        height:36px;
        border-radius:10px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
        background:{{ request()->is('hr/dashboard') ? '#8b5cf6' : '#475569' }};
    ">
                        👨‍💼
                    </span>
                    HR Dashboard
                </a>
            @endif
            {{-- HR MENU --}}
            @if (auth()->check() && auth()->user()->role === 'hr')
                <!-- HR Dashboard -->
                <a href="{{ route('hr.dashboard') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('hr/dashboard') ? 'rgba(139,92,246,0.3)' : 'transparent' }};
            background:{{ request()->is('hr/dashboard') ? 'rgba(139,92,246,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('hr/dashboard') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(139,92,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('hr/dashboard') ? 'rgba(139,92,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('hr/dashboard') ? '#8b5cf6' : '#475569' }};
            ">
                        👨‍💼
                    </span>
                    HR Dashboard
                </a>

                <!-- Employees -->
                <a href="{{ route('employees.index') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('employees*') ? 'rgba(59,130,246,0.3)' : 'transparent' }};
            background:{{ request()->is('employees*') ? 'rgba(59,130,246,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('employees*') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('employees*') ? 'rgba(59,130,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('employees*') ? '#3b82f6' : '#475569' }};
            ">
                        👥
                    </span>
                    Employees
                </a>

                <!-- Attendance -->
                <a href="{{ route('attendance.manage') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('attendance*') ? 'rgba(59,130,246,0.3)' : 'transparent' }};
            background:{{ request()->is('attendance*') ? 'rgba(59,130,246,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('attendance*') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('attendance*') ? 'rgba(59,130,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('attendance*') ? '#3b82f6' : '#475569' }};
            ">
                        🕒
                    </span>
                    Attendance
                </a>

                <!-- Mark Attendance (Quick Action) -->
                <a href="{{ route('attendance.mark') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('attendance/mark') ? 'rgba(16,185,129,0.3)' : 'transparent' }};
            background:{{ request()->is('attendance/mark') ? 'rgba(16,185,129,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('attendance/mark') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(16,185,129,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('attendance/mark') ? 'rgba(16,185,129,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('attendance/mark') ? '#10b981' : '#475569' }};
            ">
                        📝
                    </span>
                    Mark Attendance
                </a>

                <!-- Manage Leaves -->
                <a href="{{ route('leaves.manage') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('leaves*') ? 'rgba(16,185,129,0.3)' : 'transparent' }};
            background:{{ request()->is('leaves*') ? 'rgba(16,185,129,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('leaves*') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(16,185,129,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('leaves*') ? 'rgba(16,185,129,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('leaves*') ? '#10b981' : '#475569' }};
            ">
                        ✅
                    </span>
                    Manage Leaves
                </a>

                <!-- Add Employee -->
                <a href="{{ route('employees.create') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('employees/create') ? 'rgba(59,130,246,0.3)' : 'transparent' }};
            background:{{ request()->is('employees/create') ? 'rgba(59,130,246,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('employees/create') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('employees/create') ? 'rgba(59,130,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('employees/create') ? '#3b82f6' : '#475569' }};
            ">
                        ➕
                    </span>
                    Add Employee
                </a>

                <!-- HR Reports -->
                <a href="{{ route('reports.attendance') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('reports*') ? 'rgba(168,85,247,0.3)' : 'transparent' }};
            background:{{ request()->is('reports*') ? 'rgba(168,85,247,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('reports*') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(168,85,247,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('reports*') ? 'rgba(168,85,247,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('reports*') ? '#a855f7' : '#475569' }};
            ">
                        📊
                    </span>
                    HR Reports
                </a>

                <!-- HR Analytics -->
                <a href="{{ route('hr.analytics') }}"
                    style="
            color:#e2e8f0;
            text-decoration:none;
            padding:14px 16px;
            margin-bottom:4px;
            border-radius:12px;
            text-align:left;
            font-size:15px;
            transition:all 0.3s ease;
            display:flex;
            align-items:center;
            gap:12px;
            border:1px solid {{ request()->is('hr/analytics') ? 'rgba(139,92,246,0.3)' : 'transparent' }};
            background:{{ request()->is('hr/analytics') ? 'rgba(139,92,246,0.15)' : 'transparent' }};
            font-weight:{{ request()->is('hr/analytics') ? '700' : '500' }};
        "
                    onmouseover="this.style.backgroundColor='rgba(139,92,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('hr/analytics') ? 'rgba(139,92,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                width:36px;
                height:36px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:18px;
                background:{{ request()->is('hr/analytics') ? '#8b5cf6' : '#475569' }};
            ">
                        📈
                    </span>
                    HR Analytics
                </a>
            @endif
            {{-- STAFF MENU --}}
            @if (auth()->check() && auth()->user()->role === 'staff')
                <a href="{{ route('attendance.my') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('attendance/my') ? 'rgba(59,130,246,0.3)' : 'transparent' }};
                        background:{{ request()->is('attendance/my') ? 'rgba(59,130,246,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('attendance/my') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(59,130,246,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('attendance/my') ? 'rgba(59,130,246,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('attendance/my') ? '#3b82f6' : '#475569' }};
                    ">
                        🕒
                    </span>
                    My Attendance
                </a>

                <a href="{{ route('leaves.my') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('leaves/my') ? 'rgba(16,185,129,0.3)' : 'transparent' }};
                        background:{{ request()->is('leaves/my') ? 'rgba(16,185,129,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('leaves/my') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(16,185,129,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('leaves/my') ? 'rgba(16,185,129,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('leaves/my') ? '#10b981' : '#475569' }};
                    ">
                        📝
                    </span>
                    My Leaves
                </a>

                <a href="{{ route('sales.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('sales*') ? 'rgba(234,88,12,0.3)' : 'transparent' }};
                        background:{{ request()->is('sales*') ? 'rgba(234,88,12,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('sales*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(234,88,12,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('sales*') ? 'rgba(234,88,12,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('sales*') ? '#ea580c' : '#475569' }};
                    ">
                        💰
                    </span>
                    Sales
                </a>

                <a href="{{ route('customers.index') }}"
                    style="
                        color:#e2e8f0;
                        text-decoration:none;
                        padding:14px 16px;
                        margin-bottom:4px;
                        border-radius:12px;
                        text-align:left;
                        font-size:15px;
                        transition:all 0.3s ease;
                        display:flex;
                        align-items:center;
                        gap:12px;
                        border:1px solid {{ request()->is('customers*') ? 'rgba(16,185,129,0.3)' : 'transparent' }};
                        background:{{ request()->is('customers*') ? 'rgba(16,185,129,0.15)' : 'transparent' }};
                        font-weight:{{ request()->is('customers*') ? '700' : '500' }};
                   "
                    onmouseover="this.style.backgroundColor='rgba(16,185,129,0.1)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('customers*') ? 'rgba(16,185,129,0.15)' : 'transparent' }}'; this.style.transform='translateX(0)';">
                    <span
                        style="
                        width:36px;
                        height:36px;
                        border-radius:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:18px;
                        background:{{ request()->is('customers*') ? '#10b981' : '#475569' }};
                    ">
                        👤
                    </span>
                    Customers
                </a>
            @endif
        </div>

        {{-- Footer --}}
        <div
            style="margin-top:auto; padding-top:20px; text-align:center; font-size:11px; color:#94a3b8; opacity:0.7; border-top:1px solid rgba(255,255,255,0.1);">
            INVOZA One v1.0 • {{ date('Y') }}
        </div>
    </div>

    {{-- ================= TOP NAVBAR ================= --}}
    <div
        style="
        position:fixed;
        top:0;
        left:260px;
        right:0;
        height:70px;
        background:white;
        box-shadow:0 2px 15px rgba(0,0,0,0.08);
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:0 30px;
        z-index:999;
    ">
        <button id="menuToggle"
            style="
            display:none;
            align-items:center;
            justify-content:center;
            width:40px;
            height:40px;
            background:#6366f1;
            color:white;
            border-radius:10px;
            border:none;
            cursor:pointer;
            font-size:20px;
        ">
            ☰
        </button>

        <div style="font-size:22px; font-weight:700; color:#1f2937;">
            @yield('page-title', 'Dashboard')
        </div>

        @auth
            <div style="display:flex; align-items:center; gap:15px;">
                <div style="text-align:right;">
                    <div style="font-weight:600; color:#1f2937; font-size:15px;">{{ auth()->user()->name }}</div>
                    <div style="font-size:13px; color:#6b7280; background:#f3f4f6; padding:3px 10px; border-radius:20px;">
                        {{ ucfirst(auth()->user()->role) }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit"
                        style="
                        background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                        color:white;
                        border:none;
                        padding:10px 20px;
                        border-radius:10px;
                        font-weight:600;
                        cursor:pointer;
                        display:flex;
                        align-items:center;
                        gap:8px;
                        transition:all 0.3s ease;
                        box-shadow:0 4px 12px rgba(220,38,38,0.2);
                    "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220,38,38,0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(220,38,38,0.2)'">
                        <span style="font-size:18px;">🚪</span>
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div
        style="
        flex:1;
        margin-left:260px;
        margin-top:70px;
        min-height:calc(100vh - 70px);
        padding:30px;
        background:#f8fafc;
        overflow-y:auto;
    ">
        @yield('content')
    </div>

    {{-- ================= RESPONSIVE STYLES ================= --}}
    <style>
        @media (max-width: 768px) {
            #sidebar {
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            #sidebar.active {
                transform: translateX(0);
            }

            div[style*="position:fixed; top:0; left:260px"] {
                left: 0;
                padding: 0 20px;
            }

            div[style*="margin-left:260px; margin-top:70px"] {
                margin-left: 0;
                padding: 20px;
            }

            #menuToggle {
                display: flex;
            }
        }
    </style>

    {{-- ================= TOAST NOTIFICATION ================= --}}
    @if (session('success'))
        <div
            style="
            position:fixed;
            top:90px;
            right:30px;
            background:#16a34a;
            color:#fff;
            padding:14px 20px;
            border-radius:10px;
            font-weight:600;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
            z-index:9999;
            animation:slideIn 0.3s ease;
        ">
            ✅ {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('div[style*="position:fixed; top:90px; right:30px"]')?.remove();
            }, 3000);
        </script>
        <style>
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
        </style>
    @endif

    {{-- ================= JAVASCRIPT LIBRARIES ================= --}}
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Chart.js (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- ================= CUSTOM JAVASCRIPT ================= --}}
    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');

            if (window.innerWidth <= 768 &&
                sidebar && menuToggle &&
                !sidebar.contains(event.target) &&
                !menuToggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });

        // Auto-close sidebar when clicking a link on mobile
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar')?.classList.remove('active');
                }
            });
        });

        // Basic DataTable Initialization (will be overridden by specific page scripts)
        document.addEventListener('DOMContentLoaded', function() {
            // If any table has class 'datatable', initialize it
            if ($.fn.DataTable && $('.datatable').length) {
                $('.datatable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    }
                });
            }
        });
    </script>

    {{-- ================= PAGE SPECIFIC SCRIPTS ================= --}}
    @stack('scripts')
</body>

</html>
