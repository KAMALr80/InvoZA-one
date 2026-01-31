<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Sorting arrow styles */
        .sort-icon {
            margin-left: 6px;
            color: #9ca3af;
            font-size: 12px;
        }

        .sort-asc .sort-icon {
            color: #3b82f6;
        }

        .sort-desc .sort-icon {
            color: #3b82f6;
        }

        /* Action buttons with visible icons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            border: 1.5px solid transparent;
        }

        .action-btn i {
            font-size: 16px !important;
        }

        .btn-view {
            background: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
            border-color: rgba(14, 165, 233, 0.2);
        }

        .btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border-color: rgba(245, 158, 11, 0.2);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Make table headers clickable for sorting */
        .sortable-header {
            cursor: pointer;
            user-select: none;
        }

        .sortable-header:hover {
            background-color: #f9fafb;
        }

        /* Ensure icons are visible */
        i.fas,
        i.far,
        i.fab {
            display: inline-block !important;
            font-style: normal !important;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #166534;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .status-inactive {
            background: rgba(156, 163, 175, 0.1);
            color: #4b5563;
            border: 1px solid rgba(156, 163, 175, 0.2);
        }

        /* Department badges */
        .dept-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6, 182, 212, 0.1);
            color: #0e7490;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        /* Employee code badge */
        .emp-code-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
    </style>
</head>

<body>
    @extends('layouts.app')

    @section('content')
        <div
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden;">
            <!-- Background pattern -->
            <div style="position: absolute; top: 0; right: 0; width: 300px; height: 100%; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 100 100" fill="white" opacity="0.1">
                <path d="M0,0 L100,0 L100,100 Z" /></svg>
            </div>

            <div
                style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div
                        style="width: 70px; height: 70px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border-radius: 18px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255, 255, 255, 0.3); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);">
                        <i class="fas fa-users" style="font-size: 32px; color: white;"></i>
                    </div>
                    <div>
                        <h1
                            style="font-size: 36px; font-weight: 800; color: white; margin: 0; letter-spacing: -0.5px; line-height: 1.2;">
                            Employee Management</h1>
                        <p style="color: rgba(255, 255, 255, 0.9); font-size: 18px; margin: 8px 0 0 0; font-weight: 400;">
                            Manage
                            your team members and departments</p>
                    </div>
                </div>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('employees.create') }}"
                        style="display: inline-flex;
                  align-items: center;
                  gap: 12px;
                  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                  color: white;
                  border: none;
                  padding: 16px 28px;
                  border-radius: 14px;
                  text-decoration: none;
                  font-weight: 700;
                  font-size: 16px;
                  box-shadow: 0 8px 32px rgba(16, 185, 129, 0.4);
                  transition: all 0.3s ease;
                  position: relative;
                  overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 40px rgba(16, 185, 129, 0.6)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(16, 185, 129, 0.4)';">
                        <span style="font-size: 20px;">+</span>
                        <span>Add Employee</span>
                        <div
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%); pointer-events: none;">
                        </div>
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div
                    style="margin-top: 24px;
                background: rgba(34, 197, 94, 0.2);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(34, 197, 94, 0.3);
                border-radius: 12px;
                padding: 16px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideDown 0.3s ease;">
                    <div
                        style="width: 40px; height: 40px; background: rgba(34, 197, 94, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="font-size: 20px; color: #22c55e;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="color: white; font-weight: 600; font-size: 16px;">Success!</div>
                        <div style="color: rgba(255, 255, 255, 0.9); font-size: 14px; margin-top: 2px;">
                            {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.style.display='none'"
                        style="background: none; border: none; color: rgba(255, 255, 255, 0.7); font-size: 20px; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='white'"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgba(255, 255, 255, 0.7)'">
                        ×
                    </button>
                </div>
            @endif
        </div>

        <!-- DataTable Section -->
        <div
            style="background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08); border: 1px solid #e5e7eb; overflow: hidden;">
            <!-- Card Header -->
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; background: white;">
                <!-- Show entries -->
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="color: #4b5563; font-size: 14px; font-weight: 500;">Show</span>
                    <div style="position: relative;">
                        <select id="entriesPerPage"
                            style="padding: 8px 32px 8px 16px;
                               border: 1.5px solid #e5e7eb;
                               border-radius: 10px;
                               font-size: 14px;
                               color: #374151;
                               background: white;
                               cursor: pointer;
                               appearance: none;
                               min-width: 70px;"
                            onchange="handlePerPageChange(this)">
                            <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <div
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 12px;">
                            ▼</div>
                    </div>
                    <span style="color: #4b5563; font-size: 14px; font-weight: 500;">entries</span>
                </div>

                <!-- Search box -->
                <div style="position: relative;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="position: relative;">
                            <input type="text" id="globalSearch" placeholder="Search employees..."
                                value="{{ request('search') }}"
                                style="padding: 10px 40px 10px 16px;
                                  border: 1.5px solid #e5e7eb;
                                  border-radius: 10px;
                                  font-size: 14px;
                                  color: #374151;
                                  width: 240px;
                                  transition: all 0.3s;"
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                            <i class="fas fa-search"
                                style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px;"></i>
                        </div>
                        @if (request('search'))
                            <button id="clearSearch"
                                style="background: #f3f4f6;
                               border: 1.5px solid #e5e7eb;
                               border-radius: 10px;
                               width: 40px;
                               height: 40px;
                               display: flex;
                               align-items: center;
                               justify-content: center;
                               cursor: pointer;
                               transition: all 0.3s;"
                                onmouseover="this.style.backgroundColor='#e5e7eb'"
                                onmouseout="this.style.backgroundColor='#f3f4f6'">
                                <i class="fas fa-times" style="color: #6b7280; font-size: 14px;"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div style="overflow-x: auto;">
                <table id="employeeDataTable" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th
                                style="padding: 16px 12px; text-align: center; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 60px;">
                                #</th>
                            <th class="sortable-header" onclick="sortTable(1)"
                                style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 140px; cursor: pointer;">
                                <span>EMPLOYEE CODE</span>
                                <i class="fas fa-sort sort-icon"
                                    style="margin-left: 6px; color: #9ca3af; font-size: 12px;"></i>
                            </th>
                            <th class="sortable-header" onclick="sortTable(2)"
                                style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 160px; cursor: pointer;">
                                <span>NAME</span>
                                <i class="fas fa-sort sort-icon"
                                    style="margin-left: 6px; color: #9ca3af; font-size: 12px;"></i>
                            </th>
                            <th class="sortable-header" onclick="sortTable(3)"
                                style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 200px; cursor: pointer;">
                                <span>EMAIL</span>
                                <i class="fas fa-sort sort-icon"
                                    style="margin-left: 6px; color: #9ca3af; font-size: 12px;"></i>
                            </th>
                            <th class="sortable-header" onclick="sortTable(4)"
                                style="padding: 16px 12px; text-align: left; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 140px; cursor: pointer;">
                                <span>DEPARTMENT</span>
                                <i class="fas fa-sort sort-icon"
                                    style="margin-left: 6px; color: #9ca3af; font-size: 12px;"></i>
                            </th>
                            <th
                                style="padding: 16px 12px; text-align: center; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 100px;">
                                STATUS</th>
                            <th
                                style="padding: 16px 12px; text-align: center; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; white-space: nowrap; min-width: 180px;">
                                ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $index => $emp)
                            <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#f9fafb'"
                                onmouseout="this.style.backgroundColor='white'">
                                <td
                                    style="padding: 16px 12px; text-align: center; color: #6b7280; font-size: 14px; font-weight: 500;">
                                    {{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}
                                </td>
                                <td style="padding: 16px 12px;">
                                    <span class="emp-code-badge">
                                        <i class="fas fa-id-card" style="font-size: 12px;"></i>
                                        {{ $emp->employee_code }}
                                    </span>
                                </td>
                                <td style="padding: 16px 12px; color: #111827; font-size: 15px; font-weight: 600;">
                                    {{ $emp->name }}
                                </td>
                                <td style="padding: 16px 12px;">
                                    <a href="mailto:{{ $emp->email }}"
                                        style="display: inline-flex; align-items: center; gap: 8px; color: #3b82f6; text-decoration: none; font-size: 14px; transition: color 0.2s;"
                                        onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#3b82f6'">
                                        <i class="fas fa-envelope" style="font-size: 14px;"></i>
                                        {{ $emp->email }}
                                    </a>
                                </td>
                                <td style="padding: 16px 12px;">
                                    <span class="dept-badge">
                                        <i class="fas fa-building" style="font-size: 12px;"></i>
                                        {{ $emp->department }}
                                    </span>
                                </td>
                                <td style="padding: 16px 12px; text-align: center;">
                                    <span
                                        class="status-badge {{ $emp->status == 1 ? 'status-active' : 'status-inactive' }}">
                                        <i class="fas fa-circle"
                                            style="font-size: 8px; color: {{ $emp->status == 1 ? '#22c55e' : '#9ca3af' }};"></i>
                                        {{ $emp->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td style="padding: 16px 12px; text-align: center;">
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap;">
                                        <!-- View Button -->
                                        <a href="{{ route('employees.show', $emp->id) }}" class="action-btn btn-view"
                                            title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        @if (in_array(auth()->user()->role, ['admin', 'hr']))
                                            <a href="{{ route('employees.edit', $emp->id) }}" class="action-btn btn-edit"
                                                title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        <!-- Delete Button -->
                                        @if (auth()->user()->role === 'admin')
                                            <form action="{{ route('employees.destroy', $emp->id) }}" method="POST"
                                                style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-btn btn-delete"
                                                    onclick="confirmDelete(event, '{{ $emp->name }}')"
                                                    title="Delete Employee">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if (auth()->user()->role === 'staff')
                                            <span
                                                style="background: rgba(156, 163, 175, 0.1); color: #6b7280; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 500; border: 1px solid rgba(156, 163, 175, 0.2);">
                                                <i class="fas fa-eye me-1"></i> View Only
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                        <div
                                            style="width: 80px; height: 80px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-users" style="font-size: 32px; color: #9ca3af;"></i>
                                        </div>
                                        <div>
                                            <div
                                                style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                                                No employees found</div>
                                            <div style="font-size: 14px; color: #6b7280;">Try adding a new employee or
                                                adjust your search</div>
                                        </div>
                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('employees.create') }}"
                                                style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; margin-top: 8px;">
                                                <i class="fas fa-plus"></i>
                                                Add First Employee
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-top: 1px solid #e5e7eb; background: white;">
                <div style="color: #6b7280; font-size: 14px;">
                    Showing <span style="font-weight: 600; color: #374151;">{{ $employees->firstItem() ?? 0 }}</span>
                    to <span style="font-weight: 600; color: #374151;">{{ $employees->lastItem() ?? 0 }}</span>
                    of <span style="font-weight: 600; color: #374151;">{{ $employees->total() }}</span> entries
                </div>

                <!-- Pagination -->
                @if ($employees->hasPages())
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <!-- First Page -->
                        @if (!$employees->onFirstPage())
                            <a href="{{ $employees->url(1) }}"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'"
                                title="First Page">
                                <i class="fas fa-angle-double-left" style="font-size: 12px;"></i>
                            </a>
                        @else
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #9ca3af; background: #f9fafb; font-size: 14px;">
                                <i class="fas fa-angle-double-left" style="font-size: 12px;"></i>
                            </span>
                        @endif

                        <!-- Previous Page -->
                        @if (!$employees->onFirstPage())
                            <a href="{{ $employees->previousPageUrl() }}"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'"
                                title="Previous">
                                <i class="fas fa-angle-left" style="font-size: 12px;"></i>
                            </a>
                        @else
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #9ca3af; background: #f9fafb; font-size: 14px;">
                                <i class="fas fa-angle-left" style="font-size: 12px;"></i>
                            </span>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $employees->currentPage();
                            $last = $employees->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $employees->url(1) }}"
                                style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s; margin: 0 2px; padding: 0 8px;"
                                onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'">
                                1
                            </a>
                            @if ($start > 2)
                                <span
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; color: #9ca3af; font-size: 14px; margin: 0 2px;">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $current)
                                <span
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border-radius: 10px; font-size: 14px; font-weight: 600; margin: 0 2px; padding: 0 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $employees->url($i) }}"
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s; margin: 0 2px; padding: 0 8px;"
                                    onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                    onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; color: #9ca3af; font-size: 14px; margin: 0 2px;">...</span>
                            @endif
                            <a href="{{ $employees->url($last) }}"
                                style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s; margin: 0 2px; padding: 0 8px;"
                                onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'">
                                {{ $last }}
                            </a>
                        @endif

                        <!-- Next Page -->
                        @if ($employees->hasMorePages())
                            <a href="{{ $employees->nextPageUrl() }}"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'"
                                title="Next">
                                <i class="fas fa-angle-right" style="font-size: 12px;"></i>
                            </a>
                        @else
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #9ca3af; background: #f9fafb; font-size: 14px;">
                                <i class="fas fa-angle-right" style="font-size: 12px;"></i>
                            </span>
                        @endif

                        <!-- Last Page -->
                        @if ($employees->hasMorePages())
                            <a href="{{ $employees->url($last) }}"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 14px; transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.borderColor='#d1d5db'"
                                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'"
                                title="Last Page">
                                <i class="fas fa-angle-double-right" style="font-size: 12px;"></i>
                            </a>
                        @else
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1.5px solid #e5e7eb; border-radius: 10px; color: #9ca3af; background: #f9fafb; font-size: 14px;">
                                <i class="fas fa-angle-double-right" style="font-size: 12px;"></i>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <script>
            function handlePerPageChange(select) {
                const perPage = select.value;
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', perPage);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }

            function confirmDelete(event, employeeName) {
                if (confirm(`Are you sure you want to delete "${employeeName}"? This action cannot be undone.`)) {
                    event.target.closest('form').submit();
                }
                event.preventDefault();
            }

            let currentSortColumn = -1;
            let sortDirection = 1; // 1 = asc, -1 = desc

            function sortTable(columnIndex) {
                const headers = document.querySelectorAll('.sortable-header');

                // Reset all sort icons
                headers.forEach(header => {
                    header.classList.remove('sort-asc', 'sort-desc');
                    const icon = header.querySelector('.sort-icon');
                    if (icon) {
                        icon.className = 'fas fa-sort sort-icon';
                    }
                });

                // Get current header
                const currentHeader = headers[columnIndex - 1];
                const currentIcon = currentHeader.querySelector('.sort-icon');

                // Toggle direction if same column
                if (currentSortColumn === columnIndex) {
                    sortDirection *= -1;
                } else {
                    currentSortColumn = columnIndex;
                    sortDirection = 1;
                }

                // Update icon
                if (sortDirection === 1) {
                    currentHeader.classList.add('sort-asc');
                    if (currentIcon) {
                        currentIcon.className = 'fas fa-sort-up sort-icon';
                    }
                } else {
                    currentHeader.classList.add('sort-desc');
                    if (currentIcon) {
                        currentIcon.className = 'fas fa-sort-down sort-icon';
                    }
                }

                // Get table data
                const table = document.getElementById('employeeDataTable');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                // Sort rows
                rows.sort((a, b) => {
                    const aCell = a.cells[columnIndex];
                    const bCell = b.cells[columnIndex];

                    let aValue = aCell ? aCell.textContent.trim() : '';
                    let bValue = bCell ? bCell.textContent.trim() : '';

                    // For numeric sorting in # column
                    if (columnIndex === 0) {
                        aValue = parseInt(aValue) || 0;
                        bValue = parseInt(bValue) || 0;
                    }

                    if (aValue < bValue) return -1 * sortDirection;
                    if (aValue > bValue) return 1 * sortDirection;
                    return 0;
                });

                // Reorder rows
                rows.forEach(row => tbody.appendChild(row));

                console.log(`Sorted column ${columnIndex} ${sortDirection === 1 ? 'ascending' : 'descending'}`);
            }

            // Search functionality
            document.getElementById('globalSearch')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#employeeDataTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // Clear search
            document.getElementById('clearSearch')?.addEventListener('click', function() {
                document.getElementById('globalSearch').value = '';
                window.location.href = "{{ route('employees.index') }}";
            });

            // Add animation for table rows
            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('#employeeDataTable tbody tr');
                rows.forEach((row, index) => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    }, index * 50);
                });

                // Make sure all icons are visible
                const allIcons = document.querySelectorAll('i');
                allIcons.forEach(icon => {
                    icon.style.visibility = 'visible';
                    icon.style.opacity = '1';
                });
            });
        </script>
    @endsection
</body>

</html>
