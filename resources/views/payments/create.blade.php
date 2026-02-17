@extends('layouts.app')

@section('content')
<div style="min-height:100vh;background:#f3f4f6;padding:30px 20px;font-family:system-ui,-apple-system,sans-serif;">
    <div style="max-width:800px;margin:auto;">

        {{-- Error Messages --}}
        @if ($errors->any())
            <div style="background:#fee2e2;border-left:4px solid #dc2626;padding:16px 20px;border-radius:12px;margin-bottom:24px;">
                <ul style="margin:0;padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li style="color:#991b1b;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Messages --}}
        @if (session('success'))
            <div style="background:#dcfce7;border-left:4px solid #16a34a;padding:16px 20px;border-radius:12px;margin-bottom:24px;">
                <p style="color:#166534;margin:0;">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Main Card --}}
        <div style="background:white;border-radius:28px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);overflow:hidden;">

            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#2563eb 0%,#7c3aed 100%);padding:30px;color:white;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <h1 style="margin:0;font-size:28px;font-weight:700;">💳 Record Payment</h1>
                        <p style="margin:8px 0 0;opacity:0.9;">Invoice #{{ $sale->invoice_no }}</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.2);padding:8px 20px;border-radius:40px;font-weight:600;">
                        {{ strtoupper($sale->payment_status) }}
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div style="padding:30px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
                    <div style="background:white;padding:20px;border-radius:18px;">
                        <div style="color:#64748b;font-size:14px;">Grand Total</div>
                        <div style="font-size:24px;font-weight:700;">₹ {{ number_format($sale->grand_total, 2) }}</div>
                    </div>
                    <div style="background:white;padding:20px;border-radius:18px;">
                        <div style="color:#64748b;font-size:14px;">Paid Amount</div>
                        <div style="font-size:24px;font-weight:700;color:#059669;">₹ {{ number_format($paidAmount, 2) }}</div>
                    </div>
                    <div style="background:white;padding:20px;border-radius:18px;">
                        <div style="color:#64748b;font-size:14px;">Due Amount</div>
                        <div style="font-size:24px;font-weight:700;color:#dc2626;">₹ {{ number_format($remaining, 2) }}</div>
                    </div>
                </div>
            </div>

            {{-- Customer Balance --}}
            @if($sale->customer)
            <div style="padding:20px 30px;background:white;border-bottom:1px solid #e2e8f0;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:15px;">
                        <div style="width:50px;height:50px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:24px;">👤</span>
                        </div>
                        <div>
                            <div style="color:#0f172a;font-weight:600;">{{ $sale->customer->name }}</div>
                            <div style="color:#64748b;font-size:13px;">{{ $sale->customer->mobile ?? '' }}</div>
                        </div>
                    </div>

                    @if($advanceBalance > 0)
                        <div style="background:#dcfce7;padding:10px 20px;border-radius:40px;">
                            <div style="color:#64748b;font-size:12px;">Advance Balance</div>
                            <div style="color:#059669;font-weight:700;font-size:20px;">₹ {{ number_format($advanceBalance, 2) }}</div>
                        </div>
                    @elseif($dueBalance > 0)
                        <div style="background:#fee2e2;padding:10px 20px;border-radius:40px;">
                            <div style="color:#64748b;font-size:12px;">Previous Due</div>
                            <div style="color:#b91c1c;font-weight:700;font-size:20px;">₹ {{ number_format($dueBalance, 2) }}</div>
                        </div>
                    @else
                        <div style="background:#f1f5f9;padding:10px 20px;border-radius:40px;">
                            <div style="color:#64748b;font-size:12px;">Account</div>
                            <div style="color:#475569;font-weight:700;">Clear</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
z
            {{-- Payment Form --}}
            <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                <input type="hidden" name="payment_type" id="payment_type" value="full">
                <input type="hidden" name="is_advance_only" id="is_advance_only" value="0">

                <div style="padding:30px;">

                    {{-- Payment Type Tabs --}}
                    <div style="display:flex;gap:10px;background:#f1f5f9;padding:6px;border-radius:14px;margin-bottom:30px;">
                        <button type="button" class="tab-btn active" id="tabInvoice"
                            style="flex:1;padding:14px;border:none;border-radius:12px;font-weight:600;cursor:pointer;background:#2563eb;color:white;">
                            🧾 Invoice Payment
                        </button>
                        <button type="button" class="tab-btn" id="tabAdvance"
                            style="flex:1;padding:14px;border:none;border-radius:12px;font-weight:600;cursor:pointer;background:transparent;color:#475569;">
                            👛 Pure Advance
                        </button>
                    </div>

                    {{-- Invoice Payment Section --}}
                    <div id="invoiceSection">

                        {{-- Advance Usage Section --}}
                        @if($advanceBalance > 0)
                        <div style="background:#f0fdf4;border:2px solid #86efac;border-radius:20px;padding:20px;margin-bottom:30px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;">
                                <div>
                                    <div style="color:#166534;font-weight:600;">Available Advance</div>
                                    <div style="font-size:28px;font-weight:800;color:#059669;">₹ {{ number_format($advanceBalance, 2) }}</div>
                                </div>
                                <label style="display:flex;align-items:center;gap:10px;background:white;padding:10px 20px;border-radius:40px;cursor:pointer;">
                                    <input type="checkbox" id="useAdvanceCheckbox" style="width:18px;height:18px;">
                                    <span style="font-weight:600;">Use Advance</span>
                                </label>
                            </div>

                            <div id="advanceUseBox" style="display:none;margin-top:15px;">
                                <label style="font-weight:600;color:#166534;margin-bottom:8px;display:block;">Amount to use from advance</label>
                                <input type="number" id="advanceUsed" name="advance_used" min="0" max="{{ $advanceBalance }}" step="0.01" value="0"
                                    style="width:100%;padding:15px;border:2px solid #86efac;border-radius:12px;font-size:18px;"
                                    placeholder="Enter amount">
                                <div style="display:flex;gap:10px;margin-top:10px;">
                                    <button type="button" class="advance-quick-btn" data-amount="{{ min($advanceBalance, $remaining) }}"
                                        style="flex:1;padding:8px;background:#86efac;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                                        Full (₹{{ number_format(min($advanceBalance, $remaining), 2) }})
                                    </button>
                                    <button type="button" class="advance-quick-btn" data-amount="{{ min($advanceBalance/2, $remaining) }}"
                                        style="flex:1;padding:8px;background:#86efac;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                                        50%
                                    </button>
                                </div>
                                <p style="color:#166534;font-size:14px;margin-top:8px;">Remaining advance: ₹<span id="remainingAdvance">{{ number_format($advanceBalance, 2) }}</span></p>
                            </div>
                        </div>
                        @endif

                        {{-- Cash Payment with Auto-calculation --}}
                        <div style="margin-bottom:25px;">
                            <label style="font-weight:600;margin-bottom:10px;display:block;">💰 Cash/Other Payment Amount</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="number" name="payment_amount" id="paymentAmount" step="0.01" min="0" value="{{ $remaining > 0 ? $remaining : 0 }}"
                                    style="flex:1;padding:18px;border:2px solid #e2e8f0;border-radius:16px;font-size:20px;">
                                <span id="calculatedHint" style="background:#e2e8f0;padding:10px 15px;border-radius:12px;font-weight:600;display:none;"></span>
                            </div>

                            {{-- Quick Buttons --}}
                            <div style="display:flex;gap:10px;margin-top:15px;">
                                <button type="button" class="quick-btn" data-amount="{{ $remaining }}"
                                    style="flex:1;padding:12px;background:#e2e8f0;border:none;border-radius:12px;font-weight:600;cursor:pointer;">
                                    Full (₹{{ number_format($remaining, 2) }})
                                </button>
                                <button type="button" class="quick-btn" data-amount="{{ $remaining/2 }}"
                                    style="flex:1;padding:12px;background:#e2e8f0;border:none;border-radius:12px;font-weight:600;cursor:pointer;">
                                    50%
                                </button>
                                <button type="button" class="quick-btn" data-amount="0"
                                    style="flex:1;padding:12px;background:#e2e8f0;border:none;border-radius:12px;font-weight:600;cursor:pointer;">
                                    Zero
                                </button>
                            </div>
                        </div>

                        {{-- Payment Preview --}}
                        <div id="paymentPreview" style="background:#f8fafc;padding:20px;border-radius:16px;margin-bottom:25px;display:none;"></div>
                    </div>

                    {{-- Pure Advance Section --}}
                    <div id="advanceSection" style="display:none;">
                        <div style="background:linear-gradient(135deg,#8b5cf6 0%,#6366f1 100%);padding:30px;border-radius:20px;color:white;">
                            <h3 style="margin:0 0 20px;">👛 Add to Advance Balance</h3>

                            <label style="display:block;margin-bottom:10px;">Enter Amount</label>
                            <input type="number" name="advance_amount" id="advanceAmount" step="0.01" min="1"
                                style="width:100%;padding:18px;border:2px solid rgba(255,255,255,0.3);border-radius:16px;font-size:20px;background:rgba(255,255,255,0.1);color:white;"
                                placeholder="Enter amount">

                            <div style="display:flex;gap:10px;margin-top:15px;">
                                <button type="button" class="quick-advance" data-amount="1000"
                                    style="flex:1;padding:12px;background:rgba(255,255,255,0.2);border:none;border-radius:12px;color:white;font-weight:600;cursor:pointer;">
                                    ₹1,000
                                </button>
                                <button type="button" class="quick-advance" data-amount="2000"
                                    style="flex:1;padding:12px;background:rgba(255,255,255,0.2);border:none;border-radius:12px;color:white;font-weight:600;cursor:pointer;">
                                    ₹2,000
                                </button>
                                <button type="button" class="quick-advance" data-amount="5000"
                                    style="flex:1;padding:12px;background:rgba(255,255,255,0.2);border:none;border-radius:12px;color:white;font-weight:600;cursor:pointer;">
                                    ₹5,000
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <h3 style="margin:30px 0 20px;">💳 Payment Method</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-bottom:25px;">

                        <label class="method-card active" data-method="cash"
                            style="border:2px solid #2563eb;padding:20px;border-radius:16px;cursor:pointer;text-align:center;background:#eff6ff;">
                            <input type="radio" name="method" value="cash" checked style="display:none;">
                            <span style="font-size:32px;display:block;">💵</span>
                            <span style="font-weight:600;">Cash</span>
                        </label>

                        <label class="method-card" data-method="upi"
                            style="border:2px solid #e2e8f0;padding:20px;border-radius:16px;cursor:pointer;text-align:center;">
                            <input type="radio" name="method" value="upi" style="display:none;">
                            <span style="font-size:32px;display:block;">📱</span>
                            <span style="font-weight:600;">UPI</span>
                        </label>

                        <label class="method-card" data-method="card"
                            style="border:2px solid #e2e8f0;padding:20px;border-radius:16px;cursor:pointer;text-align:center;">
                            <input type="radio" name="method" value="card" style="display:none;">
                            <span style="font-size:32px;display:block;">💳</span>
                            <span style="font-weight:600;">Card</span>
                        </label>

                        <label class="method-card" data-method="net_banking"
                            style="border:2px solid #e2e8f0;padding:20px;border-radius:16px;cursor:pointer;text-align:center;">
                            <input type="radio" name="method" value="net_banking" style="display:none;">
                            <span style="font-size:32px;display:block;">🏦</span>
                            <span style="font-weight:600;">Net Banking</span>
                        </label>

                        <label class="method-card" data-method="emi"
                            style="border:2px solid #e2e8f0;padding:20px;border-radius:16px;cursor:pointer;text-align:center;">
                            <input type="radio" name="method" value="emi" style="display:none;">
                            <span style="font-size:32px;display:block;">📆</span>
                            <span style="font-weight:600;">EMI</span>
                        </label>

                        <label class="method-card" data-method="advance"
                            style="border:2px solid #e2e8f0;padding:20px;border-radius:16px;cursor:pointer;text-align:center;">
                            <input type="radio" name="method" value="advance" style="display:none;">
                            <span style="font-size:32px;display:block;">👛</span>
                            <span style="font-weight:600;">Advance</span>
                        </label>
                    </div>

                    {{-- EMI Section --}}
                    <div id="emiSection" style="display:none;background:#fffbeb;border:2px solid #fcd34d;border-radius:20px;padding:25px;margin-bottom:25px;">
                        <h4 style="margin:0 0 20px;color:#92400e;">📆 EMI Setup</h4>

                        <div style="margin-bottom:15px;">
                            <label style="font-weight:600;margin-bottom:8px;display:block;">Down Payment</label>
                            <input type="number" id="downPayment" name="down_payment" min="1" max="{{ $remaining - 1 }}" step="0.01"
                                style="width:100%;padding:15px;border:2px solid #fcd34d;border-radius:12px;">
                        </div>

                        <div style="margin-bottom:15px;">
                            <label style="font-weight:600;margin-bottom:8px;display:block;">EMI Months</label>
                            <select id="emiMonths" name="emi_months" style="width:100%;padding:15px;border:2px solid #fcd34d;border-radius:12px;">
                                <option value="">Select</option>
                                @foreach([3,6,9,12,18,24] as $month)
                                    <option value="{{ $month }}">{{ $month }} Months</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label style="font-weight:600;margin-bottom:8px;display:block;">Monthly EMI</label>
                            <input type="number" id="emiAmount" name="emi_amount" readonly
                                style="width:100%;padding:15px;border:2px solid #fcd34d;border-radius:12px;background:#fef9c3;font-weight:600;">
                        </div>
                    </div>

                    {{-- Transaction Details --}}
                    <div style="margin-bottom:25px;">
                        <label style="font-weight:600;margin-bottom:8px;display:block;">Transaction Reference (Optional)</label>
                        <input type="text" name="transaction_id" style="width:100%;padding:15px;border:2px solid #e2e8f0;border-radius:12px;">
                    </div>

                    <div style="margin-bottom:25px;">
                        <label style="font-weight:600;margin-bottom:8px;display:block;">Remarks (Optional)</label>
                        <textarea name="remarks" rows="2" style="width:100%;padding:15px;border:2px solid #e2e8f0;border-radius:12px;"></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="submitBtn"
                        style="width:100%;padding:20px;background:linear-gradient(135deg,#2563eb 0%,#7c3aed 100%);color:white;border:none;border-radius:16px;font-size:18px;font-weight:700;cursor:pointer;">
                        <span id="submitBtnText">💰 Process Payment</span>
                    </button>

                    <div style="text-align:center;margin-top:20px;">
                        <a href="{{ route('sales.show', $sale->id) }}" style="color:#64748b;">← Back to Invoice</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.method-card { transition: all 0.2s; }
.method-card:hover { transform: translateY(-2px); }
.tab-btn { transition: all 0.2s; }
.quick-btn, .quick-advance, .advance-quick-btn { transition: all 0.2s; cursor: pointer; }
.quick-btn:hover, .quick-advance:hover, .advance-quick-btn:hover { background: #2563eb !important; color: white !important; }
input:focus, select:focus { outline: none; border-color: #2563eb !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // DOM Elements
    const invoiceSection = document.getElementById('invoiceSection');
    const advanceSection = document.getElementById('advanceSection');
    const tabInvoice = document.getElementById('tabInvoice');
    const tabAdvance = document.getElementById('tabAdvance');
    const isAdvanceOnly = document.getElementById('is_advance_only');

    const methodCards = document.querySelectorAll('.method-card');
    const emiSection = document.getElementById('emiSection');

    const paymentAmount = document.getElementById('paymentAmount');
    const advanceUsed = document.getElementById('advanceUsed');
    const useAdvanceCheckbox = document.getElementById('useAdvanceCheckbox');
    const advanceUseBox = document.getElementById('advanceUseBox');
    const remainingAdvance = document.getElementById('remainingAdvance');
    const paymentPreview = document.getElementById('paymentPreview');
    const paymentType = document.getElementById('payment_type');
    const calculatedHint = document.getElementById('calculatedHint');

    const downPayment = document.getElementById('downPayment');
    const emiMonths = document.getElementById('emiMonths');
    const emiAmount = document.getElementById('emiAmount');

    const advanceBalance = {{ $advanceBalance }};
    const dueAmount = {{ $remaining }};

    // Auto-calculate cash amount when advance changes
    function autoCalculateCash() {
        if (useAdvanceCheckbox && useAdvanceCheckbox.checked) {
            const advance = parseFloat(advanceUsed.value) || 0;
            const remainingAfterAdvance = Math.max(0, dueAmount - advance);

            // Show hint but don't auto-update the field (user can override)
            if (remainingAfterAdvance >= 0) {
                calculatedHint.textContent = `Suggested: ₹${remainingAfterAdvance.toFixed(2)}`;
                calculatedHint.style.display = 'inline-block';
            }
        } else {
            calculatedHint.style.display = 'none';
        }
    }

    // Apply suggested amount
    function applySuggestedAmount() {
        if (useAdvanceCheckbox && useAdvanceCheckbox.checked) {
            const advance = parseFloat(advanceUsed.value) || 0;
            const suggested = Math.max(0, dueAmount - advance);
            paymentAmount.value = suggested.toFixed(2);
            updatePaymentPreview();
        }
    }

    // Tab Switching
    tabInvoice.addEventListener('click', function() {
        tabInvoice.style.background = '#2563eb';
        tabInvoice.style.color = 'white';
        tabAdvance.style.background = 'transparent';
        tabAdvance.style.color = '#475569';

        invoiceSection.style.display = 'block';
        advanceSection.style.display = 'none';
        isAdvanceOnly.value = '0';

        // Enable all payment methods
        document.querySelectorAll('input[name="method"]').forEach(r => r.disabled = false);

        // Reset method selection to cash if advance was selected
        const advanceRadio = document.querySelector('input[name="method"][value="advance"]');
        if (advanceRadio && advanceRadio.checked) {
            document.querySelector('input[name="method"][value="cash"]').checked = true;
            // Update UI
            methodCards.forEach(c => {
                if (c.dataset.method === 'cash') {
                    c.style.borderColor = '#2563eb';
                    c.style.background = '#eff6ff';
                } else {
                    c.style.borderColor = '#e2e8f0';
                    c.style.background = 'white';
                }
            });
        }
    });

    tabAdvance.addEventListener('click', function() {
        tabAdvance.style.background = '#2563eb';
        tabAdvance.style.color = 'white';
        tabInvoice.style.background = 'transparent';
        tabInvoice.style.color = '#475569';

        invoiceSection.style.display = 'none';
        advanceSection.style.display = 'block';
        isAdvanceOnly.value = '1';

        // Set method to advance
        document.querySelector('input[name="method"][value="advance"]').checked = true;
        document.querySelectorAll('input[name="method"]').forEach(r => {
            if (r.value !== 'advance') r.disabled = true;
        });

        // Update method UI
        methodCards.forEach(c => {
            if (c.dataset.method === 'advance') {
                c.style.borderColor = '#2563eb';
                c.style.background = '#eff6ff';
            } else {
                c.style.borderColor = '#e2e8f0';
                c.style.background = 'white';
            }
        });

        // Hide EMI section if visible
        emiSection.style.display = 'none';
    });

    // Payment Method Selection
    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            if (this.querySelector('input').disabled) return;

            const method = this.dataset.method;

            // Update radio
            this.querySelector('input').checked = true;

            // Update UI
            methodCards.forEach(c => {
                if (c === this) {
                    c.style.borderColor = '#2563eb';
                    c.style.background = '#eff6ff';
                } else {
                    c.style.borderColor = '#e2e8f0';
                    c.style.background = 'white';
                }
            });

            // Show/hide EMI section
            emiSection.style.display = method === 'emi' ? 'block' : 'none';

            // If EMI is selected, hide advance usage section (EMI can't use advance)
            if (method === 'emi' && useAdvanceCheckbox) {
                useAdvanceCheckbox.checked = false;
                if (advanceUseBox) advanceUseBox.style.display = 'none';
            }
        });
    });

    // Quick Amount Buttons
    document.querySelectorAll('.quick-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            paymentAmount.value = parseFloat(this.dataset.amount).toFixed(2);
            updatePaymentPreview();
        });
    });

    // Advance Quick Buttons
    document.querySelectorAll('.advance-quick-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            advanceUsed.value = parseFloat(this.dataset.amount).toFixed(2);
            // Trigger input event
            const event = new Event('input', { bubbles: true });
            advanceUsed.dispatchEvent(event);
        });
    });

    // Quick Advance Buttons (Pure Advance)
    document.querySelectorAll('.quick-advance').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('advanceAmount').value = this.dataset.amount;
        });
    });

    // Use Advance Checkbox
    if (useAdvanceCheckbox) {
        useAdvanceCheckbox.addEventListener('change', function() {
            advanceUseBox.style.display = this.checked ? 'block' : 'none';
            if (!this.checked) {
                advanceUsed.value = 0;
                calculatedHint.style.display = 'none';
            } else {
                // Auto-set advance to either full available or remaining due
                const suggestedAdvance = Math.min(advanceBalance, dueAmount);
                advanceUsed.value = suggestedAdvance.toFixed(2);
            }
            // Trigger input to update calculations
            const event = new Event('input', { bubbles: true });
            advanceUsed.dispatchEvent(event);
        });
    }

    // Advance Used Input
    if (advanceUsed) {
        advanceUsed.addEventListener('input', function() {
            let val = parseFloat(this.value) || 0;

            // Validate max
            if (val > advanceBalance) {
                this.value = advanceBalance;
                val = advanceBalance;
            }

            // Update remaining advance display
            if (remainingAdvance) {
                remainingAdvance.textContent = (advanceBalance - val).toFixed(2);
            }

            // Auto-calculate suggested cash amount
            autoCalculateCash();

            // Update payment preview
            updatePaymentPreview();
        });
    }

    // Payment Amount Input
    if (paymentAmount) {
        paymentAmount.addEventListener('input', function() {
            updatePaymentPreview();
        });
    }

    // Click on hint to apply suggested amount
    if (calculatedHint) {
        calculatedHint.addEventListener('click', applySuggestedAmount);
        calculatedHint.style.cursor = 'pointer';
    }

    // EMI Calculator
    function calculateEMI() {
        const down = parseFloat(downPayment.value) || 0;
        const months = parseInt(emiMonths.value) || 0;

        if (down > 0 && months > 0 && down < dueAmount) {
            const remaining = dueAmount - down;
            const monthly = remaining / months;
            emiAmount.value = monthly.toFixed(2);
            paymentAmount.value = down;
            updatePaymentPreview();
        }
    }

    if (downPayment) downPayment.addEventListener('input', calculateEMI);
    if (emiMonths) emiMonths.addEventListener('change', calculateEMI);

    // Payment Preview
    function updatePaymentPreview() {
        const cash = parseFloat(paymentAmount.value) || 0;
        const advance = (useAdvanceCheckbox && useAdvanceCheckbox.checked) ?
            (parseFloat(advanceUsed.value) || 0) : 0;

        const total = cash + advance;

        if (total <= 0) {
            paymentPreview.style.display = 'none';
            return;
        }

        let html = '';

        if (total < dueAmount) {
            paymentType.value = 'partial';
            html = `
                <div style="background:#fffbeb;padding:15px;border-radius:12px;">
                    <strong style="color:#92400e;">⚠️ Partial Payment</strong><br>
                    Cash Payment: ₹${cash.toFixed(2)}<br>
                    Advance Used: ₹${advance.toFixed(2)}<br>
                    <strong>Total: ₹${total.toFixed(2)}</strong><br>
                    <strong style="color:#dc2626;">Remaining Due: ₹${(dueAmount - total).toFixed(2)}</strong>
                </div>
            `;
        } else if (total > dueAmount) {
            paymentType.value = 'excess';
            const excess = total - dueAmount;
            html = `
                <div style="background:#f0fdf4;padding:15px;border-radius:12px;">
                    <strong style="color:#166534;">💰 Excess Payment</strong><br>
                    Cash Payment: ₹${cash.toFixed(2)}<br>
                    Advance Used: ₹${advance.toFixed(2)}<br>
                    <strong>Total: ₹${total.toFixed(2)}</strong><br>
                    Invoice Paid: ₹${dueAmount.toFixed(2)}<br>
                    <strong style="color:#059669;">Excess to Advance: ₹${excess.toFixed(2)}</strong>
                </div>
            `;
        } else {
            paymentType.value = 'full';
            html = `
                <div style="background:#f0fdf4;padding:15px;border-radius:12px;">
                    <strong style="color:#166534;">✅ Full Payment</strong><br>
                    Cash Payment: ₹${cash.toFixed(2)}<br>
                    Advance Used: ₹${advance.toFixed(2)}<br>
                    <strong>Total: ₹${total.toFixed(2)}</strong><br>
                    Invoice will be marked as PAID
                </div>
            `;
        }

        paymentPreview.style.display = 'block';
        paymentPreview.innerHTML = html;
    }

    // Initial preview
    if (dueAmount > 0) {
        updatePaymentPreview();
    }

    // Form Submit Handler
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const isAdvance = isAdvanceOnly.value === '1';
        const method = document.querySelector('input[name="method"]:checked')?.value;

        if (isAdvance) {
            const amount = parseFloat(document.getElementById('advanceAmount').value) || 0;
            if (amount <= 0) {
                e.preventDefault();
                alert('Please enter advance amount');
                return false;
            }
        } else if (method === 'emi') {
            const down = parseFloat(downPayment.value) || 0;
            const months = emiMonths.value;

            if (down <= 0) {
                e.preventDefault();
                alert('Please enter down payment amount');
                return false;
            }
            if (!months) {
                e.preventDefault();
                alert('Please select EMI months');
                return false;
            }
            if (down >= dueAmount) {
                e.preventDefault();
                alert('Down payment cannot be equal to or greater than remaining amount');
                return false;
            }
        } else {
            const cash = parseFloat(paymentAmount.value) || 0;
            const advance = (useAdvanceCheckbox && useAdvanceCheckbox.checked) ?
                (parseFloat(advanceUsed.value) || 0) : 0;

            if (cash <= 0 && advance <= 0) {
                e.preventDefault();
                alert('Please enter payment amount or use advance');
                return false;
            }
        }

        return true;
    });
});
</script>
@endsection
