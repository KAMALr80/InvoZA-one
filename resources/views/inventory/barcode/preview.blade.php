<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }

        .label {
            border: 1px dashed #999;
            padding: 12px;
            text-align: center;
            min-height: 140px;
        }

        .name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .code {
            font-size: 12px;
            margin-top: 6px;
        }

        .barcode {
            margin: 8px auto;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            @foreach ($products as $index => $p)
                @php
                    $dns1d = new \Milon\Barcode\DNS1D();
                @endphp

                <td>
                    <div class="label">
                        <div class="name">{{ $p->name }}</div>

                        <div class="barcode">
                            {!! $dns1d->getBarcodeHTML($p->product_code, 'C128', 2, 60) !!}
                        </div>

                        <div class="code">{{ $p->product_code }}</div>
                    </div>
                </td>

                {{-- 2 labels per row --}}
                @if (($index + 1) % 2 == 0)
        </tr>
        <tr>
            @endif
            @endforeach
        </tr>
    </table>

</body>

</html>
