@extends('layouts.app')

@section('content')
    <div
        style="
        max-width:1000px;
        margin:40px auto;
        background:#ffffff;
        padding:30px;
        border-radius:14px;
        box-shadow:0 10px 25px rgba(0,0,0,0.08);
    ">

        <h2 style="margin-bottom:5px;">
            👁️ Invoice View – {{ $sale->invoice_no }}
        </h2>

        <p style="color:#6b7280;margin-bottom:20px;">
            Customer: <strong>{{ $sale->customer->name ?? 'Walk-in' }}</strong> |
            Date: {{ $sale->sale_date }}
        </p>

        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#1f2937;color:#fff;">
                    <th style="padding:10px;">#</th>
                    <th style="padding:10px;">Product</th>
                    <th style="padding:10px;">Price</th>
                    <th style="padding:10px;">Qty</th>
                    <th style="padding:10px;">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sale->items as $i => $item)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:10px;">{{ $i + 1 }}</td>
                        <td style="padding:10px;">{{ $item->product->name }}</td>
                        <td style="padding:10px;">₹ {{ $item->price }}</td>
                        <td style="padding:10px;">{{ $item->quantity }}</td>
                        <td style="padding:10px;">₹ {{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:20px;text-align:right;">
            <strong>Sub Total:</strong> ₹ {{ $sale->sub_total }} <br>
            <strong>Discount:</strong> ₹ {{ $sale->discount }} <br>
            <strong>Tax:</strong> {{ $sale->tax }} % <br>
            <strong>Grand Total:</strong> ₹ {{ $sale->grand_total }}
        </div>

        <div style="margin-top:25px;">
            <a href="{{ route('customers.sales', $sale->customer_id) }}"
                style="
        background:#6b7280;
        color:#fff;
        padding:8px 14px;
        border-radius:6px;
        text-decoration:none;
   ">
                ⬅ Back to Customer History
            </a>

        </div>

    </div>
@endsection
