@extends('layouts.app')

@section('content')
    <div class="report-box">

        <h2>📝 Apply Leave</h2>

        @if (session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('leaves.apply') }}" class="report-filter">
            @csrf
            <input type="date" name="from_date" required>
            <input type="date" name="to_date" required>
            <select name="type">
                <option>Casual</option>
                <option>Sick</option>
                <option>Paid</option>
            </select>
            <input type="text" name="reason" placeholder="Reason">
            <button type="submit">Apply</button>
        </form>

        <h3>📜 My Leaves</h3>

        <table class="report-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $l)
                    <tr>
                        <td>{{ $l->from_date }}</td>
                        <td>{{ $l->to_date }}</td>
                        <td>{{ $l->type }}</td>
                        <td>{{ $l->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
