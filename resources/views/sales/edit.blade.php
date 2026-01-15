@extends('layouts.app')

@section('content')
    <div
        style="max-width:1200px;margin:30px auto;background:#fff;
padding:30px;border-radius:16px;
box-shadow:0 15px 35px rgba(0,0,0,.08);">

        <h2 style="margin-bottom:20px;font-size:26px;font-weight:700;">
            ✏️ Edit Invoice {{ $sale->invoice_no }}
        </h2>

        <form method="POST" action="{{ route('sales.update', $sale->id) }}">
            @csrf
            @method('PUT')

            {{-- CUSTOMER (LOCKED) --}}
            <div style="margin-bottom:20px;">
                <label style="font-weight:700;">Customer</label><br>
                <input type="text" value="{{ $sale->customer->name ?? 'Walk-in Customer' }}" readonly
                    style="width:320px;padding:10px;border-radius:8px;background:#f3f4f6;">
                <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">
            </div>

            {{-- PRODUCT SEARCH --}}
            <div style="margin-bottom:15px;position:relative;">
                <label style="font-weight:700;">Add Product</label><br>
                <input type="text" id="productSearch" placeholder="Type product name..."
                    style="width:320px;padding:10px;border-radius:8px;border:1px solid #d1d5db;">
                <div id="productResults"
                    style="position:absolute;top:70px;width:320px;background:#fff;
border:1px solid #e5e7eb;border-radius:10px;
display:none;z-index:999;
box-shadow:0 12px 25px rgba(0,0,0,.15);">
                </div>
            </div>

            {{-- ITEMS TABLE --}}
            <table id="itemsTable" style="width:100%;border-collapse:collapse;margin-top:20px;font-size:14px;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th>Product</th>
                        <th width="120">Price</th>
                        <th width="90">Qty</th>
                        <th width="130">Total</th>
                        <th width="60"></th>
                    </tr>
                </thead>
                <tbody>

                    {{-- EXISTING ITEMS --}}
                    @foreach ($sale->items as $item)
                        <tr data-pid="{{ $item->product_id }}">
                            <td>
                                {{ $item->product->name }}
                                <input type="hidden" name="items[product_id][]" value="{{ $item->product_id }}">
                            </td>

                            <td>
                                <input name="items[price][]" value="{{ $item->price }}" oninput="calculate()">
                            </td>

                            <td>
                                <input type="number" name="items[quantity][]" value="{{ $item->quantity }}" min="1"
                                    max="{{ $item->product->quantity + $item->quantity }}" oninput="calculate()">
                            </td>

                            <td>
                                <input name="items[total][]" value="{{ $item->total }}" readonly>
                            </td>

                            <td style="text-align:center;">
                                <button type="button" onclick="this.closest('tr').remove();calculate()"
                                    style="background:#ef4444;color:#fff;border:none;
padding:6px 10px;border-radius:6px;">
                                    ✕
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <hr style="margin:25px 0;">

            {{-- TOTALS --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:15px;">
                <div>
                    <label>Sub Total</label>
                    <input id="sub_total" name="sub_total" value="{{ $sale->sub_total }}" readonly>
                </div>

                <div>
                    <label>Discount</label>
                    <input id="discount" name="discount" value="{{ $sale->discount }}" oninput="calculate()">
                </div>

                <div>
                    <label>Tax (%)</label>
                    <input id="tax" name="tax" value="{{ $sale->tax }}" oninput="calculate()">
                </div>

                <div>
                    <label style="font-weight:700;color:#065f46;">Grand Total</label>
                    <input id="grand_total" name="grand_total" value="{{ $sale->grand_total }}" readonly
                        style="border:2px solid #10b981;background:#ecfdf5;font-weight:700;">
                </div>
            </div>

            <br>

            <button type="submit"
                style="background:#111827;color:#fff;padding:14px 26px;
border:none;border-radius:10px;font-size:15px;font-weight:600;">
                💾 Update Invoice
            </button>

        </form>
    </div>

    {{-- ================= JS ================= --}}
    <script>
        let products = @json($products);
        const productSearch = document.getElementById('productSearch');
        const productResults = document.getElementById('productResults');
        const tbody = document.querySelector('#itemsTable tbody');

        productSearch.addEventListener('input', function() {
            let val = this.value.toLowerCase();
            productResults.innerHTML = '';
            if (!val) return productResults.style.display = 'none';

            products.filter(p => p.name.toLowerCase().includes(val))
                .forEach(p => {
                    let d = document.createElement('div');
                    d.innerHTML = `${p.name} <small>(Stock: ${p.quantity})</small>`;
                    d.style.padding = '10px';
                    d.style.cursor = 'pointer';
                    d.onclick = () => addOrIncrease(p);
                    productResults.appendChild(d);
                });
            productResults.style.display = 'block';
        });

        function addOrIncrease(p) {
            productSearch.value = '';
            productResults.style.display = 'none';

            let found = false;

            tbody.querySelectorAll('tr').forEach(row => {
                if (row.dataset.pid == p.id) {
                    let qty = row.querySelector('[name="items[quantity][]"]');
                    qty.value = parseInt(qty.value) + 1;
                    found = true;
                }
            });

            if (!found) {
                tbody.insertAdjacentHTML('beforeend', `
<tr data-pid="${p.id}">
<td>${p.name}
<input type="hidden" name="items[product_id][]" value="${p.id}">
</td>
<td>
<input name="items[price][]" value="${p.price}" oninput="calculate()">
</td>
<td>
<input type="number" name="items[quantity][]" value="1" min="1" oninput="calculate()">
</td>
<td>
<input name="items[total][]" readonly>
</td>
<td>
<button type="button"
onclick="this.closest('tr').remove();calculate()">✕</button>
</td>
</tr>
`);
            }
            calculate();
        }

        function calculate() {
            let sub = 0;
            tbody.querySelectorAll('tr').forEach(r => {
                let q = parseFloat(r.querySelector('[name="items[quantity][]"]').value) || 0;
                let p = parseFloat(r.querySelector('[name="items[price][]"]').value) || 0;
                let t = q * p;
                r.querySelector('[name="items[total][]"]').value = t.toFixed(2);
                sub += t;
            });
            sub_total.value = sub.toFixed(2);
            grand_total.value =
                (sub - (discount.value || 0) + (sub * (tax.value || 0) / 100)).toFixed(2);
        }
    </script>
@endsection
