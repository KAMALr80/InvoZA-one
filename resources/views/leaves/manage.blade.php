@extends('layouts.app')

@section('content')
    <div class="report-box"
        style="max-width:900px;margin:30px auto;padding:25px;border:1px solid #ddd;border-radius:10px;background:#fdfdfd;box-shadow:0 4px 10px rgba(0,0,0,0.1);font-family:'Segoe UI',Arial,sans-serif;">

        <h2 style="color:#2c3e50;text-align:center;margin-bottom:25px;font-size:26px;">📋 Leave Requests</h2>

        <table class="report-table" style="width:100%;border-collapse:collapse;font-size:15px;">
            <thead>
                <tr style="background:#007bff;color:#fff;text-align:left;">
                    <th style="padding:12px;border:1px solid #ddd;">Employee</th>
                    <th style="padding:12px;border:1px solid #ddd;">Dates</th>
                    <th style="padding:12px;border:1px solid #ddd;">Type</th>
                    <th style="padding:12px;border:1px solid #ddd;">Status</th>
                    <th style="padding:12px;border:1px solid #ddd;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $l)
                    <tr style="background:#fff;transition:background 0.3s;" onmouseover="this.style.background='#f1f9ff'"
                        onmouseout="this.style.background='#fff'">
                        <td style="padding:12px;border:1px solid #ddd;">{{ $l->employee->name }}</td>
                        <td style="padding:12px;border:1px solid #ddd;">{{ $l->from_date }} → {{ $l->to_date }}</td>
                        <td style="padding:12px;border:1px solid #ddd;">{{ $l->type }}</td>
                        <td style="padding:12px;border:1px solid #ddd;">{{ $l->status }}</td>
                        <td style="padding:12px;border:1px solid #ddd;">
                            @if ($l->status === 'Pending')
                                <form method="POST" action="{{ route('leaves.approve', $l->id) }}" style="display:inline">
                                    @csrf
                                    <button
                                        style="padding:8px 14px;background:#28a745;color:#fff;border:none;border-radius:5px;cursor:pointer;margin-right:5px;transition:0.3s;">Approve</button>
                                </form>

                                <form method="POST" action="{{ route('leaves.reject', $l->id) }}" style="display:inline">
                                    @csrf
                                    <button
                                        style="padding:8px 14px;background:#dc3545;color:#fff;border:none;border-radius:5px;cursor:pointer;transition:0.3s;">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
