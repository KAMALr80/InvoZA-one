@extends('layouts.app')

@section('page-title', 'Employee Report')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="color: #1f2937; font-size: 28px; font-weight: 700; margin-bottom: 5px;">
            <i class="fas fa-user-clock"></i> Employee Report
        </h1>
        <p style="color: #6b7280; font-size: 14px;">Monitor employee data, performance, and HR metrics</p>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <!-- Total Employees Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #6366f1;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Total Employees</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        {{ $totalEmployees ?? 0 }}
                    </h3>
                </div>
                <div style="background: #eef2ff; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    👥
                </div>
            </div>
        </div>

        <!-- Active Employees Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Active Employees</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        {{ $activeEmployees ?? 0 }}
                    </h3>
                </div>
                <div style="background: #ecfdf5; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    ✅
                </div>
            </div>
        </div>

        <!-- Total Departments Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Departments</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        {{ count($departments ?? []) }}
                    </h3>
                </div>
                <div style="background: #eff6ff; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    🏢
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Export -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div>
                <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Department</label>
                <select style="padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <option>All Departments</option>
                    @foreach($departments ?? [] as $dept)
                        <option>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Status</label>
                <select style="padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <option>All</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>On Leave</option>
                </select>
            </div>
            <button style="background: #6366f1; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; margin-top: 23px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('reports.employees.excel') }}" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 23px; display: inline-block;">
                <i class="fas fa-download"></i> Export Excel
            </a>
            <a href="{{ route('reports.employees.pdf') }}" style="background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 23px; display: inline-block;">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Department Breakdown -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="color: #1f2937; font-weight: 600; margin-bottom: 20px;">
            <i class="fas fa-chart-pie"></i> Employees by Department
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
            @foreach($departments ?? [] as $dept)
                <div style="padding: 15px; background: #f9fafb; border-radius: 8px; border-left: 3px solid #6366f1;">
                    <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">{{ $dept }}</p>
                    <p style="color: #1f2937; font-weight: 700; font-size: 18px; margin: 0;">
                        {{ $employees->where('department', $dept)->count() }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Employees Table -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Employee Name</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Employee ID</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Department</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Position</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Join Date</th>
                    <th style="padding: 15px; text-align: center; font-size: 13px; font-weight: 600; color: #374151;">Status</th>
                    <th style="padding: 15px; text-align: center; font-size: 13px; font-weight: 600; color: #374151;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees ?? [] as $employee)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.2s;">
                        <td style="padding: 15px; color: #1f2937; font-weight: 500;">{{ $employee->name ?? 'N/A' }}</td>
                        <td style="padding: 15px; color: #6b7280; font-size: 13px;">{{ $employee->employee_id ?? 'N/A' }}</td>
                        <td style="padding: 15px; color: #6b7280;">{{ $employee->department ?? 'N/A' }}</td>
                        <td style="padding: 15px; color: #6b7280;">{{ $employee->position ?? 'N/A' }}</td>
                        <td style="padding: 15px; color: #6b7280;">{{ $employee->joining_date ? $employee->joining_date->format('d M Y') : 'N/A' }}</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: {{ $employee->deleted_at ? '#fee2e2' : '#ecfdf5' }}; color: {{ $employee->deleted_at ? '#991b1b' : '#065f46' }}; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                {{ $employee->deleted_at ? 'Inactive' : 'Active' }}
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="#" style="color: #6366f1; text-decoration: none; font-size: 14px;">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                            <p>No employee data available</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer Stats -->
    <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <p style="color: #6b7280; font-size: 13px; margin: 0;">
            <strong>Report Generated:</strong> {{ now()->format('d M Y, H:i A') }} |
            <strong>Total Employees:</strong> {{ $totalEmployees ?? 0 }} |
            <strong>Active:</strong> {{ $activeEmployees ?? 0 }} |
            <strong>Departments:</strong> {{ count($departments ?? []) }}
        </p>
    </div>
</div>

<style>
    tr:hover {
        background: #f9fafb !important;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>
@endsection
