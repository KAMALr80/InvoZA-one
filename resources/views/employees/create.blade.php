@extends('layouts.app')

@section('content')
    {{-- ADMIN ONLY GUARD --}}
    @if (auth()->user()->role !== 'admin')
        <div style="background:#fff; padding:30px; border-radius:8px;">
            <h2 style="color:#dc2626;">Unauthorized</h2>
            <p>You do not have permission to add employees.</p>

            <a href="{{ route('employees.index') }}" style="color:#2563eb;">← Back to Employees</a>
        </div>
    @else
        <div style="max-width:520px; background:#fff; padding:30px; border-radius:8px;">

            <h2 style="margin-bottom:20px;">Add Employee</h2>

            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <label>Name</label>
                <input type="text" name="name" style="width:100%; padding:8px; margin-bottom:15px;" required>

                <label>Email</label>
                <input type="email" name="email" style="width:100%; padding:8px; margin-bottom:15px;" required>

                <label>Phone</label>
                <input type="text" name="phone" style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Department</label>
                <input type="text" name="department" style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Joining Date</label>
                <input type="date" name="joining_date" style="width:100%; padding:8px; margin-bottom:20px;">

                <button type="submit"
                    style="background:#111827; color:#fff; padding:10px 16px; border:none; border-radius:6px;">
                    Save
                </button>

                <a href="{{ route('employees.index') }}" style="margin-left:10px; color:#374151;">Cancel</a>
            </form>

        </div>
    @endif
@endsection
