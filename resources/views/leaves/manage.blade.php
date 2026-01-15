@extends('layouts.app')

@section('content')
    <div class="report-box"
        style="max-width:1000px;margin:30px auto;padding:25px;border:1px solid #ddd;border-radius:10px;
               background:#fdfdfd;box-shadow:0 4px 10px rgba(0,0,0,0.1);
               font-family:'Segoe UI',Arial,sans-serif;">

        <h2 style="color:#2c3e50;text-align:center;margin-bottom:25px;font-size:26px;">
            📋 Leave Requests
        </h2>

        <table class="report-table" style="width:100%;border-collapse:collapse;font-size:15px;">
            <thead>
                <tr style="background:#007bff;color:#fff;text-align:left;">
                    <th style="padding:12px;border:1px solid #ddd;">Employee</th>
                    <th style="padding:12px;border:1px solid #ddd;">Dates</th>
                    <th style="padding:12px;border:1px solid #ddd;">Type</th>
                    <th style="padding:12px;border:1px solid #ddd;">Reason</th>
                    <th style="padding:12px;border:1px solid #ddd;">Status</th>
                    <th style="padding:12px;border:1px solid #ddd;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($leaves as $l)
                    <tr style="background:#fff;transition:background 0.3s;" onmouseover="this.style.background='#f1f9ff'"
                        onmouseout="this.style.background='#fff'">

                        <td style="padding:12px;border:1px solid #ddd;font-weight:600;">
                            {{ $l->employee->name }}
                        </td>

                        <td style="padding:12px;border:1px solid #ddd;">
                            {{ $l->from_date }} → {{ $l->to_date }}
                        </td>

                        <td style="padding:12px;border:1px solid #ddd;">
                            {{ $l->type }}
                        </td>

                        <td
                            style="padding:12px;border:1px solid #ddd;max-width:220px;
                                   white-space:normal;word-break:break-word;color:#374151;">
                            {{ $l->reason ?? '-' }}
                        </td>

                        <td
                            style="padding:12px;border:1px solid #ddd;font-weight:600;
                                   color:
                                   {{ $l->status === 'Approved' ? '#16a34a' : ($l->status === 'Rejected' ? '#dc2626' : '#ca8a04') }}">
                            {{ $l->status }}
                        </td>

                        <td style="padding:12px;border:1px solid #ddd;">
                            @if ($l->status === 'Pending')
                                <form method="POST" action="{{ route('leaves.approve', $l->id) }}" style="display:inline">
                                    @csrf
                                    <button
                                        style="padding:8px 14px;background:#28a745;color:#fff;
                                               border:none;border-radius:5px;cursor:pointer;
                                               margin-right:5px;">
                                        ✔ Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('leaves.reject', $l->id) }}" style="display:inline">
                                    @csrf
                                    <button
                                        style="padding:8px 14px;background:#dc3545;color:#fff;
                                               border:none;border-radius:5px;cursor:pointer;">
                                        ✖ Reject
                                    </button>
                                </form>
                            @else
                                <span style="color:#6b7280;font-style:italic;">
                                    No action
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:15px;text-align:center;color:#6b7280;">
                            No leave requests found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
