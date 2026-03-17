<!-- Agent Show Page -->
@extends('layouts.app')

@section('page-title', 'Agent Details - ' . $agent->name)

@section('content')
<style>
    /* ================= PROFESSIONAL AGENT DETAILS STYLES ================= */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #1e293b;
        line-height: 1.5;
    }

    /* ================= MAIN CONTAINER ================= */
    .agent-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        padding: clamp(16px, 3vw, 30px);
        width: 100%;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* ================= MAIN CARD ================= */
    .agent-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        width: 100%;
        border: 1px solid #e5e7eb;
    }

    /* ================= HEADER ================= */
    .agent-header {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        padding: clamp(1.5rem, 4vw, 2rem);
        color: white;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex: 1;
        min-width: 280px;
    }

    .header-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    .header-info {
        flex: 1;
    }

    .header-title {
        font-size: clamp(1.5rem, 5vw, 2rem);
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .header-badge {
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .header-badge.available {
        background: #10b981;
        color: white;
    }

    .header-badge.busy {
        background: #f59e0b;
        color: white;
    }

    .header-badge.offline {
        background: #64748b;
        color: white;
    }

    .header-subtitle {
        opacity: 0.9;
        font-size: clamp(0.9rem, 2.5vw, 1rem);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .header-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: clamp(0.9rem, 2vw, 1rem);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .header-btn:hover {
        background: white;
        color: #0f172a;
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .stat-card {
        background: white;
        padding: 1.25rem;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon.total {
        background: #3b82f6;
        color: white;
    }

    .stat-icon.success {
        background: #10b981;
        color: white;
    }

    .stat-icon.rating {
        background: #f59e0b;
        color: white;
    }

    .stat-icon.days {
        background: #8b5cf6;
        color: white;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .stat-sub {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .stat-progress {
        margin-top: 0.5rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        width: 0%;
        transition: width 0.3s ease;
    }

    /* ================= INFO GRID ================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        padding: clamp(1.5rem, 4vw, 2rem);
        border-bottom: 1px solid #e5e7eb;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .info-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background: #3b82f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .info-label {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
    }

    .info-value.highlight {
        color: #3b82f6;
        font-weight: 700;
    }

    .info-value.success {
        color: #10b981;
    }

    .info-value.warning {
        color: #f59e0b;
    }

    .info-value.danger {
        color: #ef4444;
    }

    .info-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .info-badge.success {
        background: #d1fae5;
        color: #065f46;
    }

    .info-badge.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .info-badge.danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .info-badge.info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* ================= DOCUMENTS GRID ================= */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .document-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
        cursor: pointer;
    }

    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
    }

    .document-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .document-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .document-status {
        font-size: 0.8rem;
        color: #10b981;
    }

    .document-upload {
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        border: 2px dashed #e5e7eb;
        text-align: center;
    }

    .document-upload input {
        display: none;
    }

    .document-upload label {
        display: block;
        padding: 1rem;
        cursor: pointer;
        color: #3b82f6;
        font-weight: 600;
    }

    /* ================= BANK DETAILS ================= */
    .bank-details {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
    }

    .bank-details .info-row {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.75rem 0;
    }

    .bank-details .info-row:last-child {
        border-bottom: none;
    }

    .bank-details .info-label {
        color: rgba(255, 255, 255, 0.7);
    }

    .bank-details .info-value {
        color: white;
    }

    /* ================= MAP SECTION ================= */
    .map-section {
        padding: clamp(1.5rem, 4vw, 2rem);
        border-bottom: 1px solid #e5e7eb;
    }

    .map-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #agentMap {
        height: 400px;
        width: 100%;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .location-update {
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .location-time {
        color: #64748b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .location-coords {
        font-family: monospace;
        background: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    /* ================= RECENT DELIVERIES ================= */
    .deliveries-section {
        padding: clamp(1.5rem, 4vw, 2rem);
        border-bottom: 1px solid #e5e7eb;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-all-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .deliveries-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .deliveries-table th {
        background: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e5e7eb;
    }

    .deliveries-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }

    .deliveries-table tbody tr:hover {
        background: #f8fafc;
    }

    .delivery-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .delivery-status.delivered {
        background: #d1fae5;
        color: #065f46;
    }

    .delivery-status.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .delivery-status.in_transit {
        background: #dbeafe;
        color: #1e40af;
    }

    .delivery-status.failed {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ================= ACTION BUTTONS ================= */
    .action-buttons {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: clamp(0.9rem, 2vw, 1rem);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-success {
        background: #10b981;
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    /* ================= TOAST ================= */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-left: 4px solid;
        display: none;
        z-index: 9999;
        max-width: 400px;
        width: calc(100% - 40px);
        animation: slideIn 0.3s ease;
    }

    .toast.success {
        border-left-color: #10b981;
    }

    .toast.error {
        border-left-color: #ef4444;
    }

    .toast.warning {
        border-left-color: #f59e0b;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* ================= LOADING ================= */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        z-index: 11000;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e5e7eb;
        border-top-color: #3b82f6;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        color: #1e293b;
        font-weight: 500;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .header-left {
            flex-direction: column;
            text-align: center;
        }

        .header-title {
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .deliveries-table {
            min-width: 800px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media print {
        .header-actions,
        .action-buttons,
        .document-upload,
        .btn {
            display: none !important;
        }
    }
</style>

<div class="agent-page">
    <div class="container">
        <div class="agent-card">
            {{-- Loading Overlay --}}
            <div id="loadingOverlay" class="loading-overlay">
                <div class="spinner"></div>
                <div class="loading-text">Processing...</div>
            </div>

            {{-- Header --}}
            <div class="agent-header">
                <div class="header-content">
                    <div class="header-left">
                        <div class="header-avatar">
                            {{ substr($agent->name, 0, 1) }}
                        </div>
                        <div class="header-info">
                            <div class="header-title">
                                {{ $agent->name }}
                                <span class="header-badge {{ $agent->status }}">
                                    {{ ucfirst($agent->status) }}
                                </span>
                            </div>
                            <div class="header-subtitle">
                                <span>🆔 {{ $agent->agent_code }}</span>
                                <span>•</span>
                                <span>📱 {{ $agent->phone }}</span>
                                @if($agent->email)
                                <span>•</span>
                                <span>✉️ {{ $agent->email }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('logistics.agents.edit', $agent->id) }}" class="header-btn">
                            ✏️ Edit Agent
                        </a>
                        <button class="header-btn" onclick="changeStatus()">
                            🔄 Change Status
                        </button>
                        <a href="{{ route('logistics.agents.index') }}" class="header-btn">
                            ← Back to List
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">📦</div>
                        <div class="stat-label">Total Deliveries</div>
                    </div>
                    <div class="stat-value">{{ number_format($agent->total_deliveries ?? 0) }}</div>
                    <div class="stat-sub">All time deliveries</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon success">✅</div>
                        <div class="stat-label">Successful</div>
                    </div>
                    <div class="stat-value">{{ number_format($agent->successful_deliveries ?? 0) }}</div>
                    <div class="stat-sub">{{ $agent->total_deliveries > 0 ? round(($agent->successful_deliveries / $agent->total_deliveries) * 100) : 0 }}% success rate</div>
                    <div class="stat-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $agent->total_deliveries > 0 ? ($agent->successful_deliveries / $agent->total_deliveries) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon rating">⭐</div>
                        <div class="stat-label">Rating</div>
                    </div>
                    <div class="stat-value">{{ number_format($agent->rating ?? 0, 1) }}</div>
                    <div class="stat-sub">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($agent->rating ?? 0))
                                <span style="color: #f59e0b;">★</span>
                            @else
                                <span style="color: #e5e7eb;">☆</span>
                            @endif
                        @endfor
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon days">📅</div>
                        <div class="stat-label">Member Since</div>
                    </div>
                    <div class="stat-value">{{ $agent->created_at->format('d M Y') }}</div>
                    <div class="stat-sub">{{ $agent->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="info-grid">
                {{-- Personal Information --}}
                <div class="info-card">
                    <div class="info-title">
                        <div class="info-icon">👤</div>
                        <span>Personal Information</span>
                    </div>
                    <div class="info-content">
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $agent->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Agent Code</span>
                            <span class="info-value highlight">{{ $agent->agent_code }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone Number</span>
                            <span class="info-value">{{ $agent->phone }}</span>
                        </div>
                        @if($agent->alternate_phone)
                        <div class="info-row">
                            <span class="info-label">Alternate Phone</span>
                            <span class="info-value">{{ $agent->alternate_phone }}</span>
                        </div>
                        @endif
                        @if($agent->email)
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $agent->email }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Employment Type</span>
                            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $agent->employment_type ?? 'full_time')) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Joining Date</span>
                            <span class="info-value">{{ $agent->joining_date ? $agent->joining_date->format('d M Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Information --}}
                <div class="info-card">
                    <div class="info-title">
                        <div class="info-icon">🚚</div>
                        <span>Vehicle Information</span>
                    </div>
                    <div class="info-content">
                        <div class="info-row">
                            <span class="info-label">Vehicle Type</span>
                            <span class="info-value">{{ ucfirst($agent->vehicle_type ?? 'Not assigned') }}</span>
                        </div>
                        @if($agent->vehicle_number)
                        <div class="info-row">
                            <span class="info-label">Vehicle Number</span>
                            <span class="info-value">{{ $agent->vehicle_number }}</span>
                        </div>
                        @endif
                        @if($agent->license_number)
                        <div class="info-row">
                            <span class="info-label">License Number</span>
                            <span class="info-value">{{ $agent->license_number }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Service Areas</span>
                            <span class="info-value">
                                @if($agent->service_areas)
                                    @foreach(json_decode($agent->service_areas) as $area)
                                        <span class="info-badge info">{{ $area }}</span>
                                    @endforeach
                                @else
                                    Not specified
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Address Information --}}
                <div class="info-card">
                    <div class="info-title">
                        <div class="info-icon">📍</div>
                        <span>Address Information</span>
                    </div>
                    <div class="info-content">
                        @if($agent->address)
                        <div class="info-row">
                            <span class="info-label">Address</span>
                            <span class="info-value">{{ $agent->address }}</span>
                        </div>
                        @endif
                        @if($agent->city)
                        <div class="info-row">
                            <span class="info-label">City</span>
                            <span class="info-value">{{ $agent->city }}</span>
                        </div>
                        @endif
                        @if($agent->state)
                        <div class="info-row">
                            <span class="info-label">State</span>
                            <span class="info-value">{{ $agent->state }}</span>
                        </div>
                        @endif
                        @if($agent->pincode)
                        <div class="info-row">
                            <span class="info-label">Pincode</span>
                            <span class="info-value">{{ $agent->pincode }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Bank Details --}}
                <div class="info-card">
                    <div class="info-title">
                        <div class="info-icon">🏦</div>
                        <span>Bank Details</span>
                    </div>
                    <div class="bank-details">
                        <div class="info-content">
                            @if($agent->bank_name)
                            <div class="info-row">
                                <span class="info-label">Bank Name</span>
                                <span class="info-value">{{ $agent->bank_name }}</span>
                            </div>
                            @endif
                            @if($agent->account_number)
                            <div class="info-row">
                                <span class="info-label">Account Number</span>
                                <span class="info-value">XXXX{{ substr($agent->account_number, -4) }}</span>
                            </div>
                            @endif
                            @if($agent->ifsc_code)
                            <div class="info-row">
                                <span class="info-label">IFSC Code</span>
                                <span class="info-value">{{ $agent->ifsc_code }}</span>
                            </div>
                            @endif
                            @if($agent->upi_id)
                            <div class="info-row">
                                <span class="info-label">UPI ID</span>
                                <span class="info-value">{{ $agent->upi_id }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Compensation --}}
                <div class="info-card">
                    <div class="info-title">
                        <div class="info-icon">💰</div>
                        <span>Compensation</span>
                    </div>
                    <div class="info-content">
                        @if($agent->salary)
                        <div class="info-row">
                            <span class="info-label">Base Salary</span>
                            <span class="info-value success">₹{{ number_format($agent->salary, 2) }}</span>
                        </div>
                        @endif
                        @if($agent->commission_type)
                        <div class="info-row">
                            <span class="info-label">Commission Type</span>
                            <span class="info-value">{{ ucfirst($agent->commission_type) }}</span>
                        </div>
                        @endif
                        @if($agent->commission_value)
                        <div class="info-row">
                            <span class="info-label">Commission Value</span>
                            <span class="info-value">{{ $agent->commission_type == 'percentage' ? $agent->commission_value . '%' : '₹' . number_format($agent->commission_value, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Documents --}}
                <div class="info-card">
                    <div class="info-title">
                        <div class="info-icon">📄</div>
                        <span>Documents</span>
                    </div>
                    <div class="documents-grid">
                        @if($agent->aadhar_card)
                        <div class="document-card" onclick="viewDocument('{{ Storage::url($agent->aadhar_card) }}')">
                            <div class="document-icon">🆔</div>
                            <div class="document-name">Aadhar Card</div>
                            <div class="document-status">Uploaded</div>
                        </div>
                        @else
                        <div class="document-card">
                            <div class="document-icon">🆔</div>
                            <div class="document-name">Aadhar Card</div>
                            <div class="document-status" style="color: #f59e0b;">Pending</div>
                        </div>
                        @endif

                        @if($agent->driving_license)
                        <div class="document-card" onclick="viewDocument('{{ Storage::url($agent->driving_license) }}')">
                            <div class="document-icon">🚗</div>
                            <div class="document-name">Driving License</div>
                            <div class="document-status">Uploaded</div>
                        </div>
                        @else
                        <div class="document-card">
                            <div class="document-icon">🚗</div>
                            <div class="document-name">Driving License</div>
                            <div class="document-status" style="color: #f59e0b;">Pending</div>
                        </div>
                        @endif

                        @if($agent->photo)
                        <div class="document-card" onclick="viewDocument('{{ Storage::url($agent->photo) }}')">
                            <div class="document-icon">📸</div>
                            <div class="document-name">Photo</div>
                            <div class="document-status">Uploaded</div>
                        </div>
                        @else
                        <div class="document-card">
                            <div class="document-icon">📸</div>
                            <div class="document-name">Photo</div>
                            <div class="document-status" style="color: #f59e0b;">Pending</div>
                        </div>
                        @endif
                    </div>

                    <div class="document-upload">
                        <input type="file" id="documentUpload" multiple accept="image/*,.pdf">
                        <label for="documentUpload">📤 Upload Documents</label>
                    </div>
                </div>
            </div>

            {{-- Map Section --}}
            @if($agent->current_latitude && $agent->current_longitude)
            <div class="map-section">
                <div class="map-title">
                    <span>🗺️</span>
                    <span>Current Location</span>
                </div>
                <div id="agentMap"></div>
                <div class="location-update">
                    <div class="location-time">
                        <span>🕒 Last Updated:</span>
                        <span>{{ $agent->last_location_update ? $agent->last_location_update->diffForHumans() : 'Never' }}</span>
                    </div>
                    <div class="location-coords">
                        {{ $agent->current_latitude }}, {{ $agent->current_longitude }}
                    </div>
                </div>
            </div>
            @endif

            {{-- Recent Deliveries --}}
            <div class="deliveries-section">
                <div class="section-header">
                    <div class="section-title">
                        <span>📦</span>
                        <span>Recent Deliveries</span>
                    </div>
                    <a href="{{ route('logistics.shipments.index', ['agent_id' => $agent->user_id]) }}" class="view-all-link">
                        View All →
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="deliveries-table">
                        <thead>
                            <tr>
                                <th>Shipment #</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agent->assignedShipments()->latest()->take(5)->get() as $shipment)
                            <tr>
                                <td>
                                    <a href="{{ route('logistics.shipments.show', $shipment->id) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                        {{ $shipment->shipment_number }}
                                    </a>
                                </td>
                                <td>{{ $shipment->receiver_name }}</td>
                                <td>{{ $shipment->city }}, {{ $shipment->state }}</td>
                                <td>
                                    <span class="delivery-status {{ $shipment->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>
                                <td>{{ $shipment->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                                    No deliveries assigned yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons">
                <a href="{{ route('logistics.agents.edit', $agent->id) }}" class="btn btn-primary">
                    ✏️ Edit Agent
                </a>
                <button class="btn btn-success" onclick="markAvailable()">
                    ✅ Mark Available
                </button>
                <button class="btn btn-warning" onclick="markBusy()">
                    🚚 Mark Busy
                </button>
                <button class="btn btn-secondary" onclick="markOffline()">
                    ⭕ Mark Offline
                </button>
                <a href="{{ route('logistics.agents.index') }}" class="btn btn-secondary">
                    ← Back to List
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" class="toast"></div>

<!-- Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    let map = null;
    let marker = null;

    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function showToast(msg, type = 'success') {
        const toast = document.getElementById('toast');
        toast.innerHTML = msg;
        toast.className = 'toast ' + type;
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 3000);
    }

    // Initialize map
    @if($agent->current_latitude && $agent->current_longitude)
    document.addEventListener('DOMContentLoaded', function() {
        map = L.map('agentMap').setView([{{ $agent->current_latitude }}, {{ $agent->current_longitude }}], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([{{ $agent->current_latitude }}, {{ $agent->current_longitude }}]).addTo(map)
            .bindPopup(`<b>{{ $agent->name }}</b><br>Current Location`)
            .openPopup();
    });
    @endif

    // Change status
    function changeStatus() {
        const newStatus = prompt('Enter new status (available, busy, offline):', '{{ $agent->status }}');

        if (!newStatus || !['available', 'busy', 'offline'].includes(newStatus)) {
            showToast('Invalid status. Use: available, busy, or offline', 'error');
            return;
        }

        updateStatus(newStatus);
    }

    function markAvailable() {
        updateStatus('available');
    }

    function markBusy() {
        updateStatus('busy');
    }

    function markOffline() {
        updateStatus('offline');
    }

    function updateStatus(status) {
        showLoading();

        fetch('{{ route("logistics.agents.update-status", $agent->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('✅ Status updated successfully!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('❌ ' + data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('❌ Error updating status', 'error');
            console.error(error);
        });
    }

    // View document
    function viewDocument(url) {
        window.open(url, '_blank');
    }

    // Document upload
    document.getElementById('documentUpload').addEventListener('change', function(e) {
        if (this.files.length > 0) {
            showLoading();

            const formData = new FormData();
            for (let i = 0; i < this.files.length; i++) {
                formData.append('documents[]', this.files[i]);
            }

            fetch('{{ route("logistics.agents.upload-documents", $agent->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showToast('✅ Documents uploaded successfully!');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('❌ ' + data.message, 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showToast('❌ Error uploading documents', 'error');
                console.error(error);
            });
        }
    });
</script>
@endsection
