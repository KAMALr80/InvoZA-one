{{-- resources/views/logistics/service-areas.blade.php --}}
@extends('layouts.app')

@section('title', 'Service Areas & Coverage Map')

@section('content')
<style>
    /* ================= PROFESSIONAL SERVICE AREAS STYLES ================= */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        min-height: 100vh;
    }

    /* ================= MAIN CONTAINER ================= */
    .service-areas-page {
        padding: 2rem 1.5rem;
        max-width: 1600px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ================= PAGE HEADER ================= */
    .page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 30px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .header-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .header-text p {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0;
    }

    /* ================= MAIN GRID ================= */
    .service-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
    }

    @media (max-width: 1200px) {
        .service-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ================= MAP CARD ================= */
    .map-card {
        background: white;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .map-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .map-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .map-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .map-tab:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .map-tab.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .map-tab i {
        font-size: 1.1rem;
    }

    .map-container {
        height: 600px;
        width: 100%;
        position: relative;
    }

    #serviceMap {
        height: 100%;
        width: 100%;
        z-index: 1;
    }

    .map-controls {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .map-control-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: white;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-size: 1.2rem;
    }

    .map-control-btn:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    /* ================= SIDEBAR CARDS ================= */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .stats-card {
        background: white;
        border-radius: 30px;
        padding: 1.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(102, 126, 234, 0.2);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea15, #764ba215);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 1.2rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .card-subtitle {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0.25rem 0 0;
    }

    /* ================= STATS GRID ================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: linear-gradient(135deg, #667eea10, #764ba210);
        transform: scale(1.05);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .stat-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 1rem 0;
    }

    /* ================= AGENT LIST ================= */
    .agent-list {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .agent-list::-webkit-scrollbar {
        width: 6px;
    }

    .agent-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .agent-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }

    .agent-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .agent-item:hover {
        background: white;
        border-color: #667eea;
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
    }

    .agent-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .agent-status {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }

    .agent-status.available {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .agent-status.busy {
        background: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }

    .agent-status.offline {
        background: #6b7280;
        box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.2);
    }

    .agent-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 1rem;
    }

    .agent-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .agent-detail {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .agent-detail i {
        color: #667eea;
        font-size: 0.8rem;
    }

    .agent-badge {
        background: linear-gradient(135deg, #667eea15, #764ba215);
        color: #667eea;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ================= LEGEND ================= */
    .legend {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .legend-item:hover {
        background: #f8fafc;
    }

    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        position: relative;
    }

    .legend-color.available {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .legend-color.busy {
        background: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }

    .legend-color.offline {
        background: #6b7280;
        box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.2);
    }

    .legend-color.hotzone {
        background: linear-gradient(135deg, #ef4444, #f97316);
    }

    .legend-color.zone {
        background: rgba(59, 130, 246, 0.2);
        border: 2px solid #3b82f6;
    }

    .legend-text {
        font-weight: 500;
        color: #1e293b;
    }

    .legend-count {
        margin-left: auto;
        background: #f1f5f9;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #475569;
    }

    /* ================= CITY GRID ================= */
    .city-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .city-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 0.75rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .city-item:hover {
        background: white;
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .city-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .city-count {
        font-size: 0.8rem;
        color: #667eea;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    /* ================= LOADING OVERLAY ================= */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        z-index: 11000;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1.5rem;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e5e7eb;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        color: #1e293b;
        font-weight: 600;
        font-size: 1.1rem;
        background: white;
        padding: 1rem 2rem;
        border-radius: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast {
        position: fixed;
        top: 30px;
        right: 30px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-left: 4px solid;
        display: none;
        z-index: 10000;
        max-width: 400px;
        width: calc(100% - 60px);
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
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

    .toast-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #1e293b;
    }

    .toast-icon {
        font-size: 1.5rem;
    }

    .toast-message {
        font-weight: 500;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .service-areas-page {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-text h1 {
            font-size: 2rem;
        }

        .map-tabs {
            flex-direction: column;
        }

        .map-tab {
            width: 100%;
            justify-content: center;
        }

        .map-container {
            height: 400px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .city-grid {
            grid-template-columns: 1fr;
        }

        .toast {
            left: 15px;
            right: 15px;
            width: calc(100% - 30px);
        }
    }

    /* Leaflet Custom Styles */
    .custom-div-icon {
        background: transparent;
        border: none;
    }

    .leaflet-popup-content {
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        line-height: 1.5;
        color: #1e293b;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .leaflet-popup-tip {
        background: white;
    }
</style>

<div class="service-areas-page">
    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Loading map data...</div>
    </div>

    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="header-text">
                <h1>Service Areas & Coverage</h1>
                <p><i class="fas fa-location-dot"></i> Track delivery agents, heatmaps, and service zones</p>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="service-grid">
        {{-- Left Column - Map --}}
        <div class="map-card">
            <div class="map-header">
                <div class="map-tabs" id="mapTabs">
                    <button class="map-tab active" data-tab="agents">
                        <i class="fas fa-users"></i> Delivery Agents
                    </button>
                    <button class="map-tab" data-tab="heatmap">
                        <i class="fas fa-fire"></i> Delivery Heatmap
                    </button>
                    <button class="map-tab" data-tab="coverage">
                        <i class="fas fa-map-marked-alt"></i> Coverage Areas
                    </button>
                    <button class="map-tab" data-tab="zones">
                        <i class="fas fa-draw-polygon"></i> Service Zones
                    </button>
                </div>
            </div>
            <div class="map-container">
                <div id="serviceMap"></div>
                <div class="map-controls">
                    <button class="map-control-btn" onclick="centerMap()" title="Center Map">
                        <i class="fas fa-crosshairs"></i>
                    </button>
                    <button class="map-control-btn" onclick="zoomIn()" title="Zoom In">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="map-control-btn" onclick="zoomOut()" title="Zoom Out">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button class="map-control-btn" onclick="refreshMap()" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Column - Sidebar --}}
        <div class="sidebar">
            {{-- Statistics Card --}}
            <div class="stats-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h3 class="card-title">Service Statistics</h3>
                        <p class="card-subtitle">Real-time delivery metrics</p>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ $agents->count() }}</div>
                        <div class="stat-label">Total Agents</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $agents->where('status', 'available')->count() }}</div>
                        <div class="stat-label">Available</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $agents->where('status', 'busy')->count() }}</div>
                        <div class="stat-label">Busy</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $agents->where('status', 'offline')->count() }}</div>
                        <div class="stat-label">Offline</div>
                    </div>
                </div>

                <div class="stat-divider"></div>

                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.9rem; color: #64748b;">Coverage Pincodes</div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $coverage->count() }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; color: #64748b;">Cities Covered</div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $coverage->pluck('city')->unique()->count() }}</div>
                    </div>
                </div>
            </div>

            {{-- Active Agents Card --}}
            <div class="stats-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <h3 class="card-title">Active Agents</h3>
                        <p class="card-subtitle">Currently on duty</p>
                    </div>
                </div>

                <div class="agent-list">
                    @forelse($agents->where('status', '!=', 'offline') as $agent)
                    <div class="agent-item" onclick="focusAgent({{ $agent->current_latitude ?? 22.524768 }}, {{ $agent->current_longitude ?? 72.955568 }})">
                        <div class="agent-header">
                            <span class="agent-status {{ $agent->status }}"></span>
                            <span class="agent-name">{{ $agent->name }}</span>
                            @if($agent->successful_deliveries > 100)
                                <span class="agent-badge">⭐ Top Performer</span>
                            @endif
                        </div>
                        <div class="agent-details">
                            <span class="agent-detail">
                                <i class="fas fa-map-pin"></i> {{ $agent->city ?? 'Location unknown' }}
                            </span>
                            <span class="agent-detail">
                                <i class="fas fa-box"></i> {{ $agent->successful_deliveries }} deliveries
                            </span>
                            @if($agent->vehicle_type)
                            <span class="agent-detail">
                                <i class="fas fa-motorcycle"></i> {{ ucfirst($agent->vehicle_type) }}
                            </span>
                            @endif
                        </div>
                        @if($agent->current_latitude && $agent->current_longitude)
                        <div style="font-size: 0.75rem; color: #10b981; margin-top: 0.5rem;">
                            <i class="fas fa-satellite-dish"></i> Live location available
                        </div>
                        @endif
                    </div>
                    @empty
                    <div style="text-align: center; padding: 2rem; color: #64748b;">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>No active agents at the moment</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Legend Card --}}
            <div class="stats-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-legend"></i>
                    </div>
                    <div>
                        <h3 class="card-title">Map Legend</h3>
                        <p class="card-subtitle">Understanding the map</p>
                    </div>
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color available"></div>
                        <span class="legend-text">Available Agent</span>
                        <span class="legend-count">{{ $agents->where('status', 'available')->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color busy"></div>
                        <span class="legend-text">Busy Agent</span>
                        <span class="legend-count">{{ $agents->where('status', 'busy')->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color offline"></div>
                        <span class="legend-text">Offline Agent</span>
                        <span class="legend-count">{{ $agents->where('status', 'offline')->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color hotzone"></div>
                        <span class="legend-text">Hot Zone (High Deliveries)</span>
                        <span class="legend-count">{{ $hotspots->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color zone"></div>
                        <span class="legend-text">5km Service Radius</span>
                        <span class="legend-count">1 zone</span>
                    </div>
                </div>
            </div>

            {{-- Top Cities Card --}}
            <div class="stats-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-city"></i>
                    </div>
                    <div>
                        <h3 class="card-title">Top Cities</h3>
                        <p class="card-subtitle">By delivery volume</p>
                    </div>
                </div>

                <div class="city-grid">
                    @foreach($coverage->groupBy('city')->sortByDesc(function($items) { return $items->count(); })->take(6) as $city => $areas)
                    <div class="city-item">
                        <div class="city-name">{{ $city }}</div>
                        <div class="city-count">{{ $areas->count() }} pincodes</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="toast"></div>

<!-- Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // ==================== MAP INITIALIZATION ====================
    let map = null;
    let agentsLayer = null;
    let heatLayer = null;
    let coverageLayer = null;
    let zonesLayer = null;
    let currentTab = 'agents';

    // Gujarat center coordinates
    const GUJARAT_CENTER = { lat: 22.524768, lng: 72.955568 };

    document.addEventListener('DOMContentLoaded', function() {
        initializeMap();
        setupTabs();
        loadAllLayers();
    });

    function initializeMap() {
        map = L.map('serviceMap').setView([GUJARAT_CENTER.lat, GUJARAT_CENTER.lng], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Add scale control
        L.control.scale({ imperial: false, metric: true }).addTo(map);
    }

    function setupTabs() {
        const tabs = document.querySelectorAll('.map-tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');

                const tabName = this.dataset.tab;
                switchTab(tabName);
            });
        });
    }

    function switchTab(tabName) {
        currentTab = tabName;

        // Clear all layers
        if (agentsLayer) map.removeLayer(agentsLayer);
        if (heatLayer) map.removeLayer(heatLayer);
        if (coverageLayer) map.removeLayer(coverageLayer);
        if (zonesLayer) map.removeLayer(zonesLayer);

        // Load selected layer
        switch(tabName) {
            case 'agents':
                loadAgentsLayer();
                break;
            case 'heatmap':
                loadHeatmapLayer();
                break;
            case 'coverage':
                loadCoverageLayer();
                break;
            case 'zones':
                loadZonesLayer();
                break;
        }
    }

    function loadAllLayers() {
        loadAgentsLayer();
    }

    function loadAgentsLayer() {
        agentsLayer = L.layerGroup();

        @foreach($agents as $agent)
            @if($agent->current_latitude && $agent->current_longitude)
            (function() {
                let color = '#10b981'; // available
                @if($agent->status == 'busy')
                    color = '#f59e0b';
                @elseif($agent->status == 'offline')
                    color = '#6b7280';
                @endif

                // Create custom icon
                let agentIcon = L.divIcon({
                    html: `<div style="
                        width: 30px;
                        height: 30px;
                        background: ${color};
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 16px;
                        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                        border: 2px solid white;
                    "><i class="fas fa-truck"></i></div>`,
                    className: 'custom-div-icon',
                    iconSize: [30, 30],
                    popupAnchor: [0, -15]
                });

                let marker = L.marker([{{ $agent->current_latitude }}, {{ $agent->current_longitude }}], {
                    icon: agentIcon
                }).bindPopup(`
                    <div style="min-width: 200px;">
                        <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 5px;">{{ $agent->name }}</div>
                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: ${color};"></span>
                            <span style="text-transform: capitalize;">{{ $agent->status }}</span>
                        </div>
                        <div style="margin-bottom: 5px;"><i class="fas fa-phone"></i> {{ $agent->phone }}</div>
                        <div style="margin-bottom: 5px;"><i class="fas fa-box"></i> {{ $agent->successful_deliveries }} deliveries</div>
                        <div><i class="fas fa-map-pin"></i> {{ $agent->city ?? 'Location unknown' }}</div>
                    </div>
                `);

                agentsLayer.addLayer(marker);
            })();
            @endif
        @endforeach

        agentsLayer.addTo(map);
    }

    function loadHeatmapLayer() {
        heatLayer = L.heatLayer([
            @foreach($hotspots as $spot)
            [{{ $spot->lat }}, {{ $spot->lng }}, {{ $spot->count }}],
            @endforeach
        ], {
            radius: 30,
            blur: 20,
            maxZoom: 12,
            gradient: {
                0.2: '#3b82f6',
                0.4: '#8b5cf6',
                0.6: '#f59e0b',
                0.8: '#ef4444'
            }
        });

        heatLayer.addTo(map);
    }

    function loadCoverageLayer() {
        coverageLayer = L.layerGroup();

        @foreach($coverage->groupBy('pincode') as $pincode => $areas)
            @php
                $area = $areas->first();
            @endphp
            L.circle([{{ $area->lat ?? 22.524768 }}, {{ $area->lng ?? 72.955568 }}], {
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                radius: 2000
            }).bindPopup(`
                <b>Pincode: {{ $pincode }}</b><br>
                City: {{ $area->city }}<br>
                Deliveries: {{ $areas->count() }}
            `).addTo(coverageLayer);
        @endforeach

        coverageLayer.addTo(map);
    }

    function loadZonesLayer() {
        zonesLayer = L.layerGroup();

        // Main warehouse zone (5km radius)
        L.circle([22.524768, 72.955568], {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.1,
            radius: 5000,
            weight: 2
        }).bindPopup(`
            <b>Main Warehouse</b><br>
            5km Service Zone<br>
            Priority Delivery Area
        `).addTo(zonesLayer);

        // Secondary zones (10km radius)
        L.circle([22.3072, 73.1812], { // Vadodara
            color: '#f59e0b',
            fillColor: '#f59e0b',
            fillOpacity: 0.1,
            radius: 8000,
            weight: 2
        }).bindPopup(`
            <b>Vadodara Hub</b><br>
            8km Service Zone
        `).addTo(zonesLayer);

        L.circle([21.1702, 72.8311], { // Surat
            color: '#f59e0b',
            fillColor: '#f59e0b',
            fillOpacity: 0.1,
            radius: 8000,
            weight: 2
        }).bindPopup(`
            <b>Surat Hub</b><br>
            8km Service Zone
        `).addTo(zonesLayer);

        zonesLayer.addTo(map);
    }

    // ==================== UTILITY FUNCTIONS ====================

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">${type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️'}</span>
                <span class="toast-message">${message}</span>
            </div>
        `;
        toast.className = 'toast ' + type;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function centerMap() {
        map.setView([GUJARAT_CENTER.lat, GUJARAT_CENTER.lng], 10);
        showToast('Map centered to Gujarat', 'info');
    }

    function zoomIn() {
        map.zoomIn();
    }

    function zoomOut() {
        map.zoomOut();
    }

    function refreshMap() {
        showLoading();
        setTimeout(() => {
            // Reload current tab
            switchTab(currentTab);
            hideLoading();
            showToast('Map refreshed successfully', 'success');
        }, 1000);
    }

    function focusAgent(lat, lng) {
        map.setView([lat, lng], 15);
        showToast('Focusing on agent location', 'info');
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    });
</script>
@endsection
