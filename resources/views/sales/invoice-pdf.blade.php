<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->invoice_no }}</title>
    <style>
        /* ================= PROFESSIONAL INVOICE STYLES ================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            background: #fff;
            padding: 20px;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ================= HEADER ================= */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .logo-cell {
            width: 20%;
            vertical-align: top;
        }

        .logo-img {
            height: 70px;
            max-width: 150px;
        }

        .company-cell {
            width: 55%;
            text-align: center;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 5px;
            text-transform: uppercase;
        }

        .company-details {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.6;
        }

        .gst-badge {
            background: #e0e7ff;
            color: #4338ca;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
            margin-top: 5px;
        }

        .status-cell {
            width: 25%;
            text-align: right;
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            color: white;
        }

        .status-paid {
            background: #16a34a;
        }

        .status-partial {
            background: #f97316;
        }

        .status-unpaid {
            background: #dc2626;
        }

        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 15px 0;
        }

        .light-hr {
            border-top: 1px dashed #ccc;
            margin: 15px 0;
        }

        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin: 15px 0;
            text-transform: uppercase;
        }

        /* ================= DETAILS TABLE ================= */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .details-table td {
            padding: 8px 10px;
            border: 1px solid #000;
            vertical-align: top;
        }

        .details-table .label {
            font-weight: 700;
            background: #f2f2f2;
            width: 120px;
        }

        /* ================= ITEMS TABLE ================= */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th {
            background: #f2f2f2;
            padding: 10px 8px;
            border: 1px solid #000;
            font-weight: 700;
            text-align: left;
        }

        .items-table td {
            padding: 8px;
            border: 1px solid #000;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ================= TOTALS TABLE ================= */
        .totals-table {
            width: 40%;
            border-collapse: collapse;
            margin: 20px 0;
            float: right;
        }

        .totals-table td {
            padding: 8px 12px;
            border: 1px solid #000;
        }

        .totals-table td:first-child {
            font-weight: 600;
            background: #f2f2f2;
        }

        .totals-table td:last-child {
            text-align: right;
        }

        .grand-total {
            font-weight: 700;
            font-size: 16px;
        }

        .clear {
            clear: both;
        }

        /* ================= PAYMENT HISTORY ================= */
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .payment-table td {
            padding: 12px;
            border: 1px solid #000;
        }

        /* ================= BANK TABLE ================= */
        .bank-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .bank-table td {
            padding: 12px;
            border: 1px solid #000;
        }

        /* ================= FOOTER ================= */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header-table tr {
                display: block;
            }

            .header-table td {
                display: block;
                width: 100%;
                text-align: center;
            }

            .status-cell {
                text-align: center;
            }

            .totals-table {
                width: 100%;
                float: none;
            }
        }
    </style>
</head>

<body>
    @php
        $paidPayments = $sale->payments->where('status', 'paid');
        $totalPaid = $paidPayments->sum('amount');
        $balanceDue = $sale->grand_total - $totalPaid;

        // ========== AMOUNT IN WORDS FUNCTION ==========
        function numberToWords($num) {
            $ones = [
                0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
                6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
                11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
                15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
                19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
                60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
            ];

            if ($num < 20) return $ones[$num];
            if ($num < 100) return $ones[floor($num/10)*10] . ($num%10 > 0 ? ' ' . $ones[$num%10] : '');
            if ($num < 1000) return $ones[floor($num/100)] . ' Hundred' . ($num%100 > 0 ? ' ' . numberToWords($num%100) : '');
            if ($num < 100000) return numberToWords(floor($num/1000)) . ' Thousand' . ($num%1000 > 0 ? ' ' . numberToWords($num%1000) : '');
            if ($num < 10000000) return numberToWords(floor($num/100000)) . ' Lakh' . ($num%100000 > 0 ? ' ' . numberToWords($num%100000) : '');
            return numberToWords(floor($num/10000000)) . ' Crore' . ($num%10000000 > 0 ? ' ' . numberToWords($num%10000000) : '');
        }

        $amountInWords = numberToWords(floor($sale->grand_total)) . ' Rupees';
        if (($sale->grand_total - floor($sale->grand_total)) > 0) {
            $paise = round(($sale->grand_total - floor($sale->grand_total)) * 100);
            $amountInWords .= ' and ' . numberToWords($paise) . ' Paise';
        }
        $amountInWords .= ' Only';
    @endphp

    <!-- ================= HEADER ================= -->
    <table class="header-table">
        <tr>

            <td class="company-cell">
                <h2 class="company-name">Invoza-one</h2>
                <div class="company-details">
                    	K-110, Basement, Hauz Khas Enclave, New Delhi, Delhi 110016, India<br>
                    Mobile: 9876543210 | Email: invoza@company.com<br>
                    <span class="gst-badge">GSTIN: 24ABCDE1234F1Z5</span>
                </div>
            </td>
            <td class="status-cell">
                @if ($sale->payment_status === 'paid')
                    <span class="status-badge status-paid">PAID</span>
                @elseif($sale->payment_status === 'partial')
                    <span class="status-badge status-partial">PARTIAL</span>
                @else
                    <span class="status-badge status-unpaid">UNPAID</span>
                @endif
            </td>
        </tr>
    </table>

    <hr>

    <h3 class="invoice-title">TAX INVOICE</h3>

    <!-- ================= INVOICE DETAILS ================= -->
    <table class="details-table">
        <tr>
            <td class="label" width="20%">Invoice No:</td>
            <td width="30%"><strong>{{ $sale->invoice_no }}</strong></td>
            <td class="label" width="20%">Invoice Date:</td>
            <td width="30%"><strong>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d.m.Y') }}</strong></td>
        </tr>
        <tr>
            <td class="label">Due Date:</td>
            <td><strong>{{ \Carbon\Carbon::parse($sale->sale_date)->addDays(30)->format('d.m.Y') }}</strong></td>
            <td class="label">Payment Mode:</td>
            <td>
                <strong>
                    @if ($paidPayments->count())
                        {{ strtoupper($paidPayments->first()->method) }}
                    @else
                        Pending
                    @endif
                </strong>
            </td>
        </tr>
    </table>

    <!-- ================= BILL TO ================= -->
    <table class="details-table">
        <tr>
            <td class="label" width="20%">Bill To:</td>
            <td colspan="3">
                <strong>{{ $sale->customer->name ?? 'Walk-in Customer' }}</strong><br>
                Mobile: {{ $sale->customer->mobile ?? '-' }} | Email: {{ $sale->customer->email ?? '-' }}<br>
                GST: {{ $sale->customer->gst_no ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- ================= ITEMS ================= -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="50%">Product</th>
                <th width="15%" class="text-right">Rate (₹)</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ================= TOTALS ================= -->
    <table class="totals-table">
        <tr>
            <td>Sub Total</td>
            <td>₹ {{ number_format($sale->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td>₹ {{ number_format($sale->discount, 2) }}</td>
        </tr>
        <tr>
            <td>Tax ({{ $sale->tax }}%)</td>
            <td>₹ {{ number_format($sale->tax_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="grand-total"><strong>Grand Total</strong></td>
            <td class="grand-total"><strong>₹ {{ number_format($sale->grand_total, 2) }}</strong></td>
        </tr>
    </table>

    <div class="clear"></div>

    <!-- ================= PAYMENT HISTORY ================= -->
    @if ($sale->payments->count())
        <table class="payment-table">
            <tr>
                <td>
                    <strong>Payment History</strong><br><br>
                    @foreach ($sale->payments as $p)
                        Amount: ₹ {{ number_format($p->amount, 2) }} |
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
    <p style="margin:20px 0; font-weight:600;">
        <strong>Amount in Words:</strong> {{ $amountInWords }}
    </p>

    <!-- ================= BANK DETAILS ================= -->
    <table class="bank-table">
        <tr>
            <td>
                <strong>Bank Details</strong><br>
                Bank: HDFC Bank<br>
                A/C No: 1234567890<br>
                IFSC: HDFC0001234<br>
                Name: INVOZA-One
            </td>
        </tr>
    </table>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        Thank you for your business.<br>
        <strong>This is a computer generated invoice.</strong>
    </div>

</body>

</html>
