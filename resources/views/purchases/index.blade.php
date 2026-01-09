@extends('layouts.app')

@section('content')
    <div
        style="background:#1f2937; padding:30px; border-radius:12px; width:100%; box-sizing:border-box; color:#f9fafb; font-family:'Segoe UI', Arial, sans-serif; box-shadow:0 6px 20px rgba(0,0,0,0.25);">

        <h2 style="margin-bottom:20px; font-size:26px; font-weight:bold; color:#facc15;">🛒 Purchases</h2>

        @if (session('success'))
            <p style="color:#22c55e; margin-bottom:15px; font-weight:bold;">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p style="color:#ef4444; margin-bottom:15px; font-weight:bold;">
                {{ session('error') }}
            </p>
        @endif

        {{-- Purchase Form --}}
        <form method="POST" action="{{ url('/purchases') }}"
            style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap:15px; margin-bottom:30px; background:#111827; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.3);">
            @csrf

            <select name="product_id" required
                style="padding:10px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">
                <option value="">Select Product</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->name }} (Stock: {{ $p->quantity }})
                    </option>
                @endforeach
            </select>

            <input type="number" name="quantity" placeholder="Qty" required
                style="padding:10px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

            <input type="text" name="price" placeholder="Price" required
                style="padding:10px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

            <input type="date" name="purchase_date" required
                style="padding:10px; border-radius:6px; border:1px solid #374151; background:#1f2937; color:#f9fafb;">

            <button type="submit"
                style="background:linear-gradient(90deg,#2563eb,#1e40af); color:#fff; border:none; padding:12px 18px; border-radius:8px; font-weight:600; cursor:pointer; transition:0.3s;"
                onmouseover="this.style.background='linear-gradient(90deg,#1e40af,#2563eb)'"
                onmouseout="this.style.background='linear-gradient(90deg,#2563eb,#1e40af)'">
                ➕ Add Purchase
            </button>
        </form>

        {{-- Purchases Table --}}
        <table
            style="width:100%; border-collapse:collapse; background:#111827; border-radius:10px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
            <thead>
                <tr style="background:#374151; color:#facc15; text-align:left; font-size:15px;">
                    <th style="padding:12px;">Product</th>
                    <th style="padding:12px;">Qty</th>
                    <th style="padding:12px;">Price</th>
                    <th style="padding:12px;">Total</th>
                    <th style="padding:12px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $p)
                    <tr style="border-bottom:1px solid #4b5563; transition:0.3s;"
                        onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#111827'">
                        <td style="padding:12px;">{{ $p->product->name }}</td>
                        <td style="padding:12px;">{{ $p->quantity }}</td>
                        <td style="padding:12px;">₹ {{ $p->price }}</td>
                        <td style="padding:12px; font-weight:bold; color:#22c55e;">₹ {{ $p->total }}</td>
                        <td style="padding:12px;">{{ $p->purchase_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:12px; text-align:center; color:#9ca3af;">
                            No purchase records found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div style="margin-top:25px; text-align:center;">
            {{ $purchases->links() }}
        </div>

    </div>
@endsection
