{{-- resources/views/logistics/shipments/create.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Create New Shipment')

@section('content')
<style>
    /* ================= PROFESSIONAL CREATE SHIPMENT STYLES ================= */
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
    .create-shipment-page {
        padding: 2rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ================= PAGE HEADER ================= */
    .page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
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
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
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

    /* ================= MAIN CARD ================= */
    .shipment-card {
        background: white;
        border-radius: 30px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    /* ================= CARD HEADER ================= */
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        border-bottom: 3px solid #667eea;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-header-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .card-header-text h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
    }

    .card-header-text p {
        margin: 0.25rem 0 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    /* ================= FORM CONTAINER ================= */
    .form-container {
        padding: 2rem;
    }

    /* ================= SECTION STYLES ================= */
    .form-section {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .form-section:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border-color: #667eea;
    }

    .form-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 4px 0 0 4px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0.25rem 0 0;
    }

    /* ================= FORM GRID ================= */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
        position: relative;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-label i {
        color: #667eea;
        font-size: 1rem;
    }

    .required-star {
        color: #ef4444;
        margin-left: 0.25rem;
        font-size: 1.2rem;
        line-height: 1;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        background: white;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control:hover {
        border-color: #94a3b8;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
    }

    /* ================= DIMENSIONS GRID ================= */
    .dimensions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .dimension-item {
        position: relative;
    }

    .dimension-item .form-control {
        padding-right: 2.5rem;
    }

    .dimension-unit {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
        background: #f1f5f9;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
    }

    /* ================= COURIER SELECT ================= */
    .courier-select {
        position: relative;
    }

    .courier-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .courier-option {
        position: relative;
    }

    .courier-option input[type="radio"] {
        display: none;
    }

    .courier-option label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .courier-option label:hover {
        border-color: #667eea;
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .courier-option input[type="radio"]:checked + label {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea10, #764ba210);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    }

    .courier-icon {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    /* ================= PRICE PREVIEW ================= */
    .price-preview {
        background: linear-gradient(135deg, #f8fafc, #e9ecef);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .price-item {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .price-label {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .price-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }

    .price-value.total {
        color: #667eea;
        font-size: 1.75rem;
    }

    /* ================= SUBMIT BUTTON ================= */
    .submit-section {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px dashed #e5e7eb;
    }

    .btn {
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn i {
        font-size: 1.1rem;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .create-shipment-page {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-text h1 {
            font-size: 2rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .dimensions-grid {
            grid-template-columns: 1fr;
        }

        .submit-section {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .courier-options {
            grid-template-columns: 1fr;
        }
    }

    /* ================= ANIMATIONS ================= */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        animation: slideIn 0.5s ease forwards;
    }

    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }

    /* ================= LOADING SPINNER ================= */
    .spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ================= TOAST NOTIFICATION ================= */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-left: 4px solid #667eea;
        z-index: 9999;
        display: none;
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
</style>

<div class="create-shipment-page">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <span>📦</span>
            </div>
            <div class="header-text">
                <h1>Create New Shipment</h1>
                <p>Fill in the details below to create a new shipment</p>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="shipment-card">
        <div class="card-header">
            <div class="card-header-icon">
                <span>✈️</span>
            </div>
            <div class="card-header-text">
                <h2>Shipment Information</h2>
                <p>Please provide accurate shipping details</p>
            </div>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('logistics.shipments.store') }}" id="shipmentForm">
                @csrf

                {{-- Receiver Details Section --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <span>👤</span>
                        </div>
                        <div>
                            <h3 class="section-title">Receiver Details</h3>
                            <p class="section-subtitle">Enter the recipient's information</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Receiver Name <span class="required-star">*</span>
                            </label>
                            <input type="text" name="receiver_name" class="form-control" required placeholder="Enter receiver's full name">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number <span class="required-star">*</span>
                            </label>
                            <input type="tel" name="receiver_phone" class="form-control" required placeholder="10 digit mobile number">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone-alt"></i>
                                Alternate Phone
                            </label>
                            <input type="tel" name="receiver_alternate_phone" class="form-control" placeholder="Alternate contact number">
                        </div>
                    </div>

                    {{-- Address Search Component --}}
                    <div class="mt-3">
                        <x-address-search id="shipping" label="Shipping Address *" />
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">
                            <i class="fas fa-map-pin"></i>
                            Landmark
                        </label>
                        <input type="text" name="landmark" class="form-control" placeholder="Nearby landmark (optional)">
                    </div>
                </div>

                {{-- Package Details Section --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <span>📦</span>
                        </div>
                        <div>
                            <h3 class="section-title">Package Details</h3>
                            <p class="section-subtitle">Specify package dimensions and value</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-weight"></i>
                                Weight (kg)
                            </label>
                            <input type="number" step="0.01" name="weight" class="form-control" placeholder="0.00" id="weight">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-box"></i>
                                Quantity
                            </label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" id="quantity">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Package Type
                            </label>
                            <select name="package_type" class="form-control">
                                <option value="box">📦 Box</option>
                                <option value="envelope">✉️ Envelope</option>
                                <option value="pallet">📏 Pallet</option>
                            </select>
                        </div>
                    </div>

                    <div class="dimensions-grid">
                        <div class="dimension-item">
                            <label class="form-label">Length</label>
                            <input type="number" name="length" class="form-control" placeholder="0" id="length">
                            <span class="dimension-unit">cm</span>
                        </div>
                        <div class="dimension-item">
                            <label class="form-label">Width</label>
                            <input type="number" name="width" class="form-control" placeholder="0" id="width">
                            <span class="dimension-unit">cm</span>
                        </div>
                        <div class="dimension-item">
                            <label class="form-label">Height</label>
                            <input type="number" name="height" class="form-control" placeholder="0" id="height">
                            <span class="dimension-unit">cm</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign"></i>
                            Declared Value <span class="required-star">*</span>
                        </label>
                        <input type="number" name="declared_value" class="form-control" required placeholder="0.00" step="0.01" id="declaredValue">
                    </div>
                </div>

                {{-- Shipping Options Section --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <span>🚚</span>
                        </div>
                        <div>
                            <h3 class="section-title">Shipping Options</h3>
                            <p class="section-subtitle">Select courier and shipping method</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-truck"></i>
                                Shipping Method <span class="required-star">*</span>
                            </label>
                            <select name="shipping_method" class="form-control" required id="shippingMethod">
                                <option value="standard">🚚 Standard (3-5 days)</option>
                                <option value="express">⚡ Express (1-2 days)</option>
                                <option value="overnight">🌙 Overnight</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-credit-card"></i>
                                Payment Mode <span class="required-star">*</span>
                            </label>
                            <select name="payment_mode" class="form-control" required id="paymentMode">
                                <option value="prepaid">💳 Prepaid</option>
                                <option value="cod">💵 Cash on Delivery</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i>
                                Estimated Delivery Date
                            </label>
                            <input type="date" name="estimated_delivery_date" class="form-control"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   value="{{ now()->addDays(3)->format('Y-m-d') }}" id="estimatedDate">
                        </div>
                    </div>

                    {{-- Courier Partners --}}
                    <div class="form-group mt-4">
                        <label class="form-label">
                            <i class="fas fa-building"></i>
                            Courier Partner <span class="required-star">*</span>
                        </label>
                        <div class="courier-options" id="courierOptions">
                            @php
                                $couriers = [
                                    ['code' => 'DELHIVERY', 'name' => 'Delhivery', 'icon' => '🚚'],
                                    ['code' => 'BLUEDART', 'name' => 'BlueDart', 'icon' => '✈️'],
                                    ['code' => 'DTDC', 'name' => 'DTDC', 'icon' => '🚛'],
                                    ['code' => 'FEDEX', 'name' => 'FedEx', 'icon' => '📦'],
                                    ['code' => 'EKART', 'name' => 'Ekart', 'icon' => '🛵'],
                                    ['code' => 'XPRESSBEES', 'name' => 'XpressBees', 'icon' => '🐝'],
                                    ['code' => 'SHADOWFAX', 'name' => 'Shadowfax', 'icon' => '⚡'],
                                ];
                            @endphp

                            @foreach($couriers as $courier)
                            <div class="courier-option">
                                <input type="radio" name="courier_partner" value="{{ $courier['code'] }}"
                                       id="courier_{{ $courier['code'] }}" {{ $loop->first ? 'checked' : '' }}>
                                <label for="courier_{{ $courier['code'] }}">
                                    <span class="courier-icon">{{ $courier['icon'] }}</span>
                                    <span>{{ $courier['name'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                {{-- Delivery Agent Section - ADD THIS AFTER COURIER PARTNERS --}}
<div class="form-section">
    <div class="section-header">
        <div class="section-icon">
            <span>👤</span>
        </div>
        <div>
            <h3 class="section-title">Delivery Agent (Optional)</h3>
            <p class="section-subtitle">Assign shipment to a delivery agent</p>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">
            <i class="fas fa-user-tie"></i>
            Select Delivery Agent
        </label>
        <select name="agent_id" class="form-control">
            <option value="">-- No Agent (Assign Later) --</option>
            @forelse($deliveryAgents as $agent)
                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                    {{ $agent->name }} ({{ $agent->vehicle_type ?? 'Bike' }}) -
                    @if($agent->city){{ $agent->city }}@endif
                    @if($agent->status == 'available') ✅ Available @endif
                </option>
            @empty
                <option value="" disabled>No agents available</option>
            @endforelse
        </select>
        @if(isset($deliveryAgents) && $deliveryAgents->count() == 0)
            <p class="text-warning" style="color: #f59e0b; margin-top: 0.5rem; font-size: 0.9rem;">
                <i class="fas fa-exclamation-triangle"></i>
                No active agents found.
                <a href="{{ route('logistics.agents.create') }}" style="color: #667eea; text-decoration: underline;">
                    Add an agent
                </a> first.
            </p>
        @endif
    </div>
</div>
                {{-- Price Preview --}}
                <div class="price-preview" id="pricePreview">
                    <div class="price-item">
                        <div class="price-label">Shipping Charge</div>
                        <div class="price-value" id="shippingCharge">₹0.00</div>
                    </div>
                    <div class="price-item">
                        <div class="price-label">COD Charge</div>
                        <div class="price-value" id="codCharge">₹0.00</div>
                    </div>
                    <div class="price-item">
                        <div class="price-label">Insurance</div>
                        <div class="price-value" id="insuranceCharge">₹0.00</div>
                    </div>
                    <div class="price-item">
                        <div class="price-label">Total</div>
                        <div class="price-value total" id="totalCharge">₹0.00</div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="submit-section">
                    <a href="{{ route('logistics.shipments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Create Shipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="toast"></div>

<script>
    // Price Calculator
    function calculateCharges() {
        const weight = parseFloat(document.getElementById('weight').value) || 0;
        const declaredValue = parseFloat(document.getElementById('declaredValue').value) || 0;
        const shippingMethod = document.getElementById('shippingMethod').value;
        const paymentMode = document.getElementById('paymentMode').value;

        // Base rates
        let baseRate = 50;
        if (shippingMethod === 'express') baseRate = 75;
        if (shippingMethod === 'overnight') baseRate = 100;

        // Weight based calculation
        const weightCharge = weight * baseRate;

        // COD Charge
        let codCharge = 0;
        if (paymentMode === 'cod') {
            if (declaredValue <= 5000) codCharge = 30;
            else if (declaredValue <= 10000) codCharge = 50;
            else if (declaredValue <= 25000) codCharge = 100;
            else codCharge = declaredValue * 0.005;
        }

        // Insurance (0.1% for values > 10000)
        let insuranceCharge = 0;
        if (declaredValue > 10000) {
            insuranceCharge = declaredValue * 0.001;
        }

        const total = weightCharge + codCharge + insuranceCharge;

        // Update display
        document.getElementById('shippingCharge').textContent = '₹' + weightCharge.toFixed(2);
        document.getElementById('codCharge').textContent = '₹' + codCharge.toFixed(2);
        document.getElementById('insuranceCharge').textContent = '₹' + insuranceCharge.toFixed(2);
        document.getElementById('totalCharge').textContent = '₹' + total.toFixed(2);
    }

    // Add event listeners
    document.getElementById('weight').addEventListener('input', calculateCharges);
    document.getElementById('declaredValue').addEventListener('input', calculateCharges);
    document.getElementById('shippingMethod').addEventListener('change', calculateCharges);
    document.getElementById('paymentMode').addEventListener('change', calculateCharges);

    // Initial calculation
    calculateCharges();

    // Form submission
    document.getElementById('shipmentForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="spinner"></span> Creating...';
        btn.disabled = true;
    });

    // Show toast function
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = 'toast ' + type;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    // Auto-calculate estimated delivery date based on shipping method
    document.getElementById('shippingMethod').addEventListener('change', function() {
        const date = new Date();
        const method = this.value;

        if (method === 'standard') date.setDate(date.getDate() + 3);
        else if (method === 'express') date.setDate(date.getDate() + 1);
        else if (method === 'overnight') date.setDate(date.getDate() + 1);

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        document.getElementById('estimatedDate').value = `${year}-${month}-${day}`;
    });
</script>
@endsection
