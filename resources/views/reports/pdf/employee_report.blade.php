<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Report - {{ $company_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1f2937;
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }

        .company-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: #2563eb;
            margin-top: 5px;
        }

        .report-meta {
            color: #64748b;
            font-size: 9px;
            margin-top: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .stat-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .stat-value.positive {
            color: #10b981;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: 600;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 15px;
        }

        th {
            background: #f1f5f9;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #64748b;
        }

        .employee-code {
            font-family: monospace;
            font-weight: 600;
            color: #2563eb;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-title">{{ $company_name }}</div>
        <div class="report-title">EMPLOYEE REPORT</div>
        <div class="report-meta">
            Generated on: {{ $generated_date }} |
            Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} |
            Total Employees: {{ $employees->count() }}
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Employees</div>
            <div class="stat-value">{{ number_format($stats['total_employees']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Active</div>
            <div class="stat-value positive">{{ number_format($stats['active_employees']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Active Rate</div>
            <div class="stat-value">{{ $stats['active_rate'] }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Departments</div>
            <div class="stat-value">{{ number_format($stats['department_count']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">New Hires</div>
            <div class="stat-value">{{ number_format($stats['new_hires']) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Joining Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $index => $employee)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><span class="employee-code">{{ $employee->employee_code }}</span></td>
                    <td><strong>{{ $employee->name }}</strong></td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->department ?? 'Not Assigned' }}</td>
                    <td>{{ $employee->user ? ucfirst($employee->user->role) : 'Staff' }}</td>
                    <td>{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : 'N/A' }}
                    </td>
                    <td><span
                            class="badge {{ $employee->status == 1 ? 'badge-active' : 'badge-inactive' }}">{{ $employee->status == 1 ? 'Active' : 'Inactive' }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9; font-weight:bold;">
                <td colspan="8" class="text-right">Total: {{ $employees->count() }} employees</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>This is a computer-generated report. No signature required.</div>
        <div>{{ strtoupper($company_name) }} - Employee Report | Page 1 of 1</div>
    </div>
</body>

</html>
