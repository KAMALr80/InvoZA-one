@extends('layouts.app')

@section('content')

    @if (auth()->user()->role === 'staff')
        <div style="background:#fff; padding:30px; border-radius:8px;">
            <h2 style="color:#dc2626;">Unauthorized</h2>
            <p>You do not have permission to access inventory.</p>
        </div>
    @else
        <div style="background:#fff; padding:25px; border-radius:8px; width:100%;">

            {{-- HEADER --}}
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h2 style="margin:0;">Inventory</h2>

                {{-- ADD PRODUCT – ADMIN ONLY --}}
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('inventory.create') }}"
                        style="background:#111827; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none;">
                        + Add Product
                    </a>
                @endif
            </div>

            {{-- TABLE --}}
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th style="padding:10px;">Code</th>
                        <th style="padding:10px;">Name</th>
                        <th style="padding:10px;">Qty</th>
                        <th style="padding:10px;">Price</th>
                        <th style="padding:10px;">Category</th>
                        <th style="padding:10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr style="border-bottom:1px solid #e5e7eb;">
                            <td style="padding:10px;">{{ $p->product_code }}</td>
                            <td style="padding:10px;">{{ $p->name }}</td>
                            <td style="padding:10px;">{{ $p->quantity }}</td>
                            <td style="padding:10px;">₹ {{ $p->price }}</td>
                            <td style="padding:10px;">{{ $p->category }}</td>
                            <td style="padding:10px;">
                                {{-- ADMIN ONLY ACTIONS --}}
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('inventory.edit', $p->id) }}"
                                        style="margin-right:10px; color:#2563eb;">Edit</a>

                                    <form method="POST" action="{{ route('inventory.destroy', $p->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button style="background:none; border:none; color:#dc2626;"
                                            onclick="return confirm('Delete product?')">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span style="color:#6b7280;">View only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:10px;">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINATION --}}
            <div style="margin-top:20px;">
                {{ $products->links() }}
            </div>

        </div>
    @endif
@endsection
