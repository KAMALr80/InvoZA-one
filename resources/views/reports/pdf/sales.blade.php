<!DOCTYPE html>
<html>

<head>
    <title>Sales Report</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>

    <h2>Sales Report</h2>

    <table>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>Date</th>
        </tr>

        @foreach ($sales as $s)
            <tr>
                <td>{{ $s->product->name }}</td>
                <td>{{ $s->quantity }}</td>
                <td>{{ $s->price }}</td>
                <td>{{ $s->total }}</td>
                <td>{{ $s->sale_date }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
