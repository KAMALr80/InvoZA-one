@extends('layouts.app')

@section('content')
    <style>
        /* ===== MAIN CONTAINER ===== */
        .customers-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 40px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border: 1px solid #e5e7eb;
            min-height: calc(100vh - 80px);
        }

        /* ===== SEARCH BUTTON ===== */
        .search-btn {
            margin-top: 14px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            border: none;
            padding: 14px 26px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.35);
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 16px 30px rgba(59, 130, 246, 0.45);
        }

        .search-btn:active {
            transform: translateY(0);
        }


        /* ===== HEADER SECTION ===== */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 20px;
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .title-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

        .title-content h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .title-content p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 15px;
        }

        .add-customer-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }

        .add-customer-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4);
        }

        .add-customer-btn span {
            font-size: 20px;
            font-weight: 300;
        }

        /* ===== SUCCESS ALERT ===== */
        .success-alert {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            padding: 18px 24px;
            border-radius: 14px;
            margin-bottom: 30px;
            border-left: 5px solid #10b981;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .success-alert::before {
            content: "✓";
            background: #10b981;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin: 5px 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== SEARCH & FILTER ===== */
        .search-filter {
            background: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .search-input {
            width: 100%;
            padding: 14px 20px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: white;
            font-size: 15px;
            color: #374151;
            outline: none;
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: #6366f1;
            box-shadow:
                0 0 0 3px rgba(99, 102, 241, 0.25),
                0 8px 20px rgba(99, 102, 241, 0.15);
            background: linear-gradient(180deg, #ffffff, #f9fafb);
        }

        /* ===== LOADING SHIMMER ===== */
        .search-loading {
            font-size: 16px;
            font-weight: 600;
            color: #6366f1;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .search-loading::after {
            content: "";
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid rgba(99, 102, 241, .3);
            border-top-color: #6366f1;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        /* ===== TABLE CONTAINER ===== */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .customers-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 800px;
        }

        .customers-table thead {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .customers-table th {
            padding: 18px 24px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .customers-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .customers-table tbody tr:hover {
            background: #f8fafc;
        }

        .customers-table td {
            padding: 18px 24px;
            color: #475569;
            font-weight: 500;
            vertical-align: middle;
        }

        /* ===== CUSTOMER INFO CELL ===== */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .customer-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .customer-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .customers-table tbody tr:hover {
            background: linear-gradient(90deg, #f8fafc, #eef2ff);
            box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.15);
        }


        /* ===== CONTACT CELLS ===== */
        .mobile-cell {
            font-weight: 500;
            color: #374151;
        }

        .email-cell {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .email-cell:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* ===== GST BADGE ===== */
        .gst-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
            font-family: 'Courier New', monospace;
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .delete-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }

        /* ===== PAGINATION ===== */
        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 20px 0;
        }

        .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
            background: white;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            background: white;
            color: #4b5563;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .pagination li a:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination li.active span {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            border-color: transparent;
        }

        .pagination li.disabled span {
            background: #f9fafb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .customers-container {
                padding: 20px;
                margin: 20px auto;
            }

            .header-section {
                flex-direction: column;
                align-items: stretch;
            }

            .add-customer-btn {
                justify-content: center;
                width: 100%;
            }

            .title-content h1 {
                font-size: 24px;
            }

            .search-input {
                font-size: 14px;
            }

            .customers-table {
                min-width: 600px;
            }

            .customers-table th,
            .customers-table td {
                padding: 14px 16px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: row;
            }

            .action-btn {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
        }
    </style>


    <div class="customers-container">
        {{-- HEADER --}}
        <div class="header-section">
            <div class="title-wrapper">
                <div class="title-icon">
                    <span style="font-size: 28px; color: white;">👥</span>
                </div>
                <div class="title-content">
                    <h1>Customers</h1>
                    <p>Manage and track all your customers</p>
                </div>
            </div>
            <a href="{{ route('customers.create') }}" class="add-customer-btn">
                <span style="font-size: 20px;">+</span>
                Add New Customer
            </a>
        </div>

        {{-- STATS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    👥
                </div>
                <div class="stat-value">{{ $customers->total() }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    💰
                </div>
                <div class="stat-value">{{ $totalRevenue ?? '₹0' }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white;">
                    📊
                </div>
                <div class="stat-value">{{ $activeCustomers ?? $customers->count() }}</div>
                <div class="stat-label">Active Customers</div>
            </div>
        </div>

        {{-- SEARCH FILTER --}}
        <div class="search-filter">
            <input type="text" id="ajaxSearch" placeholder="Search customers by name, mobile, or email..."
                class="search-input" autocomplete="off">
        </div>

        <button type="submit" class="search-btn">
            <span>🔍</span>
            Search
        </button>
        @if (request('search'))
            <a href="{{ route('customers.index') }}"
                style="
                background: #f3f4f6;
                color: #374151;
                border: 1.5px solid #e5e7eb;
                padding: 12px 24px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
            ">
                <span>🔄</span>
                Clear
            </a>
        @endif
        </form>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="table-container">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>GST Number</th>
                        <th>Open Balance</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="customersTableBody">

                    @forelse ($customers as $c)
                        <tr>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="customer-name">{{ $c->name }}</div>
                                        <div style="font-size: 12px; color: #94a3b8;">
                                            ID: {{ $c->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mobile-cell">
                                    {{ $c->mobile }}
                                </div>
                            </td>
                            <td>
                                @if ($c->email)
                                    <a href="mailto:{{ $c->email }}" class="email-cell">
                                        {{ $c->email }}
                                    </a>
                                @else
                                    <span style="color: #9ca3af;">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if ($c->gst_no)
                                    <span class="gst-badge">{{ $c->gst_no }}</span>
                                @else
                                    <span style="color: #9ca3af;">N/A</span>
                                @endif
                            </td>
                                <td>
    @if ($c->open_balance > 0)
        <span style="color:#dc2626;font-weight:700;">
            ₹ {{ number_format($c->open_balance,2) }} Due
        </span>
    @elseif ($c->open_balance < 0)
        <span style="color:#16a34a;font-weight:700;">
            ₹ {{ number_format(abs($c->open_balance),2) }} Advance
        </span>
    @else
        <span style="color:#64748b;font-weight:600;">
            Clear
        </span>
    @endif
</td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('customers.sales', $c->id) }}" class="action-btn view-btn"
                                        title="View Sales">
                                        👁️
                                    </a>
                                    <a href="{{ route('customers.edit', $c->id) }}" class="action-btn edit-btn"
                                        title="Edit Customer">
                                        ✏️
                                    </a>
                                    <form action="{{ route('customers.destroy', $c->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')"
                                            title="Delete Customer">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-content">
                                    <span class="empty-icon">👥</span>
                                    <div class="empty-title">No Customers Found</div>
                                    <div class="empty-description">
                                        @if (request('search'))
                                            No customers found matching "{{ request('search') }}".
                                        @else
                                            Start adding customers to manage your client relationships and track their
                                            purchases.
                                        @endif
                                    </div>
                                    <a href="{{ route('customers.create') }}" class="add-customer-btn"
                                        style="display: inline-flex;">
                                        <span>+</span>
                                        Add Your First Customer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($customers->hasPages())
            <div class="pagination-container" id="paginationBox">

                <div class="pagination">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const input = document.getElementById('ajaxSearch');
            const tbody = document.getElementById('customersTableBody');
            const pagination = document.getElementById('paginationBox');
            let timer = null;

            if (!input) return;

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text ?? '';
                return div.innerHTML;
            }

            input.addEventListener('keyup', function() {
                clearTimeout(timer);
                const query = this.value.trim();

                if (query === '') {
                    location.reload();
                    return;
                }

                timer = setTimeout(() => {

                    tbody.innerHTML = `
        <tr>
            <td colspan="5" style="text-align:center;padding:40px;">
                <span class="search-loading">Searching customers</span>
            </td>
        </tr>`;

                    if (pagination) {
                        pagination.style.display = 'none';
                    }

                    fetch(
                            `{{ route('customers.ajax.search') }}?search=${encodeURIComponent(query)}`
                        )
                        .then(res => res.json())
                        .then(data => {

                            tbody.innerHTML = '';

                            if (data.length === 0) {
                                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:#6b7280;">
                            😕 No customers found for "${query}"
                        </td>
                    </tr>`;
                                return;
                            }

                            data.forEach(c => {
                                tbody.innerHTML += `
                    <tr>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">${c.name.charAt(0)}</div>
                                <div>
                                    <div class="customer-name">${c.name}</div>
                                    <div style="font-size:12px;color:#94a3b8;">ID: ${c.id}</div>
                                </div>
                            </div>
                        </td>
                        <td>${c.mobile}</td>
                        <td>${c.email ?? 'Not provided'}</td>
                        <td>${c.gst_no ?? 'N/A'}</td>
                            <td>
    ${
        c.open_balance > 0
            ? `<span style="color:#dc2626;font-weight:700;">₹ ${c.open_balance} Due</span>`
            : c.open_balance < 0
                ? `<span style="color:#16a34a;font-weight:700;">₹ ${Math.abs(c.open_balance)} Advance</span>`
                : `<span style="color:#64748b;font-weight:600;">Clear</span>`
    }
</td>

                        <td>
    <div class="action-buttons">

        <!-- 👁️ VIEW SALES -->
        <a href="/customers/${c.id}/sales"
           class="action-btn view-btn"
           title="View Sales">
            👁️
        </a>

        <!-- ✏️ EDIT -->
        <a href="/customers/${c.id}/edit"
           class="action-btn edit-btn"
           title="Edit Customer">
            ✏️
        </a>

        <!-- 🗑️ DELETE -->
        <form action="/customers/${c.id}"
              method="POST"
              style="display:inline;"
              onsubmit="return confirm('Are you sure you want to delete this customer?')">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">

            <button type="submit"
                    class="action-btn delete-btn"
                    title="Delete Customer">
                🗑️
            </button>
        </form>

    </div>
</td>

                    </tr>`;
                            });
                        })
                        .catch(() => {
                            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center;color:red;padding:40px;">
                        ⚠️ Error loading data
                    </td>
                </tr>`;
                        });

                }, 400);
            });

        });
    </script>
@endsection
