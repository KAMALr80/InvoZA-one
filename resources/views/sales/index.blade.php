@extends('layouts.app')

@section('content')
    {{-- ===== PAGINATION STYLE FIX ===== --}}


    <style>
        .w-5.h-5 {
            width: 20px;
        }
    </style>

    <div
        style="
        background:#ffffff;
        padding:30px;
        border-radius:12px;
        box-shadow:0 10px 25px rgba(0,0,0,0.08);
        max-width:1200px;
        margin:auto;
    ">

        {{-- HEADER --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0; color:#1f2937; font-size:24px; font-weight:700;">
                💰 Sales / Invoices
            </h2>

            <a href="{{ route('sales.create') }}"
                style="
                background:#2563eb;
                color:#ffffff;
                padding:10px 18px;
                border-radius:8px;
                text-decoration:none;
                font-size:14px;
                font-weight:600;
                box-shadow:0 4px 10px rgba(37,99,235,0.3);
            ">
                + New Sale
            </a>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div
                style="
                background:#ecfdf5;
                color:#065f46;
                padding:10px 15px;
                border-left:5px solid #10b981;
                border-radius:6px;
                margin-bottom:15px;
                font-weight:600;
            ">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div style="overflow-x:auto;">
            <table
                style="
                width:100%;
                border-collapse:collapse;
                font-size:14px;
            ">
                <thead>
                    <tr
                        style="
                        background:#f3f4f6;
                        color:#374151;
                        text-align:left;
                    ">
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb;">Invoice</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb;">Date</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb;">Customer</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb;">Total</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:center;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sales as $sale)
                        <tr style="border-bottom:1px solid #e5e7eb;" onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background='transparent'">

                            <td style="padding:12px; font-weight:600;">
                                {{ $sale->invoice_no }}
                            </td>

                            <td style="padding:12px;">
                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                            </td>

                            <td style="padding:12px;">
                                {{ $sale->customer->name ?? 'Walk-in Customer' }}
                            </td>

                            <td style="padding:12px; font-weight:700; color:#065f46;">
                                ₹ {{ number_format($sale->grand_total, 2) }}
                            </td>

                            <td style="padding:12px; text-align:center;">
                                <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank"
                                    style="
                                   background:#111827;
                                   color:#ffffff;
                                   padding:6px 12px;
                                   border-radius:6px;
                                   text-decoration:none;
                                   font-size:13px;
                               ">
                                    🖨 Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                style="
                                padding:20px;
                                text-align:center;
                                color:#6b7280;
                            ">
                                No sales records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:25px;">
            {{ $sales->links() }}
        </div>

    </div>
@endsection
