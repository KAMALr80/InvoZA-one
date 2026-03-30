@extends('layouts.app')

@section('page-title', 'Financial Summary')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="color: #1f2937; font-size: 28px; font-weight: 700; margin-bottom: 5px;">
            <i class="fas fa-money-bill-wave"></i> Financial Summary
        </h1>
        <p style="color: #6b7280; font-size: 14px;">Complete financial overview and key metrics</p>
    </div>

    <!-- Key Financial Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <!-- Total Revenue Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Total Revenue</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        ₹{{ number_format($totalRevenue ?? 0, 2) }}
                    </h3>
                    <p style="color: #10b981; font-size: 12px; margin-top: 5px;">
                        <i class="fas fa-arrow-up"></i> 12% increase
                    </p>
                </div>
                <div style="background: #ecfdf5; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    📈
                </div>
            </div>
        </div>

        <!-- Total Expenses Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #ef4444;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Total Expenses</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        ₹{{ number_format($totalExpenses ?? 0, 2) }}
                    </h3>
                    <p style="color: #ef4444; font-size: 12px; margin-top: 5px;">
                        <i class="fas fa-arrow-up"></i> 8% increase
                    </p>
                </div>
                <div style="background: #fee2e2; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    💸
                </div>
            </div>
        </div>

        <!-- Net Profit Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #6366f1;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Net Profit</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        ₹{{ number_format($netProfit ?? 0, 2) }}
                    </h3>
                    <p style="color: #6366f1; font-size: 12px; margin-top: 5px;">
                        {{ $netProfitMargin ?? 0 }}% Margin
                    </p>
                </div>
                <div style="background: #eef2ff; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    💰
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Financial Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <!-- Sales Breakdown -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-coins"></i> Sales Breakdown
            </h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Total Sales</span>
                    <span style="color: #1f2937; font-weight: 600;">₹{{ number_format($totalSales ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Orders Count</span>
                    <span style="color: #1f2937; font-weight: 600;">{{ $totalOrders ?? 0 }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Avg Order Value</span>
                    <span style="color: #1f2937; font-weight: 600;">₹{{ number_format($avgOrderValue ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Purchase Breakdown -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-shopping-cart"></i> Purchase Breakdown
            </h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Total Purchases</span>
                    <span style="color: #1f2937; font-weight: 600;">₹{{ number_format($totalPurchases ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Purchase Orders</span>
                    <span style="color: #1f2937; font-weight: 600;">{{ $totalPurchaseOrders ?? 0 }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Avg Cost Per Order</span>
                    <span style="color: #1f2937; font-weight: 600;">₹{{ number_format($avgPurchaseValue ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i> Payment Status
            </h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Amount Received</span>
                    <span style="color: #1f2937; font-weight: 600;">₹{{ number_format($amountReceived ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Outstanding</span>
                    <span style="color: #ef4444; font-weight: 600;">₹{{ number_format($outstandingAmount ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #6b7280;">Collection Rate</span>
                    <span style="color: #1f2937; font-weight: 600;">{{ $collectionRate ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Export -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div>
                <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Date Range</label>
                <input type="date" style="padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Period</label>
                <select style="padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Quarter</option>
                    <option>This Year</option>
                </select>
            </div>
            <button style="background: #6366f1; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; margin-top: 23px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('reports.financial.excel') }}" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 23px; display: inline-block;">
                <i class="fas fa-download"></i> Export Excel
            </a>
            <a href="{{ route('reports.financial.pdf') }}" style="background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 23px; display: inline-block;">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Monthly Financial Summary -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="color: #1f2937; font-weight: 600; margin: 0;">
                <i class="fas fa-chart-bar"></i> Monthly Financial Trend
            </h3>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Month</th>
                    <th style="padding: 15px; text-align: right; font-size: 13px; font-weight: 600; color: #374151;">Revenue</th>
                    <th style="padding: 15px; text-align: right; font-size: 13px; font-weight: 600; color: #374151;">Expenses</th>
                    <th style="padding: 15px; text-align: right; font-size: 13px; font-weight: 600; color: #374151;">Profit</th>
                    <th style="padding: 15px; text-align: right; font-size: 13px; font-weight: 600; color: #374151;">Margin %</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $months = ['January', 'February', 'March', 'April', 'May', 'June'];
                    $revenues = [150000, 165000, 180000, 172000, 195000, 210000];
                    $expenses = [95000, 105000, 110000, 102000, 115000, 125000];
                @endphp
                @foreach($months as $key => $month)
                    @php
                        $revenue = $revenues[$key] ?? 0;
                        $expense = $expenses[$key] ?? 0;
                        $profit = $revenue - $expense;
                        $margin = $revenue > 0 ? round(($profit / $revenue) * 100, 1) : 0;
                    @endphp
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 15px; color: #1f2937; font-weight: 500;">{{ $month }}</td>
                        <td style="padding: 15px; text-align: right; color: #10b981; font-weight: 600;">₹{{ number_format($revenue, 2) }}</td>
                        <td style="padding: 15px; text-align: right; color: #ef4444; font-weight: 600;">₹{{ number_format($expense, 2) }}</td>
                        <td style="padding: 15px; text-align: right; color: #6366f1; font-weight: 600;">₹{{ number_format($profit, 2) }}</td>
                        <td style="padding: 15px; text-align: right;">
                            <span style="background: #eef2ff; color: #4f46e5; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                {{ $margin }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Cash Flow Information -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Operating Cash Flow -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-arrow-right-arrow-left"></i> Operating Cash Flow
            </h4>
            <p style="font-size: 24px; color: #10b981; font-weight: 700; margin: 0;">₹{{ number_format($operatingCashFlow ?? 0, 2) }}</p>
            <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">Cash generated from operations</p>
        </div>

        <!-- Liquidity Ratio -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-balance-scale"></i> Liquidity Ratio
            </h4>
            <p style="font-size: 24px; color: #6366f1; font-weight: 700; margin: 0;">{{ $liquidityRatio ?? '1.5' }}</p>
            <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">Current assets to liabilities ratio</p>
        </div>

        <!-- Debt Ratio -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-percent"></i> Debt Ratio
            </h4>
            <p style="font-size: 24px; color: #f59e0b; font-weight: 700; margin: 0;">{{ $debtRatio ?? '32' }}%</p>
            <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">Debt to total assets ratio</p>
        </div>
    </div>

    <!-- Footer Stats -->
    <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <p style="color: #6b7280; font-size: 13px; margin: 0;">
            <strong>Report Generated:</strong> {{ now()->format('d M Y, H:i A') }} |
            <strong>Report Period:</strong> {{ now()->format('F Y') }} |
            <strong>Status:</strong> <span style="color: #10b981;">✓ Updated</span>
        </p>
    </div>
</div>

<style>
    tr:hover {
        background: #f9fafb !important;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>
@endsection
