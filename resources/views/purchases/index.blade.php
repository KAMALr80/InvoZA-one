@extends('layouts.app')

@section('content')
    <div style="max-width:1300px;margin:auto;">

        {{-- PAGE HEADER --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">
            <div>
                <h2 style="margin:0;font-size:28px;font-weight:800;color:#111827;">
                    🛒 Purchase Management
                </h2>
                <p style="margin-top:6px;color:#6b7280;">
                    Manage purchases, stock & history
                </p>
            </div>

            <a href="{{ route('purchases.create') }}"
                style="background:#2563eb;color:#fff;
                   padding:10px 18px;border-radius:10px;
                   text-decoration:none;font-weight:600;">
                ➕ Add Purchase
            </a>
        </div>

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div
                style="background:#dcfce7;color:#166534;padding:12px 16px;
                    border-radius:8px;margin-bottom:18px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                style="background:#fee2e2;color:#991b1b;padding:12px 16px;
                    border-radius:8px;margin-bottom:18px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- SUMMARY CARDS --}}
        <div
            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
                gap:20px;margin-bottom:35px;">

            <div style="padding:22px;border-radius:16px;background:#f0f9ff;">
                <div style="color:#0369a1;font-size:14px;">Total Products</div>
                <div style="font-size:34px;font-weight:800;color:#0369a1;">
                    {{ $totalProducts }}
                </div>
            </div>

            <div style="padding:22px;border-radius:16px;background:#ecfdf5;">
                <div style="color:#047857;font-size:14px;">Total Purchase Amount</div>
                <div style="font-size:30px;font-weight:800;color:#047857;">
                    ₹ {{ number_format($totalPurchaseAmount, 2) }}
                </div>
            </div>

            <div style="padding:22px;border-radius:16px;background:#fee2e2;">
                <div style="color:#991b1b;font-size:14px;">Low Stock Items</div>
                <div style="font-size:30px;font-weight:800;color:#991b1b;">
                    {{ $lowStockProducts->count() }}
                </div>
            </div>
        </div>

        {{-- LOW STOCK PRODUCTS --}}
        <div
            style="background:#fff;padding:24px;border-radius:18px;
                box-shadow:0 10px 25px rgba(0,0,0,.08);margin-bottom:40px;">

            <h3 style="margin-bottom:15px;color:#dc2626;font-weight:700;">
                ⚠️ Low Stock Products
            </h3>

            @if ($lowStockProducts->count())
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#fee2e2;">
                            <th style="padding:10px;text-align:left;">Product</th>
                            <th style="padding:10px;">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $p)
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <td style="padding:10px;">{{ $p->name }}</td>
                                <td style="padding:10px;color:#dc2626;font-weight:800;">
                                    {{ $p->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#16a34a;font-weight:600;">
                    ✔ All products sufficiently stocked
                </p>
            @endif
        </div>

        {{-- PURCHASE HISTORY --}}
        <div
            style="background:#fff;padding:24px;border-radius:18px;
                box-shadow:0 10px 25px rgba(0,0,0,.08);">

            <h3 style="margin-bottom:15px;font-weight:700;">
                📜 Purchase History
            </h3>

            @if ($purchases->count())
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:10px;">Date</th>
                            <th style="padding:10px;">Product</th>
                            <th style="padding:10px;">Qty</th>
                            <th style="padding:10px;">Price</th>
                            <th style="padding:10px;">Total</th>
                            <th style="padding:10px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <td style="padding:10px;">
                                    {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
                                </td>

                                <td style="padding:10px;font-weight:600;">
                                    {{ $purchase->product->name ?? '-' }}
                                </td>

                                <td style="padding:10px;">
                                    {{ $purchase->quantity }}
                                </td>

                                <td style="padding:10px;">
                                    ₹ {{ number_format($purchase->price, 2) }}
                                </td>

                                <td style="padding:10px;font-weight:700;">
                                    ₹ {{ number_format($purchase->total, 2) }}
                                </td>

                                <td style="padding:10px;">
                                    <a href="{{ route('purchases.edit', $purchase->id) }}"
                                        style="color:#2563eb;font-weight:600;text-decoration:none;">
                                        ✏️ Edit
                                    </a>

                                    <form method="POST" action="{{ route('purchases.destroy', $purchase->id) }}"
                                        style="display:inline;"
                                        onsubmit="return confirm('Delete this purchase? Stock will be restored!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background:none;border:none;color:#dc2626;
                                               font-weight:600;cursor:pointer;">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:18px;">
                    {{ $purchases->links() }}
                </div>
            @else
                <p style="color:#16a34a;font-weight:600;">
                    ✔ No purchase records found
                </p>
            @endif
        </div>

    </div>
@endsection
