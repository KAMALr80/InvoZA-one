@extends('layouts.app')

@section('content')
    <style>
        /* ================= BRAND SYSTEM ================= */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-soft: #f8fafc;
            --border-soft: #e5e7eb;
        }

        /* ================= CONTAINER ================= */
        .customers-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 40px;
            background: linear-gradient(135deg, #ffffff, var(--bg-soft));
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-soft);
        }

        /* ================= HEADER ================= */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
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
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 26px;
            box-shadow: 0 8px 25px rgba(99, 102, 241, .35);
        }

        .title-content h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: var(--text-main);
        }

        .title-content p {
            margin: 6px 0 0;
            color: var(--text-muted);
        }

        .add-customer-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            gap: 10px;
            align-items: center;
            box-shadow: 0 8px 20px rgba(99, 102, 241, .35);
            transition: all .3s;
        }

        .add-customer-btn:hover {
            transform: translateY(-3px);
        }

        /* ================= STATS ================= */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 18px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(99, 102, 241, .12), transparent 70%);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
        }

        .stat-label {
            font-size: 13px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 700;
        }

        /* ================= TABLE ================= */
        #customersTable thead th {
            background: #f1f5f9;
            color: #334155;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 700;
        }

        #customersTable tbody tr {
            transition: all .2s ease;
        }

        #customersTable tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.003);
        }

        #customersTable td {
            vertical-align: middle;
            position: relative;
        }

        .customer-info {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .customer-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        /* ================= DROPDOWN ACTION BUTTON - FIXED ================= */
        .action-container {
            position: relative;
            display: inline-block;
        }

        .main-action-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            white-space: nowrap;
        }

        .main-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 5px);
            right: 0;
            left: auto;
            margin-top: 0;
            background: white;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-5px);
            transition: all 0.2s ease;
            border: 1px solid var(--border-soft);
            overflow: hidden;
        }

        /* Position dropdown to the left if it's near the edge */
        .action-container .dropdown-menu {
            right: 0;
            left: auto;
        }

        /* For the last few rows, show dropdown above instead of below */
        .action-container.dropdown-up .dropdown-menu {
            top: auto;
            bottom: calc(100% + 5px);
            transform: translateY(5px);
        }

        .action-container.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.2s;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: #f8fafc;
        }

        .dropdown-item.view {
            color: var(--info);
        }

        .dropdown-item.edit {
            color: var(--primary);
        }

        .dropdown-item.delete {
            color: var(--danger);
        }

        .dropdown-item i,
        .dropdown-item span:first-child {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .delete-form {
            margin: 0;
            display: block;
        }

        .delete-button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--danger);
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .delete-button:hover {
            background: #fef2f2;
        }

        /* ================= SEARCH ================= */
        .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_filter input {
            border: 1.5px solid var(--border-soft) !important;
            border-radius: 12px !important;
            padding: 10px 15px !important;
            width: 250px !important;
            margin-left: 10px !important;
        }

        .dataTables_filter label {
            font-weight: 600;
            color: var(--text-muted);
        }

        /* ================= PAGINATION ================= */
        .dataTables_paginate {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 5px;
        }

        .dataTables_paginate .paginate_button {
            border-radius: 10px !important;
            padding: 8px 14px !important;
            margin: 0 2px !important;
            border: 1px solid var(--border-soft) !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
            color: white !important;
            border: none !important;
        }

        .dataTables_info {
            margin-top: 15px;
            color: var(--text-muted);
        }
    </style>

    <div class="customers-container">

        {{-- HEADER --}}
        <div class="header-section">
            <div class="title-wrapper">
                <div class="title-icon">👥</div>
                <div class="title-content">
                    <h1>Customers</h1>
                    <p>Manage and track all your customers</p>
                </div>
            </div>
            <a href="{{ route('customers.create') }}" class="add-customer-btn">
                ➕ Add Customer
            </a>
        </div>

        {{-- STATS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--primary)">👥</div>
                <div class="stat-value">{{ $customers->count() }}</div>
                <div class="stat-label">Total Customers</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background:var(--success)">💰</div>
                <div class="stat-value">{{ $totalRevenue ?? '₹0' }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background:var(--info)">📊</div>
                <div class="stat-value">{{ $activeCustomers ?? $customers->count() }}</div>
                <div class="stat-label">Active Customers</div>
            </div>
        </div>

        {{-- TABLE --}}
        <table id="customersTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>GST</th>
                    <th>Balance</th>
                    <th style="min-width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $index => $c)
                    <tr>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">{{ strtoupper(substr($c->name, 0, 1)) }}</div>
                                <div>
                                    <strong>{{ $c->name }}</strong><br>
                                    <small>ID: {{ $c->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $c->mobile }}</td>
                        <td>{{ $c->email ?? 'N/A' }}</td>
                        <td>{{ $c->gst_no ?? 'N/A' }}</td>
                        <td>
                            @if ($c->open_balance > 0)
                                <span
                                    style="color:var(--danger); font-weight: 600;">₹{{ number_format($c->open_balance, 2) }}
                                    Due</span>
                            @elseif($c->open_balance < 0)
                                <span
                                    style="color:var(--success); font-weight: 600;">₹{{ number_format(abs($c->open_balance), 2) }}
                                    Advance</span>
                            @else
                                <span style="color:var(--text-muted);">Clear</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-container" data-row-index="{{ $index }}">
                                <button class="main-action-btn" onclick="toggleDropdown(this, event)">
                                    <span>Actions</span>
                                    <span style="font-size: 12px;">▼</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('customers.sales', $c->id) }}" class="dropdown-item view">
                                        <span>👁️</span> View Details
                                    </a>
                                    <a href="{{ route('customers.edit', $c->id) }}" class="dropdown-item edit">
                                        <span>✏️</span> Edit Customer
                                    </a>
                                    <form method="POST" action="{{ route('customers.destroy', $c->id) }}"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button"
                                            onclick="return confirm('Are you sure you want to delete this customer?')">
                                            <span>🗑️</span> Delete Customer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- DATATABLES --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(function() {
            // Initialize DataTable
            var table = $('#customersTable').DataTable({
                pageLength: 25,
                responsive: false, // Disable responsive to have better control
                language: {
                    searchPlaceholder: "Search customers...",
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                drawCallback: function() {
                    // Close all dropdowns when table redraws
                    $('.action-container').removeClass('active');
                }
            });

            // Close dropdown when clicking anywhere on the page
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.action-container').length) {
                    $('.action-container').removeClass('active');
                }
            });

            // Prevent dropdown from closing when clicking inside dropdown menu
            $(document).on('click', '.dropdown-menu', function(e) {
                e.stopPropagation();
            });
        });

        function toggleDropdown(button, event) {
            // Prevent the click from bubbling up
            if (event) {
                event.stopPropagation();
            }

            var container = $(button).closest('.action-container');
            var dropdown = container.find('.dropdown-menu');

            // Close all other dropdowns
            $('.action-container').not(container).removeClass('active');

            // Toggle current dropdown
            container.toggleClass('active');

            if (container.hasClass('active')) {
                // Get the row position
                var row = container.closest('tr');
                var tableBody = row.closest('tbody');
                var rows = tableBody.find('tr');
                var rowIndex = rows.index(row);
                var totalRows = rows.length;

                // If this is one of the last 3 rows, show dropdown above
                if (rowIndex >= totalRows - 3) {
                    container.addClass('dropdown-up');
                } else {
                    container.removeClass('dropdown-up');
                }

                // Check if dropdown is going out of viewport on the right
                var dropdownOffset = dropdown.offset();
                if (dropdownOffset) {
                    var dropdownWidth = dropdown.outerWidth();
                    var viewportWidth = $(window).width();

                    if (dropdownOffset.left + dropdownWidth > viewportWidth - 20) {
                        dropdown.css('left', 'auto');
                        dropdown.css('right', '0');
                    } else {
                        dropdown.css('left', 'auto');
                        dropdown.css('right', '0');
                    }
                }
            }
        }
    </script>
@endsection
