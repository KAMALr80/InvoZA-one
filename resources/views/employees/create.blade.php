@extends('layouts.app')

@section('content')
    {{-- ADMIN ONLY GUARD --}}
    @if (auth()->user()->role !== 'admin')
        <div
            style="background:#1f2937; padding:30px; border-radius:12px; color:#f9fafb; box-shadow:0 6px 20px rgba(0,0,0,0.25); text-align:center;">
            <h2 style="color:#ef4444; font-size:24px; margin-bottom:10px;">🚫 Unauthorized</h2>
            <p style="color:#9ca3af; font-size:16px; margin-bottom:15px;">You do not have permission to add employees.</p>

            <a href="{{ route('employees.index') }}"
                style="color:#3b82f6; font-weight:600; text-decoration:none; transition:0.3s;"
                onmouseover="this.style.color='#1e40af'" onmouseout="this.style.color='#3b82f6'">
                ← Back to Employees
            </a>
        </div>
    @else
        <div
            style="max-width:520px; background:#111827; padding:30px; border-radius:12px; color:#f9fafb; box-shadow:0 6px 20px rgba(0,0,0,0.25);">
            <h2 style="margin-bottom:20px; font-size:26px; font-weight:bold; color:#facc15;">➕ Add Employee</h2>

            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <label style="display:block; margin-bottom:6px; font-weight:600;">Name</label>
                <input type="text" name="name" required
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <label style="display:block; margin-bottom:6px; font-weight:600;">Email</label>
                <input type="email" name="email" required
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">


                <label style="display:block; margin-bottom:6px; font-weight:600;">Password</label>
                <input type="password" name="password" required
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">


                <label style="display:block; margin-bottom:6px; font-weight:600;">Phone</label>
                <input type="text" name="phone"
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <label style="display:block; margin-bottom:6px; font-weight:600;">Department</label>
                <input type="text" name="department"
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <label style="display:block; margin-bottom:6px; font-weight:600;">Joining Date</label>
                <input type="date" name="joining_date"
                    style="width:100%; padding:10px; margin-bottom:20px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <button type="submit"
                    style="background:linear-gradient(90deg,#2563eb,#1e40af); color:#fff; padding:12px 18px; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:0.3s;"
                    onmouseover="this.style.background='linear-gradient(90deg,#1e40af,#2563eb)'"
                    onmouseout="this.style.background='linear-gradient(90deg,#2563eb,#1e40af)'">
                    💾 Save
                </button>

                <a href="{{ route('employees.index') }}"
                    style="margin-left:12px; color:#9ca3af; font-weight:600; text-decoration:none; transition:0.3s;"
                    onmouseover="this.style.color='#6b7280'" onmouseout="this.style.color='#9ca3af'">
                    ❌ Cancel
                </a>
            </form>
        </div>
    @endif
@endsection
