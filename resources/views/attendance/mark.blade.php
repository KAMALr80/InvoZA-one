@extends('layouts.app')

@section('page-title', 'Mark Attendance')

@section('content')
    <div style="max-width:1200px; margin:0 auto;">
        <!-- Page Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
            <div>
                <h1 style="margin:0; font-size:28px; color:#1f2937; font-weight:800;">📝 Mark Attendance</h1>
                <p style="margin:8px 0 0 0; color:#6b7280; font-size:15px;">
                    {{ now()->format('l, F j, Y') }} • Total Employees: {{ $employees->count() }}
                </p>
            </div>
            <div style="display:flex; gap:10px;">
                <button onclick="window.location.href='{{ route('attendance.manage') }}'"
                    style="
                background:#6366f1;
                color:white;
                border:none;
                padding:10px 20px;
                border-radius:10px;
                font-weight:600;
                cursor:pointer;
                display:flex;
                align-items:center;
                gap:8px;
                font-size:14px;
            ">
                    📋 View All
                </button>
                <button onclick="submitBulkAttendance()"
                    style="
                background:#10b981;
                color:white;
                border:none;
                padding:10px 20px;
                border-radius:10px;
                font-weight:600;
                cursor:pointer;
                display:flex;
                align-items:center;
                gap:8px;
                font-size:14px;
            ">
                    💾 Save All
                </button>
            </div>
        </div>

        <!-- Stats Summary -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-bottom:30px;">
            <div style="background:#d1fae5; border:1px solid #a7f3d0; border-radius:12px; padding:20px;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div
                        style="width:50px; height:50px; border-radius:12px; background:#10b981; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                        ✅
                    </div>
                    <div>
                        <div style="font-size:12px; color:#065f46; margin-bottom:5px;">Present Today</div>
                        <div style="font-size:28px; font-weight:800; color:#065f46;">{{ $attendanceCounts['present'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div style="background:#fee2e2; border:1px solid #fecaca; border-radius:12px; padding:20px;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div
                        style="width:50px; height:50px; border-radius:12px; background:#ef4444; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                        ❌
                    </div>
                    <div>
                        <div style="font-size:12px; color:#991b1b; margin-bottom:5px;">Absent Today</div>
                        <div style="font-size:28px; font-weight:800; color:#991b1b;">{{ $attendanceCounts['absent'] }}</div>
                    </div>
                </div>
            </div>

            <div style="background:#fef3c7; border:1px solid #fde68a; border-radius:12px; padding:20px;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div
                        style="width:50px; height:50px; border-radius:12px; background:#f59e0b; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                        ⏰
                    </div>
                    <div>
                        <div style="font-size:12px; color:#92400e; margin-bottom:5px;">Late Today</div>
                        <div style="font-size:28px; font-weight:800; color:#92400e;">{{ $attendanceCounts['late'] }}</div>
                    </div>
                </div>
            </div>

            <div style="background:#e0f2fe; border:1px solid #bae6fd; border-radius:12px; padding:20px;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div
                        style="width:50px; height:50px; border-radius:12px; background:#0ea5e9; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                        ⏳
                    </div>
                    <div>
                        <div style="font-size:12px; color:#0369a1; margin-bottom:5px;">Pending</div>
                        <div style="font-size:28px; font-weight:800; color:#0369a1;">{{ $attendanceCounts['pending'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Form -->
        <div
            style="background:white; border-radius:16px; padding:25px; box-shadow:0 4px 20px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
            <h2 style="margin:0 0 20px 0; font-size:20px; color:#1f2937; font-weight:700;">Employees Attendance</h2>

            <form id="bulkAttendanceForm">
                @csrf
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom:2px solid #e5e7eb;">
                                <th
                                    style="text-align:left; padding:15px 0; color:#6b7280; font-weight:600; font-size:13px; width:40px;">
                                    #</th>
                                <th
                                    style="text-align:left; padding:15px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                    Employee</th>
                                <th
                                    style="text-align:left; padding:15px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                    Department</th>
                                <th
                                    style="text-align:left; padding:15px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                    Current Status</th>
                                <th
                                    style="text-align:left; padding:15px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                    Mark Attendance</th>
                                <th
                                    style="text-align:left; padding:15px 0; color:#6b7280; font-weight:600; font-size:13px;">
                                    Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $index => $employee)
                                @php
                                    $todayAttendance = $todayAttendance[$employee->id] ?? null;
                                    $currentStatus = $todayAttendance ? $todayAttendance->status : 'Not Marked';
                                    $currentColor =
                                        $currentStatus == 'Present'
                                            ? '#10b981'
                                            : ($currentStatus == 'Absent'
                                                ? '#ef4444'
                                                : ($currentStatus == 'Late'
                                                    ? '#f59e0b'
                                                    : '#6b7280'));
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td style="padding:15px 0; color:#6b7280;">{{ $index + 1 }}</td>
                                    <td style="padding:15px 0;">
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <div
                                                style="width:40px; height:40px; border-radius:10px; background:#6366f1; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:16px;">
                                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:600; color:#1f2937;">{{ $employee->name }}</div>
                                                <div style="font-size:12px; color:#6b7280;">ID:
                                                    {{ $employee->employee_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:15px 0; color:#6b7280;">
                                        <span
                                            style="background:#e5e7eb; color:#374151; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                                            {{ $employee->department ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding:15px 0;">
                                        <span
                                            style="background:{{ $currentColor }}20; color:{{ $currentColor }}; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                            {{ $currentStatus }}
                                        </span>
                                    </td>
                                    <td style="padding:15px 0;">
                                        <select name="attendances[{{ $index }}][status]"
                                            style="
                                    width:100%;
                                    padding:10px 12px;
                                    border-radius:8px;
                                    border:1px solid #d1d5db;
                                    background:white;
                                    font-size:14px;
                                    color:#374151;
                                    cursor:pointer;
                                "
                                            onchange="updateStatusColor(this)">
                                            <option value="present" {{ $currentStatus == 'Present' ? 'selected' : '' }}>✅
                                                Present</option>
                                            <option value="absent" {{ $currentStatus == 'Absent' ? 'selected' : '' }}>❌
                                                Absent</option>
                                            <option value="late" {{ $currentStatus == 'Late' ? 'selected' : '' }}>⏰ Late
                                            </option>
                                            <option value="leave" {{ $currentStatus == 'Leave' ? 'selected' : '' }}>🏖️
                                                Leave</option>
                                        </select>
                                        <input type="hidden" name="attendances[{{ $index }}][employee_id]"
                                            value="{{ $employee->id }}">
                                    </td>
                                    <td style="padding:15px 0;">
                                        <input type="text" name="attendances[{{ $index }}][remarks]"
                                            placeholder="Optional remarks"
                                            style="
                                    width:100%;
                                    padding:10px 12px;
                                    border-radius:8px;
                                    border:1px solid #d1d5db;
                                    font-size:14px;
                                    color:#374151;
                                "
                                            value="{{ $todayAttendance->remarks ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <script>
        function submitBulkAttendance() {
            const form = document.getElementById('bulkAttendanceForm');
            const formData = new FormData(form);

            // Convert FormData to JSON
            const data = {};
            const attendances = [];

            formData.forEach((value, key) => {
                if (key.includes('attendances')) {
                    const match = key.match(/attendances\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const index = match[1];
                        const field = match[2];

                        if (!attendances[index]) {
                            attendances[index] = {};
                        }
                        attendances[index][field] = value;
                    }
                }
            });

            data.attendances = attendances.filter(item => item);
            data._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Show loading
            const saveBtn = document.querySelector('button[onclick="submitBulkAttendance()"]');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '⏳ Saving...';
            saveBtn.disabled = true;

            // Submit via AJAX
            fetch('{{ route('attendance.bulk') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('✅ Attendance marked successfully for ' + result.count + ' employees');
                        window.location.reload();
                    } else {
                        alert('❌ Error: ' + (result.message || 'Failed to save attendance'));
                    }
                })
                .catch(error => {
                    alert('❌ Network error: ' + error.message);
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
        }

        function updateStatusColor(select) {
            const colors = {
                'present': '#10b981',
                'absent': '#ef4444',
                'late': '#f59e0b',
                'leave': '#8b5cf6'
            };

            select.style.borderColor = colors[select.value];
            select.style.backgroundColor = colors[select.value] + '20';
            select.style.color = colors[select.value];
        }
    </script>

    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))"] {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
@endsection
