@extends('layouts.app')

@section('content')
    <div
        style="
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 40px 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
">

        <div style="
        max-width: 600px;
        margin: 0 auto;
    ">
            {{-- Card Container --}}
            <div
                style="
            background: linear-gradient(145deg, #ffffff 0%, #fefefe 100%);
            border-radius: 24px;
            padding: 40px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(255, 255, 255, 0.9) inset;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            position: relative;
            overflow: hidden;
        ">
                {{-- Decorative Elements --}}
                <div
                    style="
                position: absolute;
                top: 0;
                right: 0;
                width: 120px;
                height: 120px;
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(124, 58, 237, 0.05) 100%);
                border-radius: 0 24px 0 60px;
            ">
                </div>

                <div
                    style="
                position: absolute;
                bottom: 0;
                left: 0;
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
                border-radius: 0 40px 0 0;
            ">
                </div>

                {{-- Header --}}
                <div style="margin-bottom: 32px; position: relative;">
                    <div
                        style="
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    margin-bottom: 12px;
                ">
                        <div
                            style="
                        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
                        width: 56px;
                        height: 56px;
                        border-radius: 16px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
                    ">
                            <span style="font-size: 28px; color: white;">💳</span>
                        </div>

                        <div>
                            <h1
                                style="
                            margin: 0;
                            font-size: 32px;
                            font-weight: 800;
                            color: #1f2937;
                            letter-spacing: -0.5px;
                            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                        ">
                                Record Payment
                            </h1>
                            <p
                                style="
                            margin: 6px 0 0 0;
                            color: #6b7280;
                            font-size: 15px;
                            font-weight: 500;
                        ">
                                Complete the payment for invoice
                                <span
                                    style="
                                background: #f3f4f6;
                                color: #374151;
                                padding: 4px 12px;
                                border-radius: 20px;
                                font-family: 'JetBrains Mono', monospace;
                                font-weight: 600;
                                margin-left: 8px;
                            ">
                                    #{{ $sale->invoice_no }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Amount Display --}}
                <div
                    style="
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                border-radius: 16px;
                padding: 24px;
                margin-bottom: 32px;
                border: 1px solid rgba(59, 130, 246, 0.2);
                position: relative;
                overflow: hidden;
            ">
                    <div
                        style="
                    position: absolute;
                    top: -50px;
                    right: -50px;
                    width: 100px;
                    height: 100px;
                    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
                    border-radius: 50%;
                ">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div
                                style="
                            color: #1e40af;
                            font-size: 15px;
                            font-weight: 600;
                            margin-bottom: 8px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                                📋 Payable Amount
                            </div>
                            <div
                                style="
                            color: #374151;
                            font-size: 15px;
                            font-weight: 500;
                        ">
                                Invoice Total
                            </div>
                        </div>

                        <div
                            style="
                        font-size: 36px;
                        font-weight: 900;
                        color: #1e40af;
                        font-family: 'JetBrains Mono', monospace;
                        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        text-shadow: 0 2px 4px rgba(30, 64, 175, 0.1);
                    ">
                            ₹{{ number_format($sale->grand_total, 2) }}
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('payments.store') }}" style="position: relative;">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    <input type="hidden" name="amount" value="{{ $sale->grand_total }}">

                    {{-- Payment Method --}}
                    <div style="margin-bottom: 28px;">
                        <label
                            style="
                        display: block;
                        color: #374151;
                        font-weight: 600;
                        font-size: 15px;
                        margin-bottom: 12px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                            🔧 Payment Method
                        </label>

                        <div
                            style="
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 12px;
                        margin-bottom: 20px;
                    ">
                            <label
                                style="
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            padding: 18px;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: white;
                            position: relative;
                        "
                                onmouseover="this.style.borderColor='#d1d5db'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='translateY(0)';">
                                <input type="radio" name="method" value="cash" required style="display: none;"
                                    onchange="document.querySelectorAll('.method-option').forEach(el => el.style.background='white'); this.parentNode.style.background='#f0f9ff'; this.parentNode.style.borderColor='#3b82f6';">
                                <div
                                    style="
                                font-size: 24px;
                                margin-bottom: 8px;
                            ">
                                    💵
                                </div>
                                <div
                                    style="
                                font-weight: 600;
                                color: #374151;
                                font-size: 15px;
                            ">
                                    Cash
                                </div>
                            </label>

                            <label
                                style="
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            padding: 18px;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: white;
                        "
                                onmouseover="this.style.borderColor='#d1d5db'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='translateY(0)';">
                                <input type="radio" name="method" value="upi" required style="display: none;"
                                    onchange="document.querySelectorAll('.method-option').forEach(el => el.style.background='white'); this.parentNode.style.background='#f0f9ff'; this.parentNode.style.borderColor='#3b82f6';">
                                <div
                                    style="
                                font-size: 24px;
                                margin-bottom: 8px;
                            ">
                                    📱
                                </div>
                                <div
                                    style="
                                font-weight: 600;
                                color: #374151;
                                font-size: 15px;
                            ">
                                    UPI
                                </div>
                            </label>

                            <label
                                style="
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            padding: 18px;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: white;
                        "
                                onmouseover="this.style.borderColor='#d1d5db'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='translateY(0)';">
                                <input type="radio" name="method" value="card" required style="display: none;"
                                    onchange="document.querySelectorAll('.method-option').forEach(el => el.style.background='white'); this.parentNode.style.background='#f0f9ff'; this.parentNode.style.borderColor='#3b82f6';">
                                <div
                                    style="
                                font-size: 24px;
                                margin-bottom: 8px;
                            ">
                                    💳
                                </div>
                                <div
                                    style="
                                font-weight: 600;
                                color: #374151;
                                font-size: 15px;
                            ">
                                    Card
                                </div>
                            </label>

                            <label
                                style="
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            padding: 18px;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: white;
                        "
                                onmouseover="this.style.borderColor='#d1d5db'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.parentNode.style.transform='translateY(0)';">
                                <input type="radio" name="method" value="net_banking" required style="display: none;"
                                    onchange="document.querySelectorAll('.method-option').forEach(el => el.style.background='white'); this.parentNode.style.background='#f0f9ff'; this.parentNode.style.borderColor='#3b82f6';">
                                <div
                                    style="
                                font-size: 24px;
                                margin-bottom: 8px;
                            ">
                                    🌐
                                </div>
                                <div
                                    style="
                                font-weight: 600;
                                color: #374151;
                                font-size: 15px;
                            ">
                                    Net Banking
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Transaction ID --}}
                    <div style="margin-bottom: 36px;">
                        <label
                            style="
                        display: block;
                        color: #374151;
                        font-weight: 600;
                        font-size: 15px;
                        margin-bottom: 12px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                            🔗 Transaction ID (Optional)
                        </label>

                        <input type="text" name="transaction_id"
                            placeholder="Enter UPI reference, bank transaction ID, etc."
                            style="
                            width: 100%;
                            padding: 16px 20px;
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            font-size: 15px;
                            font-family: 'Inter', sans-serif;
                            color: #374151;
                            background: white;
                            transition: all 0.2s ease;
                            box-sizing: border-box;
                           "
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                        <div
                            style="
                        color: #6b7280;
                        font-size: 13px;
                        margin-top: 8px;
                    ">
                            Leave blank for cash payments
                        </div>
                    </div>
                    {{-- 🔥 UPI QR CODE BOX --}}
                    <div id="upiQRBox"
                        style="
        display:none;
        margin-top:20px;
        text-align:center;
        background:#f9fafb;
        padding:20px;
        border-radius:14px;
        border:1px dashed #94a3b8;
     ">

                        <p style="font-weight:700;color:#1f2937;">
                            📱 Scan this QR to pay via UPI
                        </p>

                        <img id="upiQRImage" src=""
                            style="
            width:220px;
            height:220px;
            margin:10px 0;
            border-radius:12px;
            border:1px solid #e5e7eb;
         ">

                        <p style="font-size:14px;color:#16a34a;font-weight:700;">
                            Amount: ₹ {{ number_format($sale->grand_total, 2) }}
                        </p>

                        <p style="font-size:12px;color:#6b7280;">
                            After payment, click <b>Confirm Payment</b>
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div
                        style="
                    display: flex;
                    gap: 16px;
                    margin-top: 40px;
                    padding-top: 28px;
                    border-top: 1px solid #f1f5f9;
                ">
                        <button type="submit"
                            style="
                                flex: 1;
                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                color: white;
                                padding: 18px 32px;
                                border: none;
                                border-radius: 12px;
                                font-size: 16px;
                                font-weight: 700;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 12px;
                                box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
                            "
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(16, 185, 129, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.3)';">
                            <span>✅</span>
                            Confirm Payment
                        </button>

                        <a href="{{ route('sales.show', $sale->id) }}"
                            style="
                            padding: 18px 32px;
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            font-size: 16px;
                            font-weight: 600;
                            text-decoration: none;
                            color: #6b7280;
                            text-align: center;
                            transition: all 0.3s ease;
                            background: white;
                        "
                            onmouseover="this.style.borderColor='#d1d5db'; this.style.color='#374151'; this.style.transform='translateY(-2px)';"
                            onmouseout="this.style.borderColor='#e5e7eb'; this.style.color='#6b7280'; this.style.transform='translateY(0)';">
                            Cancel
                        </a>
                    </div>
                </form>

                {{-- Footer Note --}}
                <div
                    style="
                margin-top: 32px;
                padding-top: 20px;
                border-top: 1px solid #f1f5f9;
                text-align: center;
                color: #9ca3af;
                font-size: 13px;
            ">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span>⚠️</span>
                        <span>Payment once recorded cannot be undone</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');

        input:focus,
        button:focus {
            outline: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ===============================
               CONFIG
            =============================== */
            const upiId = "rathodramsing80-4@okaxis"; // ✅ your UPI
            const upiName = "OK PVT LTD"; // ✅ business name
            const amount = "{{ $sale->grand_total }}";
            const invoice = "{{ $sale->invoice_no }}";

            /* ===============================
               ELEMENTS
            =============================== */
            const qrBox = document.getElementById('upiQRBox');
            const qrImg = document.getElementById('upiQRImage');
            const radios = document.querySelectorAll('input[name="method"]');
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            /* ===============================
               QR HANDLER
            =============================== */
            function handlePaymentMethod(method) {

                if (method === 'upi') {

                    const upiURL =
                        `upi://pay?pa=${upiId}&pn=${encodeURIComponent(upiName)}&am=${amount}&cu=INR&tn=Invoice ${invoice}`;

                    const qrURL =
                        `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiURL)}`;

                    qrImg.src = qrURL;
                    qrBox.style.display = 'block';

                } else {
                    qrBox.style.display = 'none';
                    qrImg.src = '';
                }
            }

            /* ===============================
               RADIO CHANGE EVENTS
            =============================== */
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    handlePaymentMethod(this.value);
                });
            });

            /* ===============================
               DEFAULT = CASH
            =============================== */
            const cashRadio = document.querySelector('input[name="method"][value="cash"]');
            if (cashRadio) {
                cashRadio.checked = true;
                handlePaymentMethod('cash');
            }

            /* ===============================
               FORM SUBMIT HANDLER
            =============================== */
            form.addEventListener('submit', function() {

                // 🔥 hide QR once user confirms
                qrBox.style.display = 'none';

                // 🔒 prevent double submit
                submitBtn.disabled = true;
                submitBtn.innerHTML = '⏳ Processing Payment...';
            });

        });
    </script>
@endsection
