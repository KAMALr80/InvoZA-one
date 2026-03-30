@extends('layouts.app')

@section('page-title', 'Logistics Report')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="color: #1f2937; font-size: 28px; font-weight: 700; margin-bottom: 5px;">
            <i class="fas fa-truck"></i> Logistics Report
        </h1>
        <p style="color: #6b7280; font-size: 14px;">Track shipments, deliveries, and logistics performance</p>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <!-- Total Shipments Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #6366f1;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Total Shipments</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        {{ $totalShipments ?? 0 }}
                    </h3>
                </div>
                <div style="background: #eef2ff; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    📦
                </div>
            </div>
        </div>

        <!-- Delivered Shipments Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Delivered</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        {{ $deliveredShipments ?? 0 }}
                    </h3>
                    <p style="color: #10b981; font-size: 12px; margin-top: 5px;">
                        {{ $totalShipments > 0 ? round(($deliveredShipments / $totalShipments) * 100, 1) : 0 }}% Success Rate
                    </p>
                </div>
                <div style="background: #ecfdf5; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    ✅
                </div>
            </div>
        </div>

        <!-- Pending Shipments Card -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Pending/In Transit</p>
                    <h3 style="color: #1f2937; font-size: 28px; font-weight: 700; margin: 0;">
                        {{ $pendingShipments ?? 0 }}
                    </h3>
                </div>
                <div style="background: #fffbeb; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    🚚
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
                <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Status</label>
                <select style="padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <option>All</option>
                    <option>Delivered</option>
                    <option>In Transit</option>
                    <option>Pending</option>
                </select>
            </div>
            <button style="background: #6366f1; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; margin-top: 23px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('reports.logistics.excel') }}" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 23px; display: inline-block;">
                <i class="fas fa-download"></i> Export Excel
            </a>
            <a href="{{ route('reports.logistics.pdf') }}" style="background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-top: 23px; display: inline-block;">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Average Delivery Time -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-clock"></i> Average Delivery Time
            </h4>
            <p style="font-size: 24px; color: #6366f1; font-weight: 700; margin: 0;">2.5 days</p>
            <p style="color: #10b981; font-size: 12px; margin-top: 5px;">
                <i class="fas fa-arrow-down"></i> 15% improvement from last month
            </p>
        </div>

        <!-- On-Time Delivery -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i> On-Time Delivery Rate
            </h4>
            <p style="font-size: 24px; color: #10b981; font-weight: 700; margin: 0;">94.2%</p>
            <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">
                Target: 95%
            </p>
        </div>

        <!-- Failed Deliveries -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 15px;">
                <i class="fas fa-exclamation-circle"></i> Failed Deliveries
            </h4>
            <p style="font-size: 24px; color: #ef4444; font-weight: 700; margin: 0;">5.8%</p>
            <p style="color: #ef4444; font-size: 12px; margin-top: 5px;">
                <i class="fas fa-arrow-up"></i> 2% increase
            </p>
        </div>
    </div>

    <!-- Shipments Table -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Shipment ID</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Customer</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Origin</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Destination</th>
                    <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #374151;">Agent</th>
                    <th style="padding: 15px; text-align: center; font-size: 13px; font-weight: 600; color: #374151;">Status</th>
                    <th style="padding: 15px; text-align: center; font-size: 13px; font-weight: 600; color: #374151;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 15px; color: #1f2937; font-weight: 600;">#SHP001</td>
                    <td style="padding: 15px; color: #1f2937;">John Doe</td>
                    <td style="padding: 15px; color: #6b7280;">Mumbai</td>
                    <td style="padding: 15px; color: #6b7280;">Delhi</td>
                    <td style="padding: 15px; color: #6b7280;">Agent A</td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="background: #ecfdf5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            Delivered
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="#" style="color: #6366f1; text-decoration: none; font-size: 14px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 15px; color: #1f2937; font-weight: 600;">#SHP002</td>
                    <td style="padding: 15px; color: #1f2937;">Jane Smith</td>
                    <td style="padding: 15px; color: #6b7280;">Bangalore</td>
                    <td style="padding: 15px; color: #6b7280;">Chennai</td>
                    <td style="padding: 15px; color: #6b7280;">Agent B</td>
                    <td style="padding: 15px; text-align: center;">
                        <span style="background: #fffbeb; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            In Transit
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="#" style="color: #6366f1; text-decoration: none; font-size: 14px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer Stats -->
    <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <p style="color: #6b7280; font-size: 13px; margin: 0;">
            <strong>Report Generated:</strong> {{ now()->format('d M Y, H:i A') }} |
            <strong>Total Shipments:</strong> {{ $totalShipments ?? 0 }} |
            <strong>Success Rate:</strong> {{ $totalShipments > 0 ? round(($deliveredShipments / $totalShipments) * 100, 1) : 0 }}%
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
