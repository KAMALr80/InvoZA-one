<div class="sidebar">
    <h2>SmartERP</h2>

    {{-- ================= DASHBOARD ================= --}}
    <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
        📊 Dashboard
    </a>

    {{-- ================= ADMIN ================= --}}
    @if (auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
            👥 Employees
        </a>

        <a href="{{ route('inventory.index') }}" class="{{ request()->is('inventory*') ? 'active' : '' }}">
            📦 Inventory
        </a>

        <a href="{{ route('customers.index') }}" class="{{ request()->is('customers*') ? 'active' : '' }}">
            👤 Customers
        </a>

        <a href="{{ route('sales.index') }}" class="{{ request()->is('sales*') ? 'active' : '' }}">
            💰 Sales
        </a>

        <a href="{{ route('purchases.index') }}" class="{{ request()->is('purchases*') ? 'active' : '' }}">
            🛒 Purchases
        </a>

        <a href="{{ route('attendance.manage') }}" class="{{ request()->is('attendance/manage') ? 'active' : '' }}">
            🕒 Attendance
        </a>

        <a href="{{ route('leaves.manage') }}" class="{{ request()->is('leaves/manage') ? 'active' : '' }}">
            ✅ Manage Leaves
        </a>

        <a href="{{ route('admin.staff.approval') }}"
            class="{{ request()->is('admin/staff-approval') ? 'active' : '' }}">
            🧑‍⚖️ Staff Approval
        </a>
    @endif

    {{-- ================= HR ================= --}}
    @if (auth()->check() && auth()->user()->role === 'hr')
        <a href="{{ route('sales.index') }}" class="{{ request()->is('sales*') ? 'active' : '' }}">
            💰 Sales
        </a>

        <a href="{{ route('attendance.manage') }}" class="{{ request()->is('attendance/manage') ? 'active' : '' }}">
            🕒 Attendance
        </a>

        <a href="{{ route('leaves.manage') }}" class="{{ request()->is('leaves/manage') ? 'active' : '' }}">
            ✅ Manage Leaves
        </a>
    @endif

    {{-- ================= STAFF ================= --}}
    @if (auth()->check() && auth()->user()->role === 'staff')
        <a href="{{ route('attendance.my') }}" class="{{ request()->is('attendance/my') ? 'active' : '' }}">
            🕒 My Attendance
        </a>

        <a href="{{ route('leaves.my') }}" class="{{ request()->is('leaves/my') ? 'active' : '' }}">
            📝 My Leaves
        </a>

        <a href="{{ route('sales.index') }}" class="{{ request()->is('sales*') ? 'active' : '' }}">
            💰 Sales
        </a>

        <a href="{{ route('customers.index') }}" class="{{ request()->is('customers*') ? 'active' : '' }}">
            👤 Customers
        </a>
    @endif

    {{-- ================= LOGOUT ================= --}}
    <div class="logout-box">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>
</div>

{{-- ================= STYLE ================= --}}
<style>
    .sidebar {
        width: 220px;
        background: #1f2937;
        color: #fff;
        padding: 20px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 20px;
        letter-spacing: 1px;
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
        transition: 0.2s;
    }

    .sidebar a:hover,
    .sidebar button:hover {
        background: #374151;
    }

    .sidebar a.active {
        background: #2563eb;
        font-weight: bold;
    }

    .logout-box {
        margin-top: auto;
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
</style>
