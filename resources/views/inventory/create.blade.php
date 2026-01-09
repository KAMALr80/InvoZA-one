@extends('layouts.app')

@section('content')
    @if (auth()->user()->role !== 'admin')
        <div style="background:#fff; padding:30px; border-radius:8px;">
            <h2 style="color:#dc2626;">Unauthorized</h2>
            <p>You do not have permission to add products.</p>
            <a href="{{ route('inventory.index') }}">← Back</a>
        </div>
    @else
        <div style="max-width:520px; background:#fff; padding:30px; border-radius:8px;">
            <h2>Add Product</h2>

            <form method="POST" action="{{ route('inventory.store') }}">
                @csrf

                <label>Name</label>
                <input type="text" name="name" required style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Quantity</label>
                <input type="number" name="quantity" required style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Price</label>
                <input type="text" name="price" required style="width:100%; padding:8px; margin-bottom:15px;">

                <label>Category</label>
                <input type="text" name="category" style="width:100%; padding:8px; margin-bottom:20px;">

                <button type="submit"
                    style="background:#111827; color:#fff; padding:10px 16px; border:none; border-radius:6px;">
                    Save
                </button>
            </form>
        </div>
    @endif
@endsection
