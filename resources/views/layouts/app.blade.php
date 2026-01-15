<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SmartERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f3f4f6;
            overflow-x: hidden;
            /* 🔥 horizontal overlap fix */
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background: #1f2937;
            color: #fff;
            padding: 20px;

            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 20px;
        }

        .sidebar a,
        .sidebar button {
            color: #fff;
            text-decoration: none;
            padding: 10px 12px;
            margin-bottom: 6px;
            border-radius: 6px;
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

        /* LOGOUT */
        .logout-box {
            margin-top: 40px;
            /* thoda upar */
            padding-top: 15px;
            border-top: 1px solid #374151;
        }

        .logout-box button {
            width: 100%;
            background: #dc2626;
            font-weight: 600;
        }

        .logout-box button:hover {
            background: #b91c1c;
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            margin-left: 220px;
            /* 🔥 EXACT sidebar width */
            min-height: 100vh;
            padding: 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f3f4f6;
            overflow-x: hidden;
        }


        /* ================= RESPONSIVE (OPTIONAL) ================= */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }
        }
    </style>
</head>

<body>

    {{-- ================= SIDEBAR ================= --}}
    {{-- ================= SIDEBAR ================= --}}
    <div class="sidebar">
        <h2>SmartERP</h2>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>

        {{-- ================= LOGGED IN USERS ================= --}}
        @auth

            {{-- ADMIN --}}
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('employees.index') }}">👥 Employees</a>
                <a href="{{ route('inventory.index') }}">📦 Inventory</a>
                <a href="{{ route('customers.index') }}">👤 Customers</a>
                <a href="{{ route('sales.index') }}">💰 Sales</a>
                <a href="{{ route('purchases.index') }}">🛒 Purchases</a>
                <a href="{{ route('attendance.manage') }}">🕒 Attendance</a>
                <a href="{{ route('leaves.manage') }}">✅ Manage Leaves</a>
                <a href="{{ route('admin.staff.approval') }}">🧑‍⚖️ Staff Approval</a>
            @endif

            {{-- HR --}}
            @if (auth()->user()->role === 'hr')
                <a href="{{ route('sales.index') }}">💰 Sales</a>
                <a href="{{ route('attendance.manage') }}">🕒 Attendance</a>
                <a href="{{ route('leaves.manage') }}">✅ Manage Leaves</a>
            @endif

            {{-- STAFF --}}
            @if (auth()->user()->role === 'staff')
                <a href="{{ route('attendance.my') }}">🕒 My Attendance</a>
                <a href="{{ route('leaves.my') }}">📝 My Leaves</a>
                <a href="{{ route('sales.index') }}">💰 Sales</a>
                <a href="{{ route('customers.index') }}">👤 Customers</a>
            @endif

            {{-- LOGOUT --}}
            <div class="logout-box">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">🚪 Logout</button>
                </form>
            </div>

        @endauth

        {{-- ================= GUEST USERS ================= --}}
        @guest
            <a href="{{ route('login') }}">🔐 Login</a>
            <a href="{{ route('register') }}">📝 Register</a>
        @endguest
    </div>


    {{-- ================= CONTENT ================= --}}
    <div class="main-content">
        @yield('content')
    </div>


    @if (session('success'))
        <div id="toast"
            style="
            position:fixed;
            top:20px;
            right:20px;
            background:#16a34a;
            color:#fff;
            padding:14px 20px;
            border-radius:10px;
            font-weight:600;
            box-shadow:0 10px 25px rgba(0,0,0,.2);
            z-index:9999;
        ">
            ✅ {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('toast')?.remove();
            }, 3000);
        </script>
    @endif


</body>

</html>
