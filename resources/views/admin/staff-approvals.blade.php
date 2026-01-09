@extends('layouts.app')

@section('content')
    <div class="report-box">

        <h2>✅ Staff Approval</h2>

        @if (session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        <table class="report-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staff as $s)
                    <tr>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.staff.approve', $s->id) }}">
                                @csrf
                                <button class="btn-primary">Approve</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
