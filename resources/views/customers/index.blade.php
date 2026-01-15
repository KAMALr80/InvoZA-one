@extends('layouts.app')

@section('content')
    <div class="report-box"
        style="max-width:900px; margin:40px auto; padding:25px; border-radius:10px;
           background:#f9f9f9; box-shadow:0 6px 15px rgba(0,0,0,0.1);">

        <h2 style="color:#1976d2; font-family:'Segoe UI', sans-serif; margin-bottom:20px; text-align:center;">
            👤 Customers
        </h2>

        <a href="{{ route('customers.create') }}"
            style="display:inline-block; margin-bottom:15px; background:#4CAF50; color:#fff;
              padding:8px 14px; border-radius:6px; text-decoration:none; font-weight:bold;">
            ➕ Add Customer
        </a>

        @if (session('success'))
            <p style="color:green; font-weight:bold; margin-bottom:15px;">
                {{ session('success') }}
            </p>
        @endif

        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#1976d2; color:#fff;">
                    <th style="padding:10px;border:1px solid #ccc;">Name</th>
                    <th style="padding:10px;border:1px solid #ccc;">Mobile</th>
                    <th style="padding:10px;border:1px solid #ccc;">Email</th>
                    <th style="padding:10px;border:1px solid #ccc;">GST</th>
                    <th style="padding:10px;border:1px solid #ccc; text-align:center;">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($customers as $c)
                    <tr style="background:#fff;">
                        <td style="padding:10px;border:1px solid #ccc;">{{ $c->name }}</td>
                        <td style="padding:10px;border:1px solid #ccc;">{{ $c->mobile }}</td>
                        <td style="padding:10px;border:1px solid #ccc;">{{ $c->email }}</td>
                        <td style="padding:10px;border:1px solid #ccc;">{{ $c->gst_no }}</td>

                        <td style="padding:10px;border:1px solid #ccc; text-align:center;">

                            {{-- 👁 VIEW SALES --}}
                            <a href="{{ route('customers.sales', $c->id) }}" title="View Purchases"
                                style="margin-right:10px; text-decoration:none; font-size:16px;">
                                👁
                            </a>

                            {{-- ✏️ EDIT --}}
                            <a href="{{ route('customers.edit', $c->id) }}" title="Edit"
                                style="margin-right:10px; text-decoration:none; font-size:16px;">
                                ✏️
                            </a>

                            {{-- 🗑 DELETE --}}
                            <form action="{{ route('customers.destroy', $c->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this customer?')"
                                    style="background:none;border:none;cursor:pointer;font-size:16px;">
                                    🗑
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:20px; text-align:center;">
            {{ $customers->links() }}
        </div>

    </div>
@endsection
