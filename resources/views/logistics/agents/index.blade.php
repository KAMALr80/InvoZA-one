@extends('layouts.app')

@section('page-title', 'Delivery Agents Management')

@section('content')
<style>
    /* ================= PROFESSIONAL AGENTS LIST STYLES ================= */
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
    .agents-page {
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
    .agents-card {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        width: 100%;
        border: 1px solid #e5e7eb;
    }

    /* ================= HEADER ================= */
    .agents-header {
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
        flex: 1;
        min-width: 280px;
    }

    .header-title {
        font-size: clamp(1.5rem, 5vw, 2rem);
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .header-subtitle {
        opacity: 0.9;
        font-size: clamp(0.9rem, 2.5vw, 1rem);
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

    .stat-icon.available {
        background: #10b981;
        color: white;
    }

    .stat-icon.busy {
        background: #f59e0b;
        color: white;
    }

    .stat-icon.offline {
        background: #64748b;
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

    /* ================= FILTER BAR ================= */
    .filter-bar {
        padding: 1.25rem clamp(1.5rem, 4vw, 2rem);
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        background: white;
        transition: all 0.2s;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        white-space: nowrap;
    }

    .filter-btn-primary {
        background: #3b82f6;
        color: white;
    }

    .filter-btn-primary:hover {
        background: #2563eb;
    }

    .filter-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e5e7eb;
    }

    .filter-btn-secondary:hover {
        background: #e2e8f0;
    }

    /* ================= TABLE SECTION ================= */
    .table-section {
        padding: 0 clamp(1.5rem, 4vw, 2rem) 1.5rem;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: white;
    }

    .agents-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }

    .agents-table th {
        background: #f8fafc;
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    .agents-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
        font-size: 0.95rem;
        color: #1e293b;
    }

    .agents-table tbody tr {
        transition: all 0.2s;
    }

    .agents-table tbody tr:hover {
        background: #f8fafc;
    }

    .agents-table tbody tr.status-available {
        background: #f0fdf4;
    }

    .agents-table tbody tr.status-busy {
        background: #fffbeb;
    }

    .agents-table tbody tr.status-offline {
        background: #f1f5f9;
    }

    /* ================= AGENT INFO ================= */
    .agent-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .agent-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .agent-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .agent-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 1rem;
    }

    .agent-code {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* ================= STATUS BADGES ================= */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.available {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.busy {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.offline {
        background: #e2e8f0;
        color: #475569;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot.available {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .status-dot.busy {
        background: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }

    .status-dot.offline {
        background: #64748b;
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.2);
    }

    /* ================= VEHICLE INFO ================= */
    .vehicle-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .vehicle-icon {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
    }

    .vehicle-details {
        display: flex;
        flex-direction: column;
    }

    .vehicle-type {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .vehicle-number {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* ================= RATING ================= */
    .rating {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .rating-stars {
        display: flex;
        gap: 0.125rem;
    }

    .star-filled {
        color: #f59e0b;
    }

    .star-empty {
        color: #e5e7eb;
    }

    .rating-value {
        font-weight: 600;
        color: #1e293b;
        margin-left: 0.25rem;
    }

    .rating-count {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* ================= DELIVERY STATS ================= */
    .delivery-stats {
        display: flex;
        flex-direction: column;
    }

    .delivery-count {
        font-weight: 700;
        color: #1e293b;
        font-size: 1rem;
    }

    .success-rate {
        font-size: 0.8rem;
        color: #10b981;
        font-weight: 600;
    }

    /* ================= ACTION BUTTONS ================= */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        white-space: nowrap;
    }

    .action-btn-view {
        background: #3b82f6;
        color: white;
    }

    .action-btn-view:hover {
        background: #2563eb;
    }

    .action-btn-edit {
        background: #f59e0b;
        color: white;
    }

    .action-btn-edit:hover {
        background: #d97706;
    }

    .action-btn-status {
        background: #10b981;
        color: white;
    }

    .action-btn-status:hover {
        background: #059669;
    }

    .action-btn-map {
        background: #8b5cf6;
        color: white;
    }

    .action-btn-map:hover {
        background: #7c3aed;
    }

    .action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ================= PAGINATION ================= */
    .pagination-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: white;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-info {
        color: #64748b;
        font-size: 0.95rem;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pagination-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        background: white;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }

    .pagination-btn:hover {
        background: #f1f5f9;
        border-color: #3b82f6;
    }

    .pagination-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ================= MAP MODAL ================= */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-content {
        background: white;
        border-radius: 24px;
        width: 100%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease;
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    #agentMap {
        height: 400px;
        width: 100%;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        z-index: 10000;
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
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        .filter-actions {
            flex-direction: column;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }

        .agents-table {
            min-width: 1000px;
        }

        .pagination-section {
            flex-direction: column;
            align-items: center;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }

    @media print {
        .header-actions,
        .filter-bar,
        .action-buttons,
        .pagination-section,
        .modal {
            display: none !important;
        }

        .status-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="agents-page">
    <div class="container">
        <div class="agents-card">
            {{-- Loading Overlay --}}
            <div id="loadingOverlay" class="loading-overlay">
                <div class="spinner"></div>
                <div class="loading-text">Loading...</div>
            </div>

            {{-- Header --}}
            <div class="agents-header">
                <div class="header-content">
                    <div class="header-left">
                        <h1 class="header-title">Delivery Agents</h1>
                        <p class="header-subtitle">Manage your delivery fleet and track agent performance</p>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn" onclick="refreshAgents()">
                            🔄 Refresh
                        </button>
                        <a href="{{ route('logistics.agents.create') }}" class="header-btn">
                            ➕ Add New Agent
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">👥</div>
                        <div class="stat-label">Total Agents</div>
                    </div>
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stat-sub">Active delivery personnel</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon available">✅</div>
                        <div class="stat-label">Available</div>
                    </div>
                    <div class="stat-value">{{ $stats['available'] ?? 0 }}</div>
                    <div class="stat-sub">Ready for deliveries</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon busy">🚚</div>
                        <div class="stat-label">Busy</div>
                    </div>
                    <div class="stat-value">{{ $stats['busy'] ?? 0 }}</div>
                    <div class="stat-sub">Currently delivering</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon offline">⭕</div>
                        <div class="stat-label">Offline</div>
                    </div>
                    <div class="stat-value">{{ $stats['offline'] ?? 0 }}</div>
                    <div class="stat-sub">Not available</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">📦</div>
                        <div class="stat-label">Total Deliveries</div>
                    </div>
                    <div class="stat-value">{{ number_format($stats['total_deliveries'] ?? 0) }}</div>
                    <div class="stat-sub">All time deliveries</div>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="filter-bar">
                <form method="GET" action="{{ route('logistics.agents.index') }}" class="filter-form" id="filterForm">
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input type="text" name="search" class="filter-input"
                               placeholder="Name, phone, or code..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select name="status" class="filter-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="busy" {{ request('status') == 'busy' ? 'selected' : '' }}>Busy</option>
                            <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">City</label>
                        <input type="text" name="city" class="filter-input"
                               placeholder="Filter by city"
                               value="{{ request('city') }}">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Vehicle Type</label>
                        <select name="vehicle_type" class="filter-select">
                            <option value="">All Vehicles</option>
                            <option value="bike" {{ request('vehicle_type') == 'bike' ? 'selected' : '' }}>Bike</option>
                            <option value="cycle" {{ request('vehicle_type') == 'cycle' ? 'selected' : '' }}>Cycle</option>
                            <option value="van" {{ request('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="truck" {{ request('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="filter-btn filter-btn-primary">
                            🔍 Apply Filters
                        </button>
                        <a href="{{ route('logistics.agents.index') }}" class="filter-btn filter-btn-secondary">
                            🔄 Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Agents Table --}}
            <div class="table-section">
                <div class="table-responsive">
                    <table class="agents-table">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Vehicle</th>
                                <th>Location</th>
                                <th>Performance</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agents as $agent)
                            <tr class="status-{{ $agent->status }}">
                                <td>
                                    <div class="agent-info">
                                        <div class="agent-avatar">
                                            {{ substr($agent->name, 0, 1) }}
                                        </div>
                                        <div class="agent-details">
                                            <span class="agent-name">{{ $agent->name }}</span>
                                            <span class="agent-code">{{ $agent->agent_code }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <span>📱 {{ $agent->phone }}</span>
                                        @if($agent->email)
                                        <span style="font-size: 0.85rem; color: #64748b;">✉️ {{ $agent->email }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $agent->status }}">
                                        <span class="status-dot {{ $agent->status }}"></span>
                                        {{ ucfirst($agent->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="vehicle-info">
                                        <div class="vehicle-icon">
                                            @switch($agent->vehicle_type)
                                                @case('bike') 🏍️ @break
                                                @case('cycle') 🚲 @break
                                                @case('van') 🚐 @break
                                                @case('truck') 🚛 @break
                                                @default 🚗
                                            @endswitch
                                        </div>
                                        <div class="vehicle-details">
                                            <span class="vehicle-type">{{ ucfirst($agent->vehicle_type ?? 'Not assigned') }}</span>
                                            @if($agent->vehicle_number)
                                            <span class="vehicle-number">{{ $agent->vehicle_number }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($agent->city)
                                        <span>📍 {{ $agent->city }}</span>
                                        @if($agent->current_latitude && $agent->current_longitude)
                                        <div style="font-size: 0.8rem; color: #3b82f6; margin-top: 0.25rem;">
                                            <span>Live location available</span>
                                        </div>
                                        @endif
                                    @else
                                        <span style="color: #94a3b8;">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="delivery-stats">
                                        <span class="delivery-count">{{ number_format($agent->successful_deliveries) }} / {{ number_format($agent->total_deliveries) }}</span>
                                        @if($agent->total_deliveries > 0)
                                        <span class="success-rate">
                                            {{ round(($agent->successful_deliveries / $agent->total_deliveries) * 100) }}% success
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($agent->rating))
                                                    <span class="star-filled">★</span>
                                                @else
                                                    <span class="star-empty">☆</span>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ number_format($agent->rating, 1) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('logistics.agents.show', $agent->id) }}" class="action-btn action-btn-view" title="View Details">
                                            👁️ View
                                        </a>
                                        <a href="{{ route('logistics.agents.edit', $agent->id) }}" class="action-btn action-btn-edit" title="Edit Agent">
                                            ✏️ Edit
                                        </a>
                                        @if($agent->current_latitude && $agent->current_longitude)
                                        <button class="action-btn action-btn-map" onclick="showAgentMap({{ $agent->id }}, '{{ $agent->name }}', {{ $agent->current_latitude }}, {{ $agent->current_longitude }})" title="View on Map">
                                            🗺️ Map
                                        </button>
                                        @endif
                                        <button class="action-btn action-btn-status" onclick="changeStatus({{ $agent->id }}, '{{ $agent->status }}')" title="Change Status">
                                            🔄 Status
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 3rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                                    <h3 style="color: #1e293b; margin-bottom: 0.5rem;">No Agents Found</h3>
                                    <p style="color: #64748b; margin-bottom: 1.5rem;">Add your first delivery agent to get started</p>
                                    <a href="{{ route('logistics.agents.create') }}" class="filter-btn filter-btn-primary">
                                        ➕ Add New Agent
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if(isset($agents) && $agents->hasPages())
            <div class="pagination-section">
                <div class="pagination-info">
                    Showing {{ $agents->firstItem() }} to {{ $agents->lastItem() }} of {{ $agents->total() }} agents
                </div>
                <div class="pagination">
                    {{-- Previous Page Link --}}
                    @if($agents->onFirstPage())
                        <span class="pagination-btn disabled">←</span>
                    @else
                        <a href="{{ $agents->previousPageUrl() }}" class="pagination-btn">←</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($agents->getUrlRange(max(1, $agents->currentPage() - 2), min($agents->lastPage(), $agents->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" class="pagination-btn {{ $page == $agents->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($agents->hasMorePages())
                        <a href="{{ $agents->nextPageUrl() }}" class="pagination-btn">→</a>
                    @else
                        <span class="pagination-btn disabled">→</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Agent Map Modal --}}
<div id="agentMapModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalAgentName">Agent Location</h3>
            <button class="modal-close" onclick="closeAgentMap()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="agentMap"></div>
        </div>
        <div class="modal-footer">
            <button class="filter-btn filter-btn-secondary" onclick="closeAgentMap()">Close</button>
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

    function refreshAgents() {
        showLoading();
        window.location.reload();
    }

    function showAgentMap(id, name, lat, lng) {
        document.getElementById('modalAgentName').textContent = name + ' - Current Location';
        document.getElementById('agentMapModal').style.display = 'flex';

        setTimeout(() => {
            if (map) {
                map.remove();
            }

            map = L.map('agentMap').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(`<b>${name}</b><br>Current Location`)
                .openPopup();
        }, 100);
    }

    function closeAgentMap() {
        document.getElementById('agentMapModal').style.display = 'none';
        if (map) {
            map.remove();
            map = null;
        }
    }

    function changeStatus(agentId, currentStatus) {
        const newStatus = prompt(`Change status from "${currentStatus}" to:`, 'available');

        if (!newStatus || !['available', 'busy', 'offline'].includes(newStatus)) {
            showToast('Invalid status. Use: available, busy, or offline', 'error');
            return;
        }

        showLoading();

        fetch(`/logistics/agents/${agentId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
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

    // Auto-refresh every 5 minutes
    setInterval(() => {
        if (!document.getElementById('loadingOverlay').style.display === 'flex') {
            location.reload();
        }
    }, 300000);
</script>
@endsection
