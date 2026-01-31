@extends('layouts.app')

@section('content')

    {{-- ================= ERRORS ================= --}}
    @if ($errors->any())
        <div style="max-width:600px;margin:20px auto;background:#fee2e2;padding:15px;border-radius:10px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li style="color:#b91c1c;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="min-height:100vh;background:#f8fafc;padding:40px;font-family:Inter,sans-serif">

        <div style="max-width:600px;margin:auto">
            <div style="background:#fff;padding:40px;border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,.08)">

                <h2 style="font-size:28px;font-weight:800;margin-bottom:10px">💳 Record Payment</h2>
                <p style="color:#6b7280;margin-bottom:25px">
                    Invoice #{{ $sale->invoice_no }}
                </p>

                <div style="background:#eef2ff;padding:20px;border-radius:16px;margin-bottom:25px">
                    <strong>Payable Amount</strong>
                    <div style="font-size:32px;font-weight:900;color:#1e40af">
                        ₹ {{ number_format($remaining, 2) }}

                    </div>
                </div>

                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf

                    {{-- REQUIRED --}}
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                    {{-- SINGLE SOURCE OF TRUTH --}}
                    <input type="hidden" name="amount" id="amountField" value="{{ $remaining }}">




                    {{-- ================= METHODS ================= --}}
                    <h4 style="margin-bottom:12px">Payment Method</h4>

                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
                        @php
                            $methods = [
                                ['cash', '💵 Cash'],
                                ['upi', '📱 UPI'],
                                ['card', '💳 Card'],
                                ['net_banking', '🌐 Net Banking'],
                                ['emi', '📆 EMI'],
                                ['advance', '👛 Advance'],
                            ];
                        @endphp

                        @foreach ($methods as [$val, $label])
                            <label class="method-option"
                                style="border:2px solid #e5e7eb;padding:16px;border-radius:12px;cursor:pointer">
                                <input type="radio" name="method" value="{{ $val }}" style="display:none">
                                <div style="font-weight:600">{{ $label }}</div>
                            </label>
                        @endforeach
                    </div>

                    {{-- TRANSACTION --}}
                    <div style="margin-top:25px">
                        <label>Transaction ID (optional)</label>
                        <input name="transaction_id"
                            style="width:100%;padding:12px;border-radius:10px;border:1px solid #e5e7eb">
                    </div>

                    {{-- ================= EMI ================= --}}
                    <div id="emiBox"
                        style="display:none;margin-top:20px;padding:20px;border:1px dashed #c7d2fe;border-radius:12px">

                        <h4>📆 EMI Details</h4>

                        <label>Down Payment</label>
                        <input type="number" min="1" id="downPayment"
                            style="width:100%;padding:10px;margin-bottom:10px">

                        <label>EMI Months</label>
                        <input type="number" name="emi_months" min="1" id="emiMonths"
                            style="width:100%;padding:10px;margin-bottom:10px">

                        <label>Monthly EMI Amount</label>
                        <input type="number" name="emi_amount" id="emiAmount" readonly
                            style="width:100%;padding:10px;background:#f9fafb">
                    </div>

                    {{-- ================= ADVANCE ================= --}}
                    <div id="advanceBox"
                        style="display:none;margin-top:20px;background:#ecfeff;padding:20px;border-radius:14px;border:1px dashed #67e8f9">

                        <h4 style="margin-bottom:12px;color:#155e75;">👛 Advance Payment</h4>

                        <label>Advance Amount</label>
                        <input type="number" name="advance_amount" min="1"
                            style="width:100%;padding:14px;border-radius:10px;border:1px solid #e5e7eb">

                        <p style="margin-top:10px;font-size:13px;color:#0369a1">
                            Amount will be adjusted in customer open balance

                        </p>
                    </div>

                    {{-- ================= UPI QR ================= --}}
                    <div id="upiQRBox" style="display:none;margin-top:20px;text-align:center">
                        <img id="upiQRImage" style="width:220px">
                    </div>

                    <button type="submit"
                        style="margin-top:30px;width:100%;padding:16px;font-size:16px;font-weight:700;
                    background:#10b981;color:#fff;border:none;border-radius:12px">
                        Confirm Payment
                    </button>

                </form>
            </div>
        </div>
    </div>

    {{-- ================= SCRIPT ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const radios = document.querySelectorAll('input[name="method"]');
            const emiBox = document.getElementById('emiBox');
            const advBox = document.getElementById('advanceBox');
            const qrBox = document.getElementById('upiQRBox');
            const qrImg = document.getElementById('upiQRImage');

            const amountField = document.getElementById('amountField');
            const downPayment = document.getElementById('downPayment');
            const emiMonths = document.getElementById('emiMonths');
            const emiAmount = document.getElementById('emiAmount');

            const grandTotal = {{ $remaining }};


            function resetUI() {
                emiBox.style.display = 'none';
                advBox.style.display = 'none';
                qrBox.style.display = 'none';
                amountField.value = grandTotal;

                document.querySelectorAll('.method-option').forEach(el => {
                    el.style.borderColor = '#e5e7eb';
                    el.style.background = '#fff';
                });
            }

            function calculateEMI() {
                const down = Number(downPayment.value || 0);
                const months = Number(emiMonths.value || 0);

                if (down > 0 && months > 0 && down < grandTotal) {
                    const remaining = grandTotal - down;
                    const monthly = (remaining / months).toFixed(2);

                    emiAmount.value = monthly;
                    amountField.value = down; // ✅ first payment = down payment
                } else {
                    emiAmount.value = '';
                    amountField.value = 0;
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', function() {

                    resetUI();

                    this.parentNode.style.borderColor = '#3b82f6';
                    this.parentNode.style.background = '#eff6ff';

                    if (this.value === 'emi') {
                        emiBox.style.display = 'block';
                        amountField.value = 0;
                    }

                    if (this.value === 'advance') {
                        advBox.style.display = 'block';
                        amountField.value = 1; // dummy safe value
                    }


                    if (this.value === 'upi') {
                        const u =
                            `upi://pay?pa=rathodramsing80-4@okaxis&pn=OK PVT LTD&am=${grandTotal}&cu=INR`;
                        qrImg.src =
                            `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(u)}`;
                        qrBox.style.display = 'block';
                    }
                });
            });

            downPayment.addEventListener('input', calculateEMI);
            emiMonths.addEventListener('input', calculateEMI);

            // default cash
            document.querySelector('input[value="cash"]').checked = true;
            amountField.value = grandTotal;
        });
    </script>

@endsection
