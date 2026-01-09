<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SmartERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Common CSS --}}

    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            background: #1f2937;
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a,
        .sidebar button {
            width: 100%;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar button:hover {
            background: #374151;
        }

        .sidebar a.active {
            background: #2563eb;
            font-weight: bold;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 20px;
            background: #f3f4f6;
        }

        /* REPORT MENU */
        .report-menu {
            margin-top: 10px;
        }

        .report-title {
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .report-title:hover {
            background: #374151;
        }

        .report-submenu {
            display: none;
            padding-left: 10px;
            margin-top: 5px;
        }

        .report-submenu a {
            display: block;
            padding: 8px 10px;
            font-size: 14px;
            color: #e5e7eb;
            border-radius: 4px;
        }

        .report-submenu a:hover {
            background: #4b5563;
        }

        /* LOGOUT */
        .logout-box {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #374151;
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- SIDEBAR --}}
        <div class="sidebar">
            <h2>SmartERP</h2>

            {{-- DASHBOARD --}}
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
            @endauth

            {{-- ADMIN --}}
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
                        👥 Employees
                    </a>

                    <a href="{{ route('inventory.index') }}" class="{{ request()->is('inventory*') ? 'active' : '' }}">
                        📦 Inventory
                    </a>

                    <a href="{{ route('admin.staff.approval') }}">
                        ✅ Staff Approval
                    </a>
                @endif
            @endauth

            {{-- ADMIN + HR --}}
            @auth
                @if (in_array(auth()->user()->role, ['admin', 'hr']))
                    <a href="{{ route('sales.index') }}" class="{{ request()->is('sales*') ? 'active' : '' }}">
                        💰 Sales
                    </a>

                    <a href="{{ route('purchases.index') }}" class="{{ request()->is('purchases*') ? 'active' : '' }}">
                        🛒 Purchases
                    </a>
                    <a href="{{ url('/attendance') }}" class="{{ request()->is('attendance*') ? 'active' : '' }}">
                        🕒 Attendance
                    </a>

                    @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'hr']))
                        <a href="{{ route('leaves.manage') }}"
                            class="{{ request()->is('leaves/manage') ? 'active' : '' }}">
                            ✅ Manage Leaves
                        </a>
                    @endif
                    <div class="report-menu">
                        <div class="report-title" onclick="toggleReports()">
                            📑 Reports
                            <span id="report-arrow" style="float:right;">▸</span>
                        </div>

                        <div class="report-submenu" id="reportSubmenu">
                            <a href="{{ route('reports.sales') }}">📈 Sales Report</a>
                            <a href="{{ route('reports.purchases') }}">🛒 Purchase Report</a>
                            <a href="{{ route('reports.attendance') }}">🕒 Attendance Report</a>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- STAFF --}}
            @auth
                {{-- STAFF --}}
                @if (auth()->check() && auth()->user()->role === 'staff')
                    <a href="{{ route('attendance.my') }}" class="{{ request()->is('attendance/my') ? 'active' : '' }}">
                        🕒 My Attendance
                    </a>

                    <a href="{{ route('leaves.my') }}" class="{{ request()->is('leaves/my') ? 'active' : '' }}">
                        📝 My Leaves
                    </a>
                @endif


            @endauth




            {{-- LOGOUT --}}
            @auth
                <div class="logout-box">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">🚪 Logout</button>
                    </form>
                </div>
            @endauth
        </div>

        {{-- MAIN CONTENT --}}
        <div class="content">
            @yield('content')
        </div>

    </div>

    <script>
        function toggleReports() {
            const menu = document.getElementById('reportSubmenu');
            const arrow = document.getElementById('report-arrow');

            if (menu.style.display === 'block') {
                menu.style.display = 'none';
                arrow.innerHTML = '▸';
            } else {
                menu.style.display = 'block';
                arrow.innerHTML = '▾';
            }
        }
    </script>

</body>

</html>
