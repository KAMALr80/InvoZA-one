@extends('layouts.app')

@section('content')
    <div class="report-box"
        style="max-width:800px;margin:30px auto;padding:20px;border:1px solid #ddd;border-radius:8px;background:#f9f9f9;font-family:Arial, sans-serif;">

        <h2 style="color:#333;text-align:center;margin-bottom:20px;">📝 Apply Leave</h2>

        @if (session('success'))
            <p style="color:green;font-weight:bold;text-align:center;">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('leaves.apply') }}" class="report-filter"
            style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:25px;">
            @csrf
            <input type="date" name="from_date" required style="padding:8px;border:1px solid #ccc;border-radius:4px;">
            <input type="date" name="to_date" required style="padding:8px;border:1px solid #ccc;border-radius:4px;">
            <select name="type" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
                <option>Casual</option>
                <option>Sick</option>
                <option>Paid</option>
            </select>
            <input type="text" name="reason" placeholder="Reason"
                style="padding:8px;border:1px solid #ccc;border-radius:4px;flex:1;">
            <button type="submit"
                style="padding:10px 20px;background:#007bff;color:#fff;border:none;border-radius:4px;cursor:pointer;">Apply</button>
        </form>

        <h3 style="color:#444;margin-bottom:15px;text-align:center;">📜 My Leaves</h3>

        <table class="report-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#007bff;color:#fff;">
                    <th style="padding:10px;border:1px solid #ddd;">From</th>
                    <th style="padding:10px;border:1px solid #ddd;">To</th>
                    <th style="padding:10px;border:1px solid #ddd;">Type</th>
                    <th style="padding:10px;border:1px solid #ddd;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $l)
                    <tr style="background:#fff;">
                        <td style="padding:10px;border:1px solid #ddd;">{{ $l->from_date }}</td>
                        <td style="padding:10px;border:1px solid #ddd;">{{ $l->to_date }}</td>
                        <td style="padding:10px;border:1px solid #ddd;">{{ $l->type }}</td>
                        <td style="padding:10px;border:1px solid #ddd;">{{ $l->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
