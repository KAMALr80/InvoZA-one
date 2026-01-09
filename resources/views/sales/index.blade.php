@extends('layouts.app')

@section('content')
    <div style="background:#fff; padding:25px; border-radius:8px; width:100%; box-sizing:border-box;">

        <h2 style="margin-bottom:20px;">Sales</h2>

        @if (session('success'))
            <p style="color:green; margin-bottom:10px;">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p style="color:red; margin-bottom:10px;">
                {{ session('error') }}
            </p>
        @endif

        {{-- Sales Form --}}
        <form method="POST" action="{{ url('/sales') }}"
            style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:15px; margin-bottom:30px;">
            @csrf

            <select name="product_id" required style="padding:8px;">
                <option value="">Select Product</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->name }} (Stock: {{ $p->quantity }})
                    </option>
                @endforeach
            </select>

            <input type="number" name="quantity" placeholder="Qty" required style="padding:8px;">

            <input type="date" name="sale_date" required style="padding:8px;">

            <button type="submit"
                style="background:#111827; color:#fff; border:none; padding:10px 16px; border-radius:6px;">
                Add Sale
            </button>
        </form>

        {{-- Sales Table --}}
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:10px; text-align:left;">Product</th>
                    <th style="padding:10px; text-align:left;">Qty</th>
                    <th style="padding:10px; text-align:left;">Price</th>
                    <th style="padding:10px; text-align:left;">Total</th>
                    <th style="padding:10px; text-align:left;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $s)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:10px;">{{ $s->product->name }}</td>
                        <td style="padding:10px;">{{ $s->quantity }}</td>
                        <td style="padding:10px;">₹ {{ $s->price }}</td>
                        <td style="padding:10px;">₹ {{ $s->total }}</td>
                        <td style="padding:10px;">{{ $s->sale_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:10px;">No sales found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div style="margin-top:20px;">
            {{ $sales->links() }}
        </div>

    </div>
@endsection
