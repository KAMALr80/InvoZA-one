<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->invoice_no }}</title>
</head>

<body style="
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #000;
">

    <!-- HEADER -->
    <div style="text-align:center; margin-bottom:15px;">
        <h2 style="margin:0; font-size:18px;">TAX INVOICE</h2>
        <p style="margin:4px 0;">Your Company Name</p>
        <p style="margin:0;">Address Line, City</p>
    </div>

    <hr style="border:1px solid #000;">

    <!-- INVOICE DETAILS -->
    <table width="100%" cellpadding="5" cellspacing="0" style="margin-top:10px;">
        <tr>
            <td width="50%">
                <strong>Invoice No:</strong> {{ $sale->invoice_no }} <br>
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}
            </td>
            <td width="50%" style="text-align:right;">
                <strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in Customer' }} <br>
                <strong>Mobile:</strong> {{ $sale->customer->mobile ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- ITEMS TABLE -->
    <table width="100%" cellpadding="6" cellspacing="0"
        style="
        border-collapse:collapse;
        margin-top:15px;
    ">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #000;">#</th>
                <th style="border:1px solid #000;">Product</th>
                <th style="border:1px solid #000; text-align:right;">Price</th>
                <th style="border:1px solid #000; text-align:center;">Qty</th>
                <th style="border:1px solid #000; text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $i => $item)
                <tr>
                    <td style="border:1px solid #000; text-align:center;">
                        {{ $i + 1 }}
                    </td>
                    <td style="border:1px solid #000;">
                        {{ $item->product->name }}
                    </td>
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($item->price, 2) }}
                    </td>
                    <td style="border:1px solid #000; text-align:center;">
                        {{ $item->quantity }}
                    </td>
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($item->total, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table width="40%" cellpadding="6" cellspacing="0"
        style="
        border-collapse:collapse;
        margin-top:15px;
        float:right;
    ">
        <tr>
            <td style="border:1px solid #000;">Sub Total</td>
            <td style="border:1px solid #000; text-align:right;">
                {{ number_format($sale->sub_total, 2) }}
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000;">Discount</td>
            <td style="border:1px solid #000; text-align:right;">
                {{ number_format($sale->discount, 2) }}
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000;">Tax</td>
            <td style="border:1px solid #000; text-align:right;">
                {{ number_format($sale->tax, 2) }}
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #000;"><strong>Grand Total</strong></td>
            <td style="border:1px solid #000; text-align:right;">
                <strong>{{ number_format($sale->grand_total, 2) }}</strong>
            </td>
        </tr>
    </table>

    <div style="clear:both;"></div>

    <!-- FOOTER -->
    <p style="
        text-align:center;
        margin-top:30px;
        font-size:11px;
    ">
        Thank you for your business! <br>
        <strong>This is a computer generated invoice.</strong>
    </p>

</body>

</html>
