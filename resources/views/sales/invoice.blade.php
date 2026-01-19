<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->invoice_no }}</title>
</head>

<body style="font-family: DejaVu Sans, sans-serif; font-size:12px; color:#000;">

    @php
        $paidPayments = $sale->payments->where('status', 'paid');
    @endphp

    <!-- ================= HEADER ================= -->
    <table width="100%" cellpadding="6">
        <tr>
            <td width="20%">
                <img src="{{ public_path('logo.png') }}" style="height:70px;">
            </td>
            <td width="60%">
                <h2 style="margin:0;">YOUR COMPANY NAME</h2>
                <p style="margin:2px 0;">Address Line, City, State - Pincode</p>
                <p style="margin:2px 0;">Mobile: 9876543210 | Email: info@company.com</p>
                <p style="margin:2px 0;"><strong>GSTIN:</strong> 24ABCDE1234F1Z5</p>
            </td>

            {{-- PAYMENT STATUS --}}
            <td width="20%" align="right">
                @if ($sale->payment_status === 'paid')
                    <span style="color:#fff;background:#16a34a;padding:6px 12px;border-radius:6px;font-size:12px;">
                        PAID
                    </span>
                @elseif($sale->payment_status === 'partial')
                    <span style="color:#fff;background:#f97316;padding:6px 12px;border-radius:6px;font-size:12px;">
                        PARTIAL
                    </span>
                @else
                    <span style="color:#fff;background:#dc2626;padding:6px 12px;border-radius:6px;font-size:12px;">
                        UNPAID
                    </span>
                @endif
            </td>
        </tr>
    </table>

    <hr>

    <h3 align="center">TAX INVOICE</h3>

    <!-- ================= INVOICE DETAILS ================= -->
    <table width="100%" cellpadding="6">
        <tr>
            <td width="50%">
                <strong>Invoice No:</strong> {{ $sale->invoice_no }} <br>
                <strong>Date:</strong> {{ $sale->formatted_date }} <br>

                <strong>Payment Mode:</strong>
                @if ($paidPayments->count())
                    {{ strtoupper($paidPayments->first()->method) }}
                @else
                    Pending
                @endif
            </td>

            <td width="50%">
                <strong>Bill To:</strong><br>
                {{ $sale->customer->name ?? 'Walk-in Customer' }} <br>
                Mobile: {{ $sale->customer->mobile ?? '-' }} <br>
                Email: {{ $sale->customer->email ?? '-' }} <br>
                GST: {{ $sale->customer->gst_no ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- ================= ITEMS ================= -->
    <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse; margin-top:10px;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #000;">#</th>
                <th style="border:1px solid #000;">Product</th>
                <th style="border:1px solid #000;" align="right">Rate</th>
                <th style="border:1px solid #000;" align="center">Qty</th>
                <th style="border:1px solid #000;" align="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $i => $item)
                <tr>
                    <td style="border:1px solid #000;" align="center">{{ $i + 1 }}</td>
                    <td style="border:1px solid #000;">{{ $item->product->name }}</td>
                    <td style="border:1px solid #000;" align="right">{{ number_format($item->price, 2) }}</td>
                    <td style="border:1px solid #000;" align="center">{{ $item->quantity }}</td>
                    <td style="border:1px solid #000;" align="right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ================= TOTAL ================= -->
    <table width="40%" align="right" cellpadding="6" cellspacing="0"
        style="border-collapse:collapse; margin-top:10px;">
        <tr>
            <td style="border:1px solid #000;">Sub Total</td>
            <td style="border:1px solid #000;" align="right">{{ number_format($sale->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000;">Discount</td>
            <td style="border:1px solid #000;" align="right">{{ number_format($sale->discount, 2) }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000;">Tax</td>
            <td style="border:1px solid #000;" align="right">{{ number_format($sale->tax, 2) }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000;"><strong>Grand Total</strong></td>
            <td style="border:1px solid #000;" align="right">
                <strong>{{ number_format($sale->grand_total, 2) }}</strong>
            </td>
        </tr>
    </table>

    <div style="clear:both;"></div>

    <!-- ================= PAYMENT DETAILS ================= -->
    @if ($sale->payments->count())
        <table width="100%" cellpadding="6" style="border-collapse:collapse; margin-top:15px;">
            <tr>
                <td style="border:1px solid #000;">
                    <strong>Payment History</strong><br><br>

                    @foreach ($sale->payments as $p)
                        Amount: {{ number_format($p->amount, 2) }} |
                        Method: {{ strtoupper($p->method) }} |
                        Status: {{ strtoupper($p->status) }}
                        @if ($p->transaction_id)
                            | Txn: {{ $p->transaction_id }}
                        @endif
                        | Date: {{ $p->created_at->format('d M Y') }}
                        <br>
                    @endforeach
                </td>
            </tr>
        </table>
    @endif

    <!-- ================= AMOUNT IN WORDS ================= -->
    <p style="margin-top:15px;">
        <strong>Amount in Words:</strong> {{ $amountInWords }}
    </p>

    <!-- ================= BANK ================= -->
    <table width="100%" cellpadding="6" style="border-collapse:collapse; margin-top:10px;">
        <tr>
            <td style="border:1px solid #000;">
                <strong>Bank Details</strong><br>
                Bank: HDFC Bank<br>
                A/C No: 1234567890<br>
                IFSC: HDFC0001234<br>
                Name: YOUR COMPANY NAME
            </td>
        </tr>
    </table>

    <p align="center" style="margin-top:20px; font-size:11px;">
        Thank you for your business.<br>
        <strong>This is a computer generated invoice.</strong>
    </p>

</body>

</html>
