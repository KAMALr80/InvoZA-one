@extends('layouts.app')

@section('content')
    <div class="page-box">

        <h2>👥 Pending Staff Approval</h2>

        @if (session('success'))
            <p style="color:green;">{{ session('success') }}</p>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.staff.approve', $user->id) }}">
                                @csrf
                                <button class="btn-primary">
                                    ✅ Approve
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No pending staff</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
