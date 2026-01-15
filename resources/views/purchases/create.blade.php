@extends('layouts.app')

@section('content')
    <div
        style="
        max-width:650px;
        margin:50px auto;
        background:#ffffff;
        padding:32px;
        border-radius:18px;
        box-shadow:0 12px 30px rgba(0,0,0,.08);
    ">

        {{-- HEADER --}}
        <div style="margin-bottom:25px;">
            <h2 style="margin:0;font-size:26px;font-weight:800;color:#111827;">
                ➕ Add Purchase
            </h2>
            <p style="margin-top:6px;color:#6b7280;">
                Stock will be increased automatically after saving
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

        <form method="POST" action="{{ route('purchases.store') }}">
            @csrf

            {{-- PRODUCT --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Product</label>
                <select name="product_id" required
                    style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
                    <option value="">Select Product</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->name }} (Current Stock: {{ $p->quantity }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- QUANTITY --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Quantity</label>
                <input type="number" id="qty" name="quantity" value="{{ old('quantity') }}" min="1" required
                    style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
            </div>

            {{-- PRICE --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Price (per unit)</label>
                <input type="number" id="price" step="0.01" name="price" value="{{ old('price') }}"
                    min="0" required style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
            </div>

            {{-- PURCHASE DATE --}}
            <div style="margin-bottom:16px;">
                <label style="font-weight:600;">Purchase Date</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required
                    style="width:100%;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
            </div>

            {{-- TOTAL PREVIEW --}}
            <div style="margin-bottom:22px;">
                <label style="font-weight:600;">Total Amount</label>
                <input type="text" id="total" readonly value="₹ 0.00"
                    style="width:100%;padding:11px;border-radius:10px;
                       background:#f9fafb;border:1px solid #e5e7eb;
                       font-weight:700;">
            </div>

            {{-- ACTIONS --}}
            <div style="display:flex;gap:12px;">
                <button type="submit"
                    style="background:#2563eb;color:#fff;
                       padding:12px 22px;border:none;
                       border-radius:12px;font-weight:600;">
                    💾 Save Purchase
                </button>

                <a href="{{ route('purchases.index') }}"
                    style="background:#e5e7eb;color:#111827;
                       padding:12px 22px;border-radius:12px;
                       text-decoration:none;font-weight:600;">
                    ↩ Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- JS FOR LIVE TOTAL --}}
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
