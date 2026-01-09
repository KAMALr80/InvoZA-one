@extends('layouts.app')

@section('content')
    <div class="report-box" style="padding:20px; border:1px solid #ccc; border-radius:8px; background:#f9f9f9;">

        <h2 style="color:#2e7d32; font-family:Arial, sans-serif;">✅ Staff Approval</h2>

        @if (session('success'))
            <p style="color:green; font-weight:bold; margin-bottom:15px;">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p style="color:red; font-weight:bold; margin-bottom:15px;">
                {{ session('error') }}
            </p>
        @endif

        <table class="report-table" style="width:100%; border-collapse:collapse; margin-top:15px;">
            <thead>
                <tr style="background:#e0e0e0; text-align:left;">
                    <th style="padding:10px; border:1px solid #ccc;">Name</th>
                    <th style="padding:10px; border:1px solid #ccc;">Email</th>
                    <th style="padding:10px; border:1px solid #ccc;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staff as $s)
                    <tr>
                        <td style="padding:10px; border:1px solid #ccc;">{{ $s->name }}</td>
                        <td style="padding:10px; border:1px solid #ccc;">{{ $s->email }}</td>
                        <td style="padding:10px; border:1px solid #ccc;">

                            {{-- ✅ BUTTON LOGIC --}}
                            @if ($s->status === 'approved')
                                <button
                                    style="background:#2e7d32; color:#fff; border:none; padding:8px 12px; border-radius:4px;"
                                    disabled>
                                    ✔ Approved
                                </button>
                            @else
                                <form method="POST" action="{{ route('admin.staff.approve', $s->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button onclick="return confirm('Approve this staff?')"
                                        style="background:#1976d2; color:#fff; border:none; padding:8px 12px; border-radius:4px; cursor:pointer;">
                                        Approve
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding:15px; text-align:center; color:#777;">
                            No pending staff found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
