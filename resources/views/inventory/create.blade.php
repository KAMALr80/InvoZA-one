@extends('layouts.app')

@section('content')
    @if (auth()->user()->role !== 'admin')
        <div
            style="background:#1f2937; padding:30px; border-radius:12px; color:#f9fafb; box-shadow:0 6px 20px rgba(0,0,0,0.25); text-align:center;">
            <h2 style="color:#ef4444; font-size:24px; margin-bottom:10px;">🚫 Unauthorized</h2>
            <p style="color:#9ca3af; font-size:16px; margin-bottom:15px;">You do not have permission to add products.</p>
            <a href="{{ route('inventory.index') }}"
                style="color:#3b82f6; font-weight:600; text-decoration:none; transition:0.3s;"
                onmouseover="this.style.color='#1e40af'" onmouseout="this.style.color='#3b82f6'">
                ← Back
            </a>
        </div>
    @else
        <div
            style="max-width:520px; background:#111827; padding:30px; border-radius:12px; color:#f9fafb; box-shadow:0 6px 20px rgba(0,0,0,0.25);">
            <h2 style="margin-bottom:20px; font-size:26px; font-weight:bold; color:#facc15;">➕ Add Product</h2>

            <form method="POST" action="{{ route('inventory.store') }}">
                @csrf

                <label style="display:block; margin-bottom:6px; font-weight:600;">Name</label>
                <input type="text" name="name" required
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <label style="display:block; margin-bottom:6px; font-weight:600;">Quantity</label>
                <input type="number" name="quantity" required
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <label style="display:block; margin-bottom:6px; font-weight:600;">Price</label>
                <input type="text" name="price" required
                    style="width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <label style="display:block; margin-bottom:6px; font-weight:600;">Category</label>
                <input type="text" name="category"
                    style="width:100%; padding:10px; margin-bottom:20px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

                <button type="submit"
                    style="background:linear-gradient(90deg,#2563eb,#1e40af); color:#fff; padding:12px 18px; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:0.3s;"
                    onmouseover="this.style.background='linear-gradient(90deg,#1e40af,#2563eb)'"
                    onmouseout="this.style.background='linear-gradient(90deg,#2563eb,#1e40af)'">
                    💾 Save
                </button>
            </form>
        </div>
    @endif
@endsection
<style>
    /* Pagination container */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
    }

    /* All pagination buttons */
    .pagination li a,
    .pagination li span {
        padding: 6px 10px !important;
        font-size: 13px !important;
        line-height: 1.2;
        min-width: 32px;
        height: 32px;
        text-align: center;
    }

    /* Arrow (‹ ›) size fix */
    .pagination svg {
        width: 14px !important;
        height: 14px !important;
    }

    /* Active page */
    .pagination .active span {
        background-color: #111827;
        color: #fff;
        border-radius: 6px;
    }

    /* Hover effect */
    .pagination li a:hover {
        background: #e5e7eb;
        border-radius: 6px;
    }
</style>
