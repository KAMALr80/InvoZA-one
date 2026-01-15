@extends('layouts.app')

@section('content')
    <div
        style="
        max-width:750px;
        margin:40px auto;
        background:#ffffff;
        padding:32px;
        border-radius:18px;
        box-shadow:0 12px 30px rgba(0,0,0,.08);
    ">

        {{-- HEADER --}}
        <div style="margin-bottom:25px;">
            <h2 style="margin:0;font-size:26px;font-weight:800;color:#111827;">
                ✏️ Edit Purchase
            </h2>
            <p style="margin-top:6px;color:#6b7280;">
                You can update quantity or price. Stock will auto-adjust.
            </p>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div
                style="background:#fee2e2;color:#991b1b;
                    padding:12px 16px;border-radius:8px;margin-bottom:20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('purchases.update', $purchase->id) }}">
            @csrf
            @method('PUT')

            {{-- PRODUCT (LOCKED) --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Product</label>

                <select disabled
                    style="
                    width:100%;
                    padding:11px;
                    border-radius:10px;
                    border:1px solid #d1d5db;
                    background:#f3f4f6;
                ">
                    <option>
                        {{ $purchase->product->name }}
                        (Current Stock: {{ $purchase->product->quantity }})
                    </option>
                </select>

                {{-- ACTUAL VALUE --}}
                <input type="hidden" name="product_id" value="{{ $purchase->product_id }}">

                <small style="color:#6b7280;">
                    🔒 Product cannot be changed after purchase creation
                </small>
            </div>

            {{-- QUANTITY --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Quantity</label>
                <input type="number" id="qty" name="quantity" value="{{ old('quantity', $purchase->quantity) }}"
                    min="1" required style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">

                <small style="color:#6b7280;">
                    ⚠️ Changing quantity will update stock automatically
                </small>
            </div>

            {{-- PRICE --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Price (per unit)</label>
                <input type="number" id="price" step="0.01" name="price"
                    value="{{ old('price', $purchase->price) }}" min="0" required
                    style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
            </div>

            {{-- PURCHASE DATE --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Purchase Date</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date) }}"
                    required style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
            </div>

            {{-- TOTAL PREVIEW --}}
            <div style="margin-bottom:24px;">
                <label style="font-weight:600;">Total Amount</label>
                <input type="text" id="total" readonly
                    value="₹ {{ number_format($purchase->quantity * $purchase->price, 2) }}"
                    style="
                    width:100%;
                    padding:11px;
                    border-radius:10px;
                    background:#f9fafb;
                    border:1px solid #e5e7eb;
                    font-weight:700;
                ">
            </div>

            {{-- ACTIONS --}}
            <div style="display:flex;gap:12px;">
                <button type="submit"
                    style="
                    background:#2563eb;
                    color:#fff;
                    padding:12px 22px;
                    border:none;
                    border-radius:12px;
                    font-weight:600;
                    cursor:pointer;
                ">
                    💾 Update Purchase
                </button>

                <a href="{{ route('purchases.index') }}"
                    style="
                    background:#e5e7eb;
                    color:#111827;
                    padding:12px 22px;
                    border-radius:12px;
                    text-decoration:none;
                    font-weight:600;
                ">
                    ↩ Back
                </a>
            </div>
        </form>
    </div>

    {{-- LIVE TOTAL SCRIPT --}}
    <script>
        const qtyInput = document.getElementById('qty');
        const priceInput = document.getElementById('price');
        const totalInput = document.getElementById('total');

        function calculateTotal() {
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            totalInput.value = '₹ ' + (qty * price).toFixed(2);
        }

        qtyInput.addEventListener('input', calculateTotal);
        priceInput.addEventListener('input', calculateTotal);
    </script>
@endsection
