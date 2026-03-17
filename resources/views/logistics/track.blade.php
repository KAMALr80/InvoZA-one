{{-- resources/views/logistics/track.blade.php --}}
@extends('layouts.app')

@section('title', 'Track Shipment - ' . $shipment->shipment_number)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .info-card {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pending { background: #ffc107; color: #000; }
    .status-picked { background: #17a2b8; color: #fff; }
    .status-in_transit { background: #007bff; color: #fff; }
    .status-out_for_delivery { background: #fd7e14; color: #fff; }
    .status-delivered { background: #28a745; color: #fff; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        Live Tracking: {{ $shipment->shipment_number }}
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Shipment Info Card -->
            <div class="info-card mb-3">
                <h5><i class="fas fa-info-circle"></i> Shipment Details</h5>
                <hr>
                <p><strong>Status:</strong>
                    <span class="status-badge status-{{ str_replace('_', '-', $shipment->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </p>
                <p><strong>Tracking #:</strong> {{ $shipment->tracking_number ?? 'N/A' }}</p>
                <p><strong>Receiver:</strong> {{ $shipment->receiver_name }}</p>
                <p><strong>Phone:</strong> {{ $shipment->receiver_phone }}</p>
                <p><strong>Address:</strong> {{ $shipment->full_address }}</p>
                <p><strong>Est. Delivery:</strong>
                    {{ $shipment->estimated_delivery_date?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <!-- Location Info Card -->
            <div class="info-card mb-3">
                <h5><i class="fas fa-location-dot"></i> Current Location</h5>
                <hr>
                <p id="current-location">Loading...</p>
                <p id="last-updated">Last updated: {{ $shipment->last_location_update?->diffForHumans() ?? 'N/A' }}</p>
            </div>

            <!-- Tracking Timeline -->
            <div class="info-card">
                <h5><i class="fas fa-history"></i> Tracking History</h5>
                <hr>
                <div style="max-height: 300px; overflow-y: auto;">
                    <ul class="timeline">
                        @forelse($shipment->trackings()->latest()->take(10)->get() as $track)
                        <li class="timeline-item">
                            <div class="timeline-badge"></div>
                            <div class="timeline-content">
                                <strong>{{ ucfirst(str_replace('_', ' ', $track->status)) }}</strong>
                                <p class="text-muted small mb-0">
                                    {{ $track->tracked_at->format('d M Y, h:i A') }}
                                </p>
                                @if($track->location)
                                <p class="small mb-0">{{ $track->location }}</p>
                                @endif
                            </div>
                        </li>
                        @empty
                        <p class="text-muted">No tracking history</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    var map = L.map('map').setView([{{ $currentLat }}, {{ $currentLng }}], 12);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Custom icons
    var warehouseIcon = L.divIcon({
        html: '<i class="fas fa-warehouse" style="color: #007bff; font-size: 24px;"></i>',
        className: 'custom-div-icon',
        iconSize: [30, 30],
        popupAnchor: [0, -15]
    });

    var currentIcon = L.divIcon({
        html: '<i class="fas fa-truck" style="color: #28a745; font-size: 24px;"></i>',
        className: 'custom-div-icon',
        iconSize: [30, 30],
        popupAnchor: [0, -15]
    });

    var destinationIcon = L.divIcon({
        html: '<i class="fas fa-flag-checkered" style="color: #dc3545; font-size: 24px;"></i>',
        className: 'custom-div-icon',
        iconSize: [30, 30],
        popupAnchor: [0, -15]
    });

    // Add warehouse marker
    L.marker([{{ $warehouse['lat'] }}, {{ $warehouse['lng'] }}], {icon: warehouseIcon})
        .addTo(map)
        .bindPopup('<b>{{ $warehouse['name'] }}</b><br>Pickup Location');

    // Add current location marker
    var currentMarker = L.marker([{{ $currentLat }}, {{ $currentLng }}], {icon: currentIcon})
        .addTo(map)
        .bindPopup('<b>Current Location</b><br>Shipment is here');

    // Add destination marker
    L.marker([{{ $destination['lat'] }}, {{ $destination['lng'] }}], {icon: destinationIcon})
        .addTo(map)
        .bindPopup('<b>{{ $destination['name'] }}</b><br>{{ $destination['address'] }}');

    // Draw route if we have tracking points
    @if($trackingPoints->count() > 0)
    var points = [
        @foreach($trackingPoints as $point)
            [{{ $point['lat'] }}, {{ $point['lng'] }}],
        @endforeach
        [{{ $currentLat }}, {{ $currentLng }}],
        [{{ $destination['lat'] }}, {{ $destination['lng'] }}]
    ];

    var polyline = L.polyline(points, {color: 'blue', weight: 3}).addTo(map);
    map.fitBounds(polyline.getBounds());
    @endif

    // Reverse geocoding to get address
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat={{ $currentLat }}&lon={{ $currentLng }}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('current-location').innerHTML =
                `<strong>📍 ${data.display_name || 'Location not found'}</strong>`;
        })
        .catch(error => {
            document.getElementById('current-location').innerHTML =
                `<strong>📍 {{ $currentLat }}, {{ $currentLng }}</strong>`;
        });
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
});
</script>
@endsection
