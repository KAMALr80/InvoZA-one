@extends('layouts.app')

@section('content')
    <div style="background:#1f2937; padding:30px; border-radius:12px; width:100%; color:#f9fafb; font-family:'Segoe UI', Arial, sans-serif; box-shadow:0 6px 20px rgba(0,0,0,0.25);">

        {{-- HEADER --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0; font-size:28px; font-weight:bold; color:#facc15;">👥 Employees</h2>

            {{-- ADD EMPLOYEE – ADMIN ONLY --}}
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('employees.create') }}"
                    style="background:#10b981; color:#fff; padding:10px 18px; border-radius:8px; text-decoration:none; font-weight:600; box-shadow:0 3px 6px rgba(0,0,0,0.2); transition:0.3s;"
                    onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                    + Add Employee
                </a>
            @endif
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <p style="color:#22c55e; margin-bottom:15px; font-weight:bold;">
                {{ session('success') }}
            </p>
        @endif

        {{-- SEARCH --}}
        <form method="GET" style="margin-bottom:20px; display:flex; gap:10px;">
            <input type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}"
                style="padding:10px; width:280px; border-radius:6px; border:1px solid #374151; background:#111827; color:#f9fafb;">
            <button style="padding:10px 16px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:600; transition:0.3s;"
                onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#2563eb'">
                Search
            </button>
        </form>

        {{-- TABLE --}}
        <table style="width:100%; border-collapse:collapse; background:#111827; border-radius:8px; overflow:hidden;">
            <thead>
                <tr style="background:#374151; color:#facc15; text-align:left; font-size:15px;">
                    <th style="padding:12px;">Code</th>
                    <th style="padding:12px;">Name</th>
                    <th style="padding:12px;">Email</th>
                    <th style="padding:12px;">Department</th>
                    <th style="padding:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $emp)
                    <tr style="border-bottom:1px solid #4b5563; transition:0.3s;"
                        onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#111827'">
                        <td style="padding:12px; color:#f9fafb;">{{ $emp->employee_code }}</td>
                        <td style="padding:12px; color:#f9fafb;">{{ $emp->name }}</td>
                        <td style="padding:12px; color:#f9fafb;">{{ $emp->email }}</td>
                        <td style="padding:12px; color:#f9fafb;">{{ $emp->department }}</td>

                        <td style="padding:12px;">
                            {{-- EDIT – ADMIN + HR --}}
                            @if (in_array(auth()->user()->role, ['admin', 'hr']))
                                <a href="{{ route('employees.edit', $emp->id) }}"
                                    style="margin-right:12px; color:#3b82f6; font-weight:600; text-decoration:none; transition:0.3s;"
                                    onmouseover="this.style.color='#1e40af'" onmouseout="this.style.color='#3b82f6'">
                                    ✏️ Edit
                                </a>
                            @endif

                            {{-- DELETE – ADMIN ONLY --}}
                            @if (auth()->user()->role === 'admin')
                                <form action="{{ route('employees.destroy', $emp->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background:#dc2626; border:none; color:#fff; padding:6px 12px; border-radius:6px; cursor:pointer; font-weight:600; transition:0.3s;"
                                        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'"
                                        onclick="return confirm('Delete employee?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            @endif

                            {{-- STAFF VIEW --}}
                            @if (auth()->user()->role === 'staff')
                                <span style="color:#9ca3af; font-style:italic;">View only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:12px; text-align:center; color:#9ca3af;">No employees found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div style="margin-top:25px; text-align:center;">
            {{ $employees->links() }}
        </div>

    </div>
@endsection
