@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div
        style="
background:#ffffff;
padding:35px;
border-radius:16px;
max-width:1250px;
margin:30px auto;
box-shadow:0 15px 35px rgba(0,0,0,0.08);
font-family:'Segoe UI', Tahoma, sans-serif;
">

        <h2 style="font-size:28px;font-weight:800;margin-bottom:25px;">
            🧾 Create Sale / Invoice
        </h2>

        <form method="POST" action="{{ route('sales.store') }}" onsubmit="handleSubmit(this)">
            @csrf

            {{-- 🔐 LEVEL-2 TOKEN --}}
            <input type="hidden" name="invoice_token" value="{{ Str::uuid() }}">

            {{-- ================= CUSTOMER + SEARCH ================= --}}
            <div style="display:flex;gap:14px;align-items:flex-end;margin-bottom:25px;flex-wrap:wrap;">

                <div>
                    <label><b>Customer</b></label><br>
                    <select id="customerSelect" name="customer_id"
                        style="width:280px;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="openCustomerModal()"
                    style="height:44px;background:#2563eb;color:#fff;border:none;
        padding:0 18px;border-radius:10px;font-weight:600;">
                    ➕ Add Customer
                </button>

                <div style="position:relative;">
                    <label><b>Search Product</b></label><br>
                    <input type="text" id="productSearch" disabled placeholder="Type product name..."
                        style="width:320px;padding:11px;border-radius:10px;border:1px solid #d1d5db;">
                    <div id="productResults"
                        style="display:none;position:absolute;top:70px;width:100%;
            background:#fff;border:1px solid #e5e7eb;border-radius:10px;
            box-shadow:0 12px 30px rgba(0,0,0,.15);
            max-height:220px;overflow-y:auto;z-index:999;">
                    </div>
                </div>
            </div>

            {{-- ================= ITEMS TABLE ================= --}}
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="itemsTable"></tbody>
            </table>

            {{-- ================= TOTALS ================= --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:25px;">
                <div>
                    <label>Sub Total</label>
                    <input id="sub_total" name="sub_total" readonly>
                </div>
                <div>
                    <label>Discount</label>
                    <input id="discount" name="discount" value="0" oninput="calculate()">
                </div>
                <div>
                    <label>Tax (%)</label>
                    <input id="tax" name="tax" value="0" oninput="calculate()">
                </div>
                <div>
                    <label><b>Grand Total</b></label>
                    <input id="grand_total" name="grand_total" readonly>
                </div>
            </div>

            <br>

            <button type="submit" id="saveBtn"
                style="background:#111827;color:#fff;padding:14px 32px;
border:none;border-radius:12px;font-weight:700;">
                💾 Save & Generate Invoice
            </button>

        </form>
    </div>

    {{-- ================= CUSTOMER MODAL ================= --}}
    <div id="customerModal"
        style="display:none;position:fixed;inset:0;
background:rgba(0,0,0,.6);z-index:9999;
align-items:center;justify-content:center;">
        <div style="background:#fff;width:420px;padding:22px;border-radius:14px;">
            <h3>Add Customer</h3>

            <input id="c_name" placeholder="Name" style="width:100%;margin-bottom:8px;">
            <input id="c_mobile" placeholder="Mobile" style="width:100%;margin-bottom:8px;">
            <input id="c_email" placeholder="Email" style="width:100%;margin-bottom:8px;">
            <textarea id="c_address" placeholder="Address" style="width:100%;margin-bottom:10px;"></textarea>

            <div style="text-align:right;">
                <button onclick="closeCustomerModal()">Cancel</button>
                <button onclick="saveCustomer()" style="background:#16a34a;color:#fff;">
                    Save
                </button>
            </div>
        </div>
    </div>

    {{-- ================= JS ================= --}}
    <script>
        let products = @json($products);

        const customerSelect = document.getElementById('customerSelect');
        const productSearch = document.getElementById('productSearch');
        const productResults = document.getElementById('productResults');
        const itemsTable = document.getElementById('itemsTable');

        customerSelect.addEventListener('change', () => {
            productSearch.disabled = customerSelect.value === "";
        });

        productSearch.addEventListener('input', function() {
            let val = this.value.toLowerCase();
            productResults.innerHTML = '';
            if (!val) return productResults.style.display = 'none';

            products.filter(p => p.name.toLowerCase().includes(val))
                .forEach(p => {
                    let d = document.createElement('div');
                    d.innerHTML = `<b>${p.name}</b> (₹${p.price})`;
                    d.style.padding = '10px';
                    d.style.cursor = 'pointer';
                    d.onclick = () => addProduct(p);
                    productResults.appendChild(d);
                });

            productResults.style.display = 'block';
        });

        function addProduct(p) {
            productResults.style.display = 'none';
            productSearch.value = '';

            let found = false;
            document.querySelectorAll('#itemsTable tr').forEach(row => {
                if (row.dataset.pid == p.id) {
                    let q = row.querySelector('.qty');
                    q.value = parseInt(q.value) + 1;
                    found = true;
                }
            });

            if (!found) {
                itemsTable.insertAdjacentHTML('beforeend', `
        <tr data-pid="${p.id}">
            <td>${p.name}
                <input type="hidden" name="items[product_id][]" value="${p.id}">
            </td>
            <td><input name="items[price][]" value="${p.price}" oninput="calculate()"></td>
            <td><input type="number" class="qty" name="items[quantity][]" value="1" min="1" oninput="calculate()"></td>
            <td><input name="items[total][]" readonly></td>
            <td>
                <button type="button" onclick="this.closest('tr').remove();calculate()">✕</button>
            </td>
        </tr>
        `);
            }
            calculate();
        }

        function calculate() {
            let sub = 0;
            document.querySelectorAll('#itemsTable tr').forEach(r => {
                let q = parseFloat(r.querySelector('.qty').value) || 0;
                let p = parseFloat(r.querySelector('[name="items[price][]"]').value) || 0;
                let t = q * p;
                r.querySelector('[name="items[total][]"]').value = t.toFixed(2);
                sub += t;
            });
            sub_total.value = sub.toFixed(2);
            grand_total.value =
                (sub - discount.value + (sub * tax.value / 100)).toFixed(2);
        }

        function handleSubmit(form) {
            const btn = document.getElementById('saveBtn');
            btn.disabled = true;
            btn.innerText = 'Saving...';
        }

        function openCustomerModal() {
            customerModal.style.display = 'flex';
        }

        function closeCustomerModal() {
            customerModal.style.display = 'none';
        }

        function saveCustomer() {
            fetch("{{ route('customers.store.ajax') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: c_name.value,
                        mobile: c_mobile.value,
                        email: c_email.value,
                        address: c_address.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    let opt = document.createElement('option');
                    opt.value = data.customer.id;
                    opt.text = data.customer.name;
                    opt.selected = true;
                    customerSelect.appendChild(opt);
                    productSearch.disabled = false;
                    closeCustomerModal();
                });
        }
    </script>
@endsection
