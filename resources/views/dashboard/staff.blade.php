@extends('layouts.app')
<style>
    <div style="
background:#fff;
padding:22px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
transition:.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
</style>
@section('content')

    <div style="max-width:1200px;margin:auto;">

        {{-- PAGE HEADER --}}
        <div style="margin-bottom:30px;">
            <h2 style="margin:0;font-size:28px;font-weight:800;color:#111827;">
                👨‍💼 Staff Dashboard
            </h2>
            <p style="margin-top:6px;color:#6b7280;">
                Your daily overview & activities
            </p>
        </div>

        {{-- ================= STAFF STATS ================= --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:22px;margin-bottom:40px;">

            {{-- MY ATTENDANCE --}}
            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Today's Attendance</div>
                <div style="font-size:32px;font-weight:800;">
                    {{ $myAttendanceStatus ?? 'N/A' }}
                </div>
            </div>

            {{-- PRESENT DAYS --}}
            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Present Days</div>
                <div style="font-size:34px;font-weight:800;">
                    {{ $presentCount ?? 0 }}
                </div>
            </div>

            {{-- ABSENT DAYS --}}
            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#dc2626,#991b1b);color:#fff;">
                <div style="font-size:14px;opacity:.9;">Absent Days</div>
                <div style="font-size:34px;font-weight:800;">
                    {{ $absentCount ?? 0 }}
                </div>
            </div>

            {{-- TOTAL LEAVES --}}
            <div style="padding:22px;border-radius:18px;background:linear-gradient(135deg,#f97316,#c2410c);color:#fff;">
                <div style="font-size:14px;opacity:.9;">My Leaves</div>
                <div style="font-size:34px;font-weight:800;">
                    {{ $leaveCount ?? 0 }}
                </div>
            </div>

        </div>

        {{-- ================= QUICK ACTIONS ================= --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:22px;margin-bottom:40px;">

            <a href="{{ route('attendance.my') }}" style="text-decoration:none;">
                <div
                    style="background:#fff;padding:22px;border-radius:18px;
                        box-shadow:0 10px 25px rgba(0,0,0,.08);
                        transition:.3s;">
                    <h3 style="margin:0;color:#2563eb;">🕒 My Attendance</h3>
                    <p style="margin-top:6px;color:#6b7280;font-size:14px;">
                        View check-in, check-out & history
                    </p>
                </div>
            </a>

            <a href="{{ route('leaves.my') }}" style="text-decoration:none;">
                <div
                    style="background:#fff;padding:22px;border-radius:18px;
                        box-shadow:0 10px 25px rgba(0,0,0,.08);
                        transition:.3s;">
                    <h3 style="margin:0;color:#16a34a;">📝 My Leaves</h3>
                    <p style="margin-top:6px;color:#6b7280;font-size:14px;">
                        Apply & track leave requests
                    </p>
                </div>
            </a>

            <a href="{{ route('sales.index') }}" style="text-decoration:none;">
                <div
                    style="background:#fff;padding:22px;border-radius:18px;
                        box-shadow:0 10px 25px rgba(0,0,0,.08);
                        transition:.3s;">
                    <h3 style="margin:0;color:#f97316;">💰 Sales</h3>
                    <p style="margin-top:6px;color:#6b7280;font-size:14px;">
                        Create & manage invoices
                    </p>
                </div>
            </a>

            <a href="{{ route('customers.index') }}" style="text-decoration:none;">
                <div
                    style="background:#fff;padding:22px;border-radius:18px;
                        box-shadow:0 10px 25px rgba(0,0,0,.08);
                        transition:.3s;">
                    <h3 style="margin:0;color:#7c3aed;">👤 Customers</h3>
                    <p style="margin-top:6px;color:#6b7280;font-size:14px;">
                        View & add customers
                    </p>
                </div>
            </a>

        </div>

        {{-- ================= RECENT ATTENDANCE ================= --}}
        <div
            style="background:#fff;padding:26px;border-radius:18px;
                box-shadow:0 12px 30px rgba(0,0,0,.08);">

            <h3 style="margin:0 0 15px;font-size:18px;font-weight:700;">
                📅 Recent Attendance
            </h3>

            @if (isset($recentAttendance) && $recentAttendance->count())
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:10px;text-align:left;">Date</th>
                            <th style="padding:10px;">Status</th>
                            <th style="padding:10px;">Check In</th>
                            <th style="padding:10px;">Check Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentAttendance as $a)
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <td style="padding:10px;">
                                    {{ \Carbon\Carbon::parse($a->attendance_date)->format('d M Y') }}
                                </td>
                                <td
                                    style="padding:10px;font-weight:600;
                                color:
                                {{ $a->status === 'Present' ? '#16a34a' : ($a->status === 'Absent' ? '#dc2626' : '#f97316') }}">
                                    {{ $a->status }}
                                </td>
                                <td style="padding:10px;">
                                    {{ $a->check_in ?? '-' }}
                                </td>
                                <td style="padding:10px;">
                                    {{ $a->check_out ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#6b7280;">
                    No attendance records found
                </p>
            @endif
        </div>

    </div>

@endsection
