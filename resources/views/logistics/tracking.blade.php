{{-- resources/views/logistics/tracking.blade.php --}}
@extends('layouts.app')

@section('title', 'Track Shipment - ' . $shipment->shipment_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Live Tracking: {{ $shipment->shipment_number }}</h4>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Shipment Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong>
                        <span class="badge badge-{{ $shipment->status_badge }}">
                            {{ ucfirst($shipment->status) }}
                        </span>
                    </p>
                    <p><strong>From:</strong> Warehouse</p>
                    <p><strong>To:</strong> {{ $shipment->receiver_name }}</p>
                    <p><strong>Address:</strong> {{ $shipment->full_address }}</p>
                    <p><strong>Estimated Delivery:</strong>
                        {{ $shipment->estimated_delivery_date?->format('d M Y, h:i A') ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Tracking History</h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <ul class="timeline">
                        @foreach($trackingHistory as $history)
                        <li class="timeline-item">
                            <strong>{{ ucfirst($history->status) }}</strong>
                            <p class="text-muted small">
                                {{ $history->tracked_at->format('d M Y, h:i A') }}
                                @if($history->location)
                                    <br>{{ $history->location }}
                                @endif
                            </p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<style>
    .timeline {
        list-style: none;
        padding: 0;
    }
    .timeline-item {
        border-left: 2px solid #007bff;
        padding-left: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        width: 10px;
        height: 10px;
        background: #007bff;
        border-radius: 50%;
        position: absolute;
        left: -6px;
        top: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        var map = L.map('map').setView([{{ $currentLocation['lat'] }}, {{ $currentLocation['lng'] }}], 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add custom icons
        var warehouseIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var destinationIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var currentIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add origin marker
        var originMarker = L.marker([{{ $origin['lat'] }}, {{ $origin['lng'] }}], {icon: warehouseIcon})
            .addTo(map)
            .bindPopup("<b>{{ $origin['name'] }}</b><br>Pickup Location");

        // Add destination marker
        var destMarker = L.marker([{{ $destination['lat'] }}, {{ $destination['lng'] }}], {icon: destinationIcon})
            .addTo(map)
            .bindPopup("<b>{{ $destination['name'] }}</b><br>{{ $destination['address'] }}");

        // Add current location marker
        var currentMarker = L.marker([{{ $currentLocation['lat'] }}, {{ $currentLocation['lng'] }}], {icon: currentIcon})
            .addTo(map)
            .bindPopup("<b>Current Location</b><br>Last updated: {{ $shipment->last_location_update?->format('h:i A') }}");

        // Draw route between points
        L.Routing.control({
            waypoints: [
                L.latLng({{ $origin['lat'] }}, {{ $origin['lng'] }}),
                L.latLng({{ $currentLocation['lat'] }}, {{ $currentLocation['lng'] }}),
                L.latLng({{ $destination['lat'] }}, {{ $destination['lng'] }})
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            createMarker: function() { return null; } // Don't create extra markers
        }).addTo(map);

        // Add path from tracking history
        @if($trackingHistory->count() > 1)
        var pathPoints = [
            @foreach($trackingHistory as $point)
                @if($point->latitude && $point->longitude)
                    [{{ $point->latitude }}, {{ $point->longitude }}],
                @endif
            @endforeach
        ];

        if(pathPoints.length > 0) {
            var polyline = L.polyline(pathPoints, {color: 'blue'}).addTo(map);
            map.fitBounds(polyline.getBounds());
        }
        @endif
    });
</script>
@endpush
