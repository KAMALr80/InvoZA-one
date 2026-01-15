@extends('layouts.app')

@section('content')
    <style>
        .w-5.h-5 {
            width: 20px;
        }
    </style>
    @if (auth()->user()->role === 'staff')
        <div
            style="background:#1f2937; padding:30px; border-radius:12px; color:#f9fafb; box-shadow:0 6px 20px rgba(0,0,0,0.25); text-align:center;">
            <h2 style="color:#ef4444; font-size:24px; margin-bottom:10px;">🚫 Unauthorized</h2>
            <p style="color:#9ca3af; font-size:16px;">You do not have permission to access inventory.</p>
        </div>
    @else
        <div
            style="background:#111827; padding:30px; border-radius:12px; width:100%; color:#f9fafb; font-family:'Segoe UI', Arial, sans-serif; box-shadow:0 6px 20px rgba(0,0,0,0.25);">

            {{-- HEADER --}}
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="margin:0; font-size:26px; font-weight:bold; color:#facc15;">📦 Inventory</h2>

                {{-- ADD PRODUCT – ADMIN ONLY --}}
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('inventory.create') }}"
                        style="background:linear-gradient(90deg,#2563eb,#1e40af); color:#fff; padding:10px 18px; border-radius:8px; text-decoration:none; font-weight:600; box-shadow:0 4px 10px rgba(0,0,0,0.3); transition:0.3s;"
                        onmouseover="this.style.background='linear-gradient(90deg,#1e40af,#2563eb)'"
                        onmouseout="this.style.background='linear-gradient(90deg,#2563eb,#1e40af)'">
                        ➕ Add Product
                    </a>
                @endif
            </div>

            {{-- TABLE --}}
            <table
                style="width:100%; border-collapse:collapse; background:#1f2937; border-radius:10px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
                <thead>
                    <tr style="background:#374151; color:#facc15; text-align:left; font-size:15px;">
                        <th style="padding:12px;">Code</th>
                        <th style="padding:12px;">Name</th>
                        <th style="padding:12px;">Qty</th>
                        <th style="padding:12px;">Price</th>
                        <th style="padding:12px;">Category</th>
                        <th style="padding:12px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr style="border-bottom:1px solid #4b5563; transition:0.3s;"
                            onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#1f2937'">
                            <td style="padding:12px;">{{ $p->product_code }}</td>
                            <td style="padding:12px;">{{ $p->name }}</td>
                            <td style="padding:12px;">{{ $p->quantity }}</td>
                            <td style="padding:12px; font-weight:bold; color:#22c55e;">₹ {{ $p->price }}</td>
                            <td style="padding:12px;">{{ $p->category }}</td>
                            <td style="padding:12px;">
                                {{-- ADMIN ONLY ACTIONS --}}
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('inventory.edit', $p->id) }}"
                                        style="margin-right:12px; color:#3b82f6; font-weight:600; text-decoration:none; transition:0.3s;"
                                        onmouseover="this.style.color='#1e40af'" onmouseout="this.style.color='#3b82f6'">
                                        ✏️ Edit
                                    </a>

                                    <form method="POST" action="{{ route('inventory.destroy', $p->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            style="background:#dc2626; border:none; color:#fff; padding:6px 12px; border-radius:6px; cursor:pointer; font-weight:600; transition:0.3s;"
                                            onmouseover="this.style.background='#b91c1c'"
                                            onmouseout="this.style.background='#dc2626'"
                                            onclick="return confirm('Delete product?')">
                                            🗑 Delete
                                        </button>
                                    </form>
                                @else
                                    <span style="color:#9ca3af; font-style:italic;">View only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:12px; text-align:center; color:#9ca3af;">No products found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINATION --}}
            <div style="margin-top:25px; text-align:center;">
                {{ $products->links() }}
            </div>

        </div>
    @endif
@endsection
