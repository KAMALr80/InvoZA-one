@extends('layouts.app')

@section('content')
    {{-- ADMIN + HR ONLY --}}
    @if (!in_array(auth()->user()->role, ['admin', 'hr']))
        <div style="background:#fff; padding:30px; border-radius:8px;">
            <h2 style="color:#dc2626;">Unauthorized</h2>
            <p>You do not have permission to edit employee details.</p>

            <a href="{{ route('employees.index') }}" style="color:#2563eb;">← Back to Employees</a>
        </div>
    @else
        <div style="max-width:520px; background:#fff; padding:30px; border-radius:8px;">

            <h2 style="margin-bottom:20px;">Edit Employee</h2>

            <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                @csrf
                @method('PUT')

                <label>Name</label>
                <input type="text" name="name" value="{{ $employee->name }}"
                    style="width:100%; padding:8px; margin-bottom:15px;" required>

                <label>Email</label>
                <input type="email" name="email" value="{{ $employee->email }}"
                    style="width:100%; padding:8px; margin-bottom:15px;" required>

                <label>Phone</label>
                <input type="text" name="phone" value="{{ $employee->phone }}"
                    style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Department</label>
                <input type="text" name="department" value="{{ $employee->department }}"
                    style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Joining Date</label>
                <input type="date" name="joining_date" value="{{ $employee->joining_date }}"
                    style="width:100%; padding:8px; margin-bottom:20px;">

                <button type="submit"
                    style="background:#111827; color:#fff; padding:10px 16px; border:none; border-radius:6px;">
                    Update
                </button>

                <a href="{{ route('employees.index') }}" style="margin-left:10px; color:#374151;">Cancel</a>
            </form>

        </div>
    @endif
@endsection
