@extends('layouts.app')

@section('content')
    {{-- ADMIN + HR ONLY --}}
    @if (!in_array(auth()->user()->role, ['admin', 'hr']))
        <div style="background:#1f2937; padding:30px; border-radius:12px; color:#f9fafb; text-align:center;">
            <h2 style="color:#ef4444;">🚫 Unauthorized</h2>
            <p>You do not have permission to edit employee details.</p>

            <a href="{{ route('employees.index') }}" style="color:#3b82f6; font-weight:600;">
                ← Back to Employees
            </a>
        </div>
    @else
        <div style="max-width:520px; background:#111827; padding:30px; border-radius:12px; color:#f9fafb;">
            <h2 style="margin-bottom:20px; color:#facc15;">✏️ Edit Employee</h2>

            <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                @csrf
                @method('PUT')

                {{-- NAME --}}
                <label>Name</label>
                <input type="text" name="name" value="{{ $employee->name }}" required
                    style="width:100%; padding:10px; margin-bottom:12px; background:#1f2937; color:#fff; border:1px solid #374151; border-radius:6px;">

                {{-- EMAIL --}}
                <label>Email</label>
                <input type="email" name="email" value="{{ $employee->email }}" required
                    style="width:100%; padding:10px; margin-bottom:12px; background:#1f2937; color:#fff; border:1px solid #374151; border-radius:6px;">

                {{-- PASSWORD --}}
                <label>Password <small style="color:#9ca3af;">(leave blank to keep same)</small></label>
                <input type="password" name="password"
                    style="width:100%; padding:10px; margin-bottom:12px; background:#1f2937; color:#fff; border:1px solid #374151; border-radius:6px;">

                {{-- PHONE --}}
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $employee->phone }}"
                    style="width:100%; padding:10px; margin-bottom:12px; background:#1f2937; color:#fff; border:1px solid #374151; border-radius:6px;">

                {{-- DEPARTMENT --}}
                <label>Department</label>
                <input type="text" name="department" value="{{ $employee->department }}"
                    style="width:100%; padding:10px; margin-bottom:12px; background:#1f2937; color:#fff; border:1px solid #374151; border-radius:6px;">

                {{-- JOINING DATE --}}
                <label>Joining Date</label>
                <input type="date" name="joining_date" value="{{ $employee->joining_date }}"
                    style="width:100%; padding:10px; margin-bottom:20px; background:#1f2937; color:#fff; border:1px solid #374151; border-radius:6px;">

                {{-- BUTTONS --}}
                <button type="submit"
                    style="background:#2563eb; color:#fff; padding:10px 18px; border:none; border-radius:6px; font-weight:600;">
                    ✅ Update
                </button>

                <a href="{{ route('employees.index') }}" style="margin-left:12px; color:#9ca3af; text-decoration:none;">
                    ❌ Cancel
                </a>
            </form>
        </div>
    @endif
@endsection
