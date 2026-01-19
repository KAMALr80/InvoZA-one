@extends('layouts.app')

@section('content')
    <div class="employee-container">

        {{-- HEADER --}}
        <div class="employee-header">
            <div class="header-content">
                <div class="header-icon">👥</div>
                <div class="header-text">
                    <h1 class="header-title">Employee Management</h1>
                    <p class="header-subtitle">Manage your team members and departments</p>
                </div>
            </div>

            {{-- ADD EMPLOYEE – ADMIN ONLY --}}
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('employees.create') }}" class="btn-add">
                    <span class="btn-icon">+</span>
                    Add Employee
                </a>
            @endif
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="success-message">
                <span class="success-icon">✓</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- SEARCH BAR --}}
        <div class="search-section">
            <form method="GET" class="search-form">
                <div class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" placeholder="Search by name, email, or department..."
                        value="{{ request('search') }}" class="search-input">
                </div>
                <button type="submit" class="btn-search">
                    Search
                </button>
            </form>
            <div class="search-info">
                Showing {{ $employees->total() }} employees
            </div>
        </div>

        {{-- TABLE CONTAINER --}}
        <div class="table-container">
            <div class="table-responsive">
                <table class="employee-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="table-header">
                                    <span class="header-icon">#</span>
                                    Employee Code
                                </div>
                            </th>
                            <th>
                                <div class="table-header">
                                    <span class="header-icon">👤</span>
                                    Name
                                </div>
                            </th>
                            <th>
                                <div class="table-header">
                                    <span class="header-icon">✉️</span>
                                    Email
                                </div>
                            </th>
                            <th>
                                <div class="table-header">
                                    <span class="header-icon">🏢</span>
                                    Department
                                </div>
                            </th>
                            <th>
                                <div class="table-header">
                                    <span class="header-icon">⚡</span>
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $emp)
                            <tr class="table-row">
                                <td>
                                    <div class="employee-code">
                                        <span class="code-badge">{{ $emp->employee_code }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="employee-name">
                                        <div class="avatar">
                                            {{ strtoupper(substr($emp->name, 0, 1)) }}
                                        </div>
                                        <div class="name-info">
                                            <div class="name">{{ $emp->name }}</div>
                                            <div class="role">Employee</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="employee-email">
                                        <span class="email-icon">📧</span>
                                        {{ $emp->email }}
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="department-badge {{ strtolower(str_replace(' ', '-', $emp->department)) }}">
                                        {{ $emp->department }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- EDIT – ADMIN + HR --}}
                                        @if (in_array(auth()->user()->role, ['admin', 'hr']))
                                            <a href="{{ route('employees.edit', $emp->id) }}" class="btn-edit">
                                                <span class="btn-icon">✏️</span>
                                                Edit
                                            </a>
                                        @endif

                                        {{-- DELETE – ADMIN ONLY --}}
                                        @if (auth()->user()->role === 'admin')
                                            <form action="{{ route('employees.destroy', $emp->id) }}" method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete" onclick="return confirmDelete()">
                                                    <span class="btn-icon">🗑️</span>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif

                                        {{-- STAFF VIEW --}}
                                        @if (auth()->user()->role === 'staff')
                                            <span class="view-only">View Only</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">👤</div>
                                        <h3>No Employees Found</h3>
                                        <p>Try searching with different keywords or add a new employee</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if ($employees->hasPages())
            <div class="pagination-container">
                {{ $employees->links('vendor.pagination.custom') }}
            </div>
        @endif

    </div>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this employee? This action cannot be undone.');
        }
    </script>

    <style>
        /* Main Container */
        .employee-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Header */
        .employee-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding: 0 8px;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icon {
            font-size: 48px;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }

        .header-text {
            flex: 1;
        }

        .header-title {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            color: #64748b;
            font-size: 16px;
            margin: 8px 0 0 0;
        }

        /* Buttons */
        .btn-add {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
        }

        .btn-icon {
            font-size: 18px;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
            color: #065f46;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .success-icon {
            font-size: 20px;
            color: #10b981;
        }

        /* Search Section */
        .search-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .search-form {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }

        .search-wrapper {
            flex: 1;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
        }

        .search-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            color: #1e293b;
            background: white;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .btn-search {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35);
        }

        .search-info {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .employee-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .employee-table th {
            padding: 20px 24px;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
        }

        .table-header {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-icon {
            font-size: 16px;
            opacity: 0.7;
        }

        /* Table Rows */
        .table-row {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background: #f8fafc;
        }

        .table-row td {
            padding: 20px 24px;
        }

        /* Employee Code */
        .employee-code {
            display: flex;
            align-items: center;
        }

        .code-badge {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 1px solid #fbbf24;
        }

        /* Employee Name */
        .employee-name {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .name-info {
            display: flex;
            flex-direction: column;
        }

        .name {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .role {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        /* Email */
        .employee-email {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4b5563;
            font-size: 15px;
        }

        .email-icon {
            color: #9ca3af;
            font-size: 16px;
        }

        /* Department Badges */
        .department-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            min-width: 100px;
        }

        .department-badge.human-resources {
            background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
            color: #1e40af;
            border: 1px solid #60a5fa;
        }

        .department-badge.engineering,
        .department-badge.technology {
            background: linear-gradient(135deg, #fce7f3 0%, #f9a8d4 100%);
            color: #831843;
            border: 1px solid #f472b6;
        }

        .department-badge.sales {
            background: linear-gradient(135deg, #dcfce7 0%, #86efac 100%);
            color: #166534;
            border: 1px solid #4ade80;
        }

        .department-badge.marketing {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .department-badge.finance {
            background: linear-gradient(135deg, #e0e7ff 0%, #a5b4fc 100%);
            color: #3730a3;
            border: 1px solid #818cf8;
        }

        /* Default badge for other departments */
        .department-badge:not(.human-resources):not(.engineering):not(.technology):not(.sales):not(.marketing):not(.finance) {
            background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%);
            color: #374151;
            border: 1px solid #9ca3af;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.2);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(239, 68, 68, 0.2);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        .delete-form {
            display: inline;
        }

        .view-only {
            background: #f3f4f6;
            color: #6b7280;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #d1d5db;
            font-style: italic;
        }

        /* Empty State */
        .empty-row {
            border-bottom: none;
        }

        .empty-state {
            padding: 80px 20px;
            text-align: center;
        }

        .empty-icon {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 20px;
            color: #1e293b;
            margin: 0 0 8px 0;
            font-weight: 700;
        }

        .empty-state p {
            color: #64748b;
            margin: 0;
            font-size: 15px;
        }

        /* Pagination */
        .pagination-container {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .employee-container {
                padding: 16px;
            }

            .employee-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .header-content {
                width: 100%;
            }

            .header-icon {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }

            .header-title {
                font-size: 24px;
            }

            .search-form {
                flex-direction: column;
            }

            .btn-add,
            .btn-search {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-edit,
            .btn-delete,
            .view-only {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {

            .employee-table th,
            .employee-table td {
                padding: 12px;
            }

            .employee-name {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .department-badge {
                min-width: auto;
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
@endsection
