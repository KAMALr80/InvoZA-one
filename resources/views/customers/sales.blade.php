@extends('layouts.app')

@section('content')
    <div
        style="
    max-width:1000px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
">

        <h2 style="margin-bottom:5px;">🧾 {{ $customer->name }}</h2>
        <p style="color:#6b7280;margin-bottom:20px;">
            Purchase History
        </p>

        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#1f2937;color:#fff;">
                    <th style="padding:10px;">Invoice</th>
                    <th style="padding:10px;">Date</th>
                    <th style="padding:10px;">Total</th>
                    <th style="padding:10px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sales as $sale)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:10px;">{{ $sale->invoice_no }}</td>
                        <td style="padding:10px;">{{ $sale->sale_date }}</td>
                        <td style="padding:10px;">₹ {{ $sale->grand_total }}</td>
                        <td style="padding:10px;">

    {{-- VIEW (DETAIL PAGE) --}}
    <a href="{{ route('sales.view', $sale->id) }}"
       style="margin-right:10px; text-decoration:none;">
        👁️ View
    </a>

    {{-- PRINT --}}
    <a href="{{ route('sales.invoice', $sale->id) }}"
       target="_blank"
       style="margin-right:10px; text-decoration:none;">
        🖨 Invoice
    </a>

    {{-- EDIT --}}
    <a href="{{ route('sales.edit', $sale->id) }}"
       style="text-decoration:none;">
        ✏️ Edit
    </a>

</td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:15px;text-align:center;color:#6b7280;">
                            No purchases found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $sales->links() }}
        </div>

    </div>
@endsection
