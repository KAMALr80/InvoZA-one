@extends('layouts.app')

@section('content')
<div style="max-width:1000px; margin:40px auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.08);">

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 20px;">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 20px;">❌</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header with Customer Info and Tabs --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f1f5f9;">
        <div>
            <h2 style="margin:0; font-size: 28px; font-weight: 700;">🧾 {{ $customer->name }}</h2>
            <p style="color:#6b7280; margin:5px 0 0 0;">Customer Purchase History</p>
        </div>

        {{-- Balance Badge --}}
        @php
            $balance = $customer->open_balance ?? 0;
        @endphp
        <div style="padding: 10px 20px; border-radius: 40px;
            @if($balance > 0) background: #fee2e2; color: #b91c1c;
            @elseif($balance < 0) background: #dcfce7; color: #166534;
            @else background: #f3f4f6; color: #4b5563;
            @endif">
            <span style="font-weight: 600;">Balance: ₹ {{ number_format($balance, 2) }}</span>
        </div>
    </div>

    {{-- Tabs for switching between views --}}
    <div style="display: flex; gap: 10px; margin-bottom: 25px;">
        <a href="{{ route('customers.sales', $customer->id) }}"
           style="flex: 1; padding: 14px; text-align: center; background: #2563eb; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
            📋 Invoices ({{ $sales->total() }})
        </a>
        <a href="{{ route('customers.payments', $customer->id) }}"
           style="flex: 1; padding: 14px; text-align: center; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 10px; font-weight: 600;">
            💳 Payments
        </a>
    </div>

    {{-- Invoices Table --}}
    <div style="overflow-x: auto; border-radius: 10px; border: 1px solid #e5e7eb;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background: #1f2937; color: #fff;">
                    <th style="padding: 12px; text-align: left;">Invoice #</th>
                    <th style="padding: 12px; text-align: left;">Date</th>
                    <th style="padding: 12px; text-align: right;">Total</th>
                    <th style="padding: 12px; text-align: center;">Status</th>
                    <th style="padding: 12px; text-align: center;" colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    @php
                        $statusColor = match ($sale->payment_status) {
                            'paid' => '#dcfce7',
                            'partial' => '#fef3c7',
                            'emi' => '#e0e7ff',
                            'unpaid' => '#fee2e2',
                            default => '#fee2e2',
                        };
                        $statusTextColor = match ($sale->payment_status) {
                            'paid' => '#166534',
                            'partial' => '#92400e',
                            'emi' => '#3730a3',
                            'unpaid' => '#991b1b',
                            default => '#991b1b',
                        };
                    @endphp
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; font-weight: 600;">{{ $sale->invoice_no }}</td>
                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                        <td style="padding: 12px; text-align: right; font-weight: 700; color: #059669;">
                            ₹ {{ number_format($sale->grand_total, 2) }}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: {{ $statusColor }}; color: {{ $statusTextColor }}; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                {{ strtoupper($sale->payment_status) }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                {{-- VIEW --}}
                                <a href="{{ route('sales.show', $sale->id) }}"
                                   style="background: #e0f2fe; color: #0369a1; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;"
                                   title="View Invoice">
                                    👁️ View
                                </a>

                                {{-- PRINT --}}
                                <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank"
                                   style="background: #dcfce7; color: #166534; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;"
                                   title="Print Invoice">
                                    🖨️ Print
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('sales.edit', $sale->id) }}"
                                   style="background: #fef3c7; color: #92400e; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;"
                                   title="Edit Invoice">
                                    ✏️ Edit
                                </a>

                                {{-- DELETE --}}
                                <form method="POST" action="{{ route('sales.destroy', $sale->id) }}"
                                      onsubmit="return confirm('⚠️ Delete this invoice?\n\nInvoice #: {{ $sale->invoice_no }}\nAmount: ₹{{ number_format($sale->grand_total, 2) }}\n\nThis will also delete all related payments. Are you sure?')"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 3px;"
                                            onmouseover="this.style.background='#fecaca'"
                                            onmouseout="this.style.background='#fee2e2'"
                                            title="Delete Invoice">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #6b7280;">
                            <div style="font-size: 48px; margin-bottom: 10px;">📭</div>
                            <div style="font-size: 18px; font-weight: 600;">No invoices found</div>
                            <a href="{{ route('sales.create') }}?customer_id={{ $customer->id }}"
                               style="display: inline-block; margin-top: 15px; background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                + Create New Invoice
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($sales, 'links'))
        <div style="margin-top: 20px;">
            {{ $sales->links() }}
        </div>
    @endif

    {{-- Back Button --}}
    <div style="margin-top: 25px; text-align: center;">
        <a href="{{ route('customers.index') }}"
           style="display: inline-flex; align-items: center; gap: 8px; background: #f3f4f6; color: #4b5563; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s;"
           onmouseover="this.style.background='#e5e7eb'"
           onmouseout="this.style.background='#f3f4f6'">
            ← Back to Customers
        </a>
    </div>

</div>
@endsection
