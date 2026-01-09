@extends('layouts.app')

@section('content')
    <div style="background:#fff; padding:25px; border-radius:8px; width:100%;">

        {{-- HEADER --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h2 style="margin:0;">Employees</h2>

            {{-- ADD EMPLOYEE – ADMIN ONLY --}}
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('employees.create') }}"
                    style="background:#111827; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none;">
                    + Add Employee
                </a>
            @endif
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <p style="color:green; margin-bottom:10px;">
                {{ session('success') }}
            </p>
        @endif

        {{-- SEARCH --}}
        <form method="GET" style="margin-bottom:15px;">
            <input type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}"
                style="padding:8px; width:260px;">
            <button style="padding:8px;">Search</button>
        </form>

        {{-- TABLE --}}
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:10px;">Code</th>
                    <th style="padding:10px;">Name</th>
                    <th style="padding:10px;">Email</th>
                    <th style="padding:10px;">Department</th>
                    <th style="padding:10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $emp)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:10px;">{{ $emp->employee_code }}</td>
                        <td style="padding:10px;">{{ $emp->name }}</td>
                        <td style="padding:10px;">{{ $emp->email }}</td>
                        <td style="padding:10px;">{{ $emp->department }}</td>

                        <td style="padding:10px;">
                            {{-- EDIT – ADMIN + HR --}}
                            @if (in_array(auth()->user()->role, ['admin', 'hr']))
                                <a href="{{ route('employees.edit', $emp->id) }}" style="margin-right:10px; color:#2563eb;">
                                    Edit
                                </a>
                            @endif

                            {{-- DELETE – ADMIN ONLY --}}
                            @if (auth()->user()->role === 'admin')
                                <form action="{{ route('employees.destroy', $emp->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background:none; border:none; color:#dc2626; cursor:pointer;"
                                        onclick="return confirm('Delete employee?')">
                                        Delete
                                    </button>
                                </form>
                            @endif

                            {{-- STAFF VIEW --}}
                            @if (auth()->user()->role === 'staff')
                                <span style="color:#6b7280;">View only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:10px;">No employees found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div style="margin-top:20px;">
            {{ $employees->links() }}
        </div>

    </div>
@endsection
