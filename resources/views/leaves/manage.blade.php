@extends('layouts.app')

@section('content')
    <div class="report-box">

        <h2>📋 Leave Requests</h2>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Dates</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $l)
                    <tr>
                        <td>{{ $l->employee->name }}</td>
                        <td>{{ $l->from_date }} → {{ $l->to_date }}</td>
                        <td>{{ $l->type }}</td>
                        <td>{{ $l->status }}</td>
                        <td>
                            @if ($l->status === 'Pending')
                                <form method="POST" action="{{ route('leaves.approve', $l->id) }}" style="display:inline">
                                    @csrf
                                    <button>Approve</button>
                                </form>

                                <form method="POST" action="{{ route('leaves.reject', $l->id) }}" style="display:inline">
                                    @csrf
                                    <button>Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
