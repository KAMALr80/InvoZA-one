@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 40px auto; background: #ffffff; padding: 40px; border-radius: 24px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08); font-family: 'Inter', 'Segoe UI', -apple-system, sans-serif; border: 1px solid rgba(229, 231, 235, 0.8);">

    {{-- ================= HEADER ================= --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #f1f5f9;">
        <div>
            <h2 style="margin: 0; font-size: 32px; font-weight: 800; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: -0.5px;">
                📄 Invoice
            </h2>
            <div style="color: #6b7280; font-size: 15px; margin-top: 6px; display: flex; align-items: center; gap: 8px;">
                <span style="background: #f3f4f6; padding: 4px 12px; border-radius: 20px; font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #374151;">
                    #{{ $sale->invoice_no }}
                </span>
                <span style="color: #9ca3af;">•</span>
                <span>{{ \Carbon\Carbon::parse($sale->created_at)->format('M d, Y - h:i A') }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);"
                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.3)';"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.2)';">
                🖨️ Print Invoice
            </a>

            <a href="{{ route('sales.index') }}" style="background: linear-gradient(135deg, #4b5563 0%, #374151 100%); color: #fff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(55, 65, 81, 0.2);"
                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(55, 65, 81, 0.3)';"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(55, 65, 81, 0.2)';">
                ← Back to Sales
            </a>
        </div>
    </div>

    {{-- ================= STATUS BADGE ================= --}}
    @php
        $statusColor = match ($sale->payment_status) {
            'paid' => '#16a34a',
            'partial' => '#f59e0b',
            'emi' => '#6366f1',
            default => '#dc2626',
        };
    @endphp
    <div style="margin-bottom: 30px; display: inline-block; padding: 8px 20px; border-radius: 20px; background: {{ $statusColor }}; color: white; font-weight: 700; letter-spacing: 1px; font-size: 14px;">
        {{ strtoupper($sale->payment_status) }}
    </div>

    {{-- ================= CUSTOMER INFO ================= --}}
    <div style="background: #f8fafc; padding: 28px; border-radius: 16px; margin-bottom: 32px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; font-size: 15px; border: 1px solid #e5e7eb;">
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div style="color: #6366f1; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                👤 Customer
            </div>
            <div style="font-size: 18px; color: #111827; font-weight: 700;">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div style="color: #6366f1; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                📅 Invoice Date
            </div>
            <div style="font-size: 18px; color: #111827; font-weight: 700;">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div style="color: #6366f1; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                📱 Mobile
            </div>
            <div style="font-size: 16px; color: #111827; font-weight: 600;">{{ $sale->customer->mobile ?? 'N/A' }}</div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div style="color: #6366f1; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
                ✉️ Email
            </div>
            <div style="font-size: 16px; color: #111827; font-weight: 600;">{{ $sale->customer->email ?? '-' }}</div>
        </div>
    </div>

    {{-- ================= ITEMS TABLE ================= --}}
    <div style="margin-bottom: 32px;">
        <div style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; padding: 18px 24px; border-radius: 12px 12px 0 0; font-weight: 600; font-size: 16px; display: flex; align-items: center; gap: 10px;">
            🛒 Items Purchased
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 15px; border-radius: 0 0 12px 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-top: none;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 18px 20px; border-bottom: 2px solid #e5e7eb; text-align: center; color: #4b5563; font-weight: 700;">#</th>
                    <th style="padding: 18px 20px; border-bottom: 2px solid #e5e7eb; text-align: left; color: #4b5563; font-weight: 700;">Product</th>
                    <th style="padding: 18px 20px; border-bottom: 2px solid #e5e7eb; text-align: right; color: #4b5563; font-weight: 700;">Price</th>
                    <th style="padding: 18px 20px; border-bottom: 2px solid #e5e7eb; text-align: center; color: #4b5563; font-weight: 700;">Qty</th>
                    <th style="padding: 18px 20px; border-bottom: 2px solid #e5e7eb; text-align: right; color: #4b5563; font-weight: 700;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $i => $item)
                <tr style="background: {{ $i % 2 == 0 ? '#ffffff' : '#fafafa' }}; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#f8fafc';"
                    onmouseout="this.style.backgroundColor='{{ $i % 2 == 0 ? '#ffffff' : '#fafafa' }}';">
                    <td style="padding: 16px 20px; text-align: center; color: #6b7280; font-weight: 500; border-right: 1px solid #f1f5f9;">
                        {{ $i + 1 }}
                    </td>
                    <td style="padding: 16px 20px; font-weight: 600; color: #374151;">
                        {{ $item->product->name ?? 'Product Deleted' }}
                    </td>
                    <td style="padding: 16px 20px; text-align: right; color: #059669; font-weight: 600; font-family: 'JetBrains Mono', monospace;">
                        ₹ {{ number_format($item->price, 2) }}
                    </td>
                    <td style="padding: 16px 20px; text-align: center; color: #4b5563; font-weight: 500;">
                        <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
                            {{ $item->quantity }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px; text-align: right; color: #1d4ed8; font-weight: 700; font-family: 'JetBrains Mono', monospace;">
                        ₹ {{ number_format($item->total, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================= TOTALS ================= --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 32px;">
        <div></div>

        <div style="background: #f8fafc; padding: 28px; border-radius: 16px; font-size: 15px; border: 1px solid #e5e7eb;">
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #374151; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
                💰 Summary
            </h3>

            <div style="display: flex; justify-content: space-between; margin-bottom: 14px; padding: 10px 0;">
                <span style="color: #6b7280;">Sub Total</span>
                <b style="color: #4b5563; font-family: 'JetBrains Mono', monospace;">₹ {{ number_format($sale->sub_total, 2) }}</b>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 14px; padding: 10px 0;">
                <span style="color: #6b7280;">Discount</span>
                <b style="color: #dc2626; font-family: 'JetBrains Mono', monospace;">- ₹ {{ number_format($sale->discount, 2) }}</b>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 14px; padding: 10px 0;">
                <span style="color: #6b7280;">Tax</span>
                <b style="color: #ea580c;">{{ $sale->tax }}%</b>
            </div>

            <hr style="border: none; border-top: 2px dashed #e5e7eb; margin: 20px 0;">

            <div style="display: flex; justify-content: space-between; padding: 20px 0; background: #f0f9ff; margin: -28px; margin-top: 10px; padding: 20px 28px; border-radius: 0 0 16px 16px; align-items: center;">
                <span style="font-size: 18px; font-weight: 800; color: #075985;">Grand Total</span>
                <span style="font-size: 28px; font-weight: 900; color: #1e40af; font-family: 'JetBrains Mono', monospace;">
                    ₹ {{ number_format($sale->grand_total, 2) }}
                </span>
            </div>
        </div>
    </div>

    {{-- ================= PAYMENT DETAILS ================= --}}
    <div style="background: #f8fafc; padding: 28px; border-radius: 16px; font-size: 15px; border: 1px solid #e5e7eb; margin-bottom: 32px;">
        <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #374151; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 10px;">
            💳 Payment Details
        </h3>

        @php
            $totalPaid = $sale->payments->where('status', 'paid')->sum('amount');
            $remaining = max(0, $sale->grand_total - $totalPaid);
        @endphp

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
            <div>
                <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Total Paid</div>
                <div style="font-size: 20px; font-weight: 700; color: #059669;">₹ {{ number_format($totalPaid, 2) }}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Remaining</div>
                <div style="font-size: 20px; font-weight: 700; color: #dc2626;">₹ {{ number_format($remaining, 2) }}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Status</div>
                <div style="font-size: 20px; font-weight: 700; color: {{ $statusColor }};">{{ strtoupper($sale->payment_status) }}</div>
            </div>
        </div>

        @if ($sale->customer)
        <div style="background: #ffffff; padding: 16px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e5e7eb;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #4b5563; display: flex; align-items: center; gap: 8px;">
                👤 Customer Balance
            </h4>
            @if ($sale->customer->open_balance > 0)
                <div style="color: #dc2626; font-size: 18px; font-weight: 700;">
                    ₹ {{ number_format($sale->customer->open_balance, 2) }} Due
                </div>
            @elseif ($sale->customer->open_balance < 0)
                <div style="color: #16a34a; font-size: 18px; font-weight: 700;">
                    ₹ {{ number_format(abs($sale->customer->open_balance), 2) }} Advance
                </div>
            @else
                <div style="color: #64748b; font-size: 18px; font-weight: 600;">
                    Clear
                </div>
            @endif
        </div>
        @endif

        @if ($sale->payment_status === 'emi' && $sale->emiPlan)
        <div style="background: #ffffff; padding: 16px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e5e7eb;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #4b5563; display: flex; align-items: center; gap: 8px;">
                📆 EMI Details
            </h4>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                <div>
                    <div style="color: #6b7280; font-size: 14px;">Total Amount</div>
                    <div style="font-weight: 600;">₹ {{ number_format($sale->emiPlan->total_amount, 2) }}</div>
                </div>
                <div>
                    <div style="color: #6b7280; font-size: 14px;">Down Payment</div>
                    <div style="font-weight: 600;">₹ {{ number_format($sale->emiPlan->down_payment, 2) }}</div>
                </div>
                <div>
                    <div style="color: #6b7280; font-size: 14px;">Monthly EMI</div>
                    <div style="font-weight: 600;">₹ {{ number_format($sale->emiPlan->emi_amount, 2) }}</div>
                </div>
                <div>
                    <div style="color: #6b7280; font-size: 14px;">Status</div>
                    <div style="font-weight: 600; color: {{ $sale->emiPlan->status === 'completed' ? '#16a34a' : ($sale->emiPlan->status === 'running' ? '#f59e0b' : '#dc2626') }};">
                        {{ strtoupper($sale->emiPlan->status) }}
                    </div>
                </div>
            </div>

            @if ($sale->emiPlan->status === 'running')
            <div style="margin-top: 20px; text-align: center;">
                <a href="{{ route('emi.show', $sale->emiPlan->id) }}" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    💳 Pay EMI Now
                </a>
            </div>
            @endif

            @if ($sale->emiPlan->status === 'completed')
            <div style="margin-top: 16px; padding: 12px; background: #dcfce7; border-radius: 8px; text-align: center; color: #166534; font-weight: 600;">
                ✅ EMI Completed – Invoice Fully Paid
            </div>
            @endif
        </div>
        @endif

        @if ($sale->payments->count() > 0)
        <div>
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #4b5563; display: flex; align-items: center; gap: 8px;">
                📋 Payment History
            </h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">#</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: left;">Method</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: right;">Amount</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">Status</th>
                        <th style="padding: 12px; border: 1px solid #e5e7eb; text-align: left;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->payments as $i => $payment)
                    <tr>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">{{ $i + 1 }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; font-weight: 600;">{{ strtoupper($payment->method) }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: right; font-weight: 600; color: #059669;">₹ {{ number_format($payment->amount, 2) }}</td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb; text-align: center;">
                            <span style="padding: 4px 12px; border-radius: 20px; font-weight: 600; background: {{ $payment->status === 'paid' ? '#dcfce7' : '#fef3c7' }}; color: {{ $payment->status === 'paid' ? '#166534' : '#92400e' }};">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td style="padding: 12px; border: 1px solid #e5e7eb;">{{ $payment->created_at->format('d M Y - h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if ($sale->payment_status !== 'paid' && $sale->payment_status !== 'emi')
        <div style="margin-top: 24px; text-align: center;">
            <a href="{{ route('payments.create', $sale->id) }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;"
                onmouseover="this.style.transform='translateY(-2px)';"
                onmouseout="this.style.transform='translateY(0)';">
                ➕ Add Another Payment
            </a>
        </div>
        @endif
    </div>

    {{-- ================= FOOTER ================= --}}
    <div style="margin-top: 40px; text-align: center; color: #9ca3af; font-size: 13px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
        Thank you for your business! • This is a computer-generated invoice
    </div>
</div>
@endsection
