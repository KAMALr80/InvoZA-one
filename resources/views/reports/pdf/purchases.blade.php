<!DOCTYPE html>
<html>
<head>
    <title>Purchase Report</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Purchase Report</h2>

<table>
    <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        <th>Date</th>
    </tr>

    @foreach($purchases as $p)
    <tr>
        <td>{{ $p->product->name }}</td>
        <td>{{ $p->quantity }}</td>
        <td>{{ $p->price }}</td>
        <td>{{ $p->total }}</td>
        <td>{{ $p->purchase_date }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
