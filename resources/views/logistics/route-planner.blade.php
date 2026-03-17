{{-- resources/views/logistics/route-planner.blade.php --}}
@extends('layouts.app')

@section('title', 'Route Planner & Optimization')

@section('content')
<style>
    /* ================= PROFESSIONAL ROUTE PLANNER STYLES ================= */
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
    .route-planner-page {
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

    /* ================= FILTER CARD ================= */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .filter-card:hover {
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .filter-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea15, #764ba215);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 1.2rem;
    }

    .filter-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .filter-grid {
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
        margin-bottom: 0.5rem;
    }

    .filter-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        background: white;
        transition: all 0.3s ease;
    }

    .filter-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .filter-control:hover {
        border-color: #94a3b8;
    }

    .filter-btn {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .filter-btn i {
        font-size: 1rem;
    }

    /* ================= MAIN GRID ================= */
    .route-grid {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 1200px) {
        .route-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ================= SHIPMENTS PANEL ================= */
    .shipments-panel {
        background: white;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        height: fit-content;
    }

    .panel-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .panel-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea15, #764ba215);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 1.2rem;
    }

    .panel-title h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .panel-title span {
        font-size: 0.85rem;
        color: #64748b;
    }

    .select-all-btn {
        padding: 0.5rem 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .select-all-btn:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    }

    .select-all-btn i {
        font-size: 0.9rem;
    }

    /* ================= SHIPMENT LIST ================= */
    .shipment-list {
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem;
        background: #f8fafc;
    }

    .shipment-list::-webkit-scrollbar {
        width: 6px;
    }

    .shipment-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .shipment-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }

    .shipment-item {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        cursor: move;
        position: relative;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .shipment-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        border-color: #667eea;
    }

    .shipment-item.selected {
        background: linear-gradient(135deg, #667eea10, #764ba210);
        border-color: #667eea;
        border-width: 2px;
    }

    .shipment-item.sortable-ghost {
        opacity: 0.4;
        background: #cbd5e1;
        border: 2px dashed #667eea;
    }

    .shipment-item.sortable-drag {
        opacity: 0.8;
        transform: rotate(2deg);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .shipment-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .shipment-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #667eea;
    }

    .shipment-number {
        font-weight: 700;
        color: #667eea;
        font-size: 0.95rem;
    }

    .shipment-badge {
        background: linear-gradient(135deg, #667eea15, #764ba215);
        color: #667eea;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: auto;
    }

    .shipment-details {
        margin-left: 2.75rem;
    }

    .shipment-customer {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .shipment-address {
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .shipment-address i {
        color: #667eea;
        font-size: 0.8rem;
    }

    .shipment-meta {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .shipment-meta span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .shipment-meta i {
        color: #667eea;
    }

    /* ================= OPTIMIZED ORDER PANEL ================= */
    .order-panel {
        background: white;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        margin-top: 1.5rem;
    }

    .order-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .order-header i {
        color: #667eea;
        font-size: 1.2rem;
    }

    .order-header h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .order-list {
        padding: 1rem;
        min-height: 200px;
        background: #f8fafc;
    }

    .order-item {
        background: white;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .order-item:hover {
        transform: translateX(5px);
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
    }

    .order-number {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .order-content {
        flex: 1;
    }

    .order-customer {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .order-address {
        font-size: 0.8rem;
        color: #64748b;
    }

    .empty-order {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }

    .empty-order i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-order p {
        font-size: 0.95rem;
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
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .map-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .map-title i {
        color: #667eea;
        font-size: 1.2rem;
    }

    .map-title h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .map-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .map-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .map-btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .map-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .map-btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .map-btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }

    .map-btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
    }

    .map-btn-success:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .map-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e5e7eb;
    }

    .map-btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .map-btn i {
        font-size: 1rem;
    }

    .map-container {
        height: 500px;
        width: 100%;
        position: relative;
    }

    #routeMap {
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

    /* ================= ROUTE INFO CARD ================= */
    .route-info-card {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .route-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .route-info-item {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    .route-info-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .route-info-label i {
        color: #667eea;
    }

    .route-info-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .route-info-value small {
        font-size: 0.9rem;
        color: #94a3b8;
        font-weight: normal;
    }

    /* ================= MODAL ================= */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-content {
        background: white;
        border-radius: 30px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 30px 30px 0 0;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-title i {
        color: #667eea;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-form-group {
        margin-bottom: 1.5rem;
    }

    .modal-form-label {
        display: block;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .modal-form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .modal-form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        background: #f8fafc;
        border-radius: 0 0 30px 30px;
    }

    .modal-btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .modal-btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .modal-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .modal-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e5e7eb;
    }

    .modal-btn-secondary:hover {
        background: #e2e8f0;
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

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .route-planner-page {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-text h1 {
            font-size: 2rem;
        }

        .filter-grid {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }

        .map-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .map-actions {
            width: 100%;
        }

        .map-btn {
            flex: 1;
            justify-content: center;
        }

        .route-info-grid {
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

    .leaflet-routing-container {
        background: white;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        font-family: 'Inter', sans-serif;
        max-height: 300px;
        overflow-y: auto;
    }

    .leaflet-routing-alt {
        border-bottom: 1px solid #e5e7eb;
    }

    .leaflet-routing-alt h2 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .leaflet-routing-geocoders {
        border-top: 1px solid #e5e7eb;
    }
</style>

<div class="route-planner-page">
    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Optimizing route...</div>
    </div>

    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-route"></i>
            </div>
            <div class="header-text">
                <h1>Route Planner & Optimization</h1>
                <p><i class="fas fa-map-marked-alt"></i> Plan and optimize delivery routes for maximum efficiency</p>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="filter-card">
        <div class="filter-header">
            <div class="filter-icon">
                <i class="fas fa-filter"></i>
            </div>
            <h3 class="filter-title">Filter Shipments</h3>
        </div>
        <form method="GET" action="{{ route('logistics.route-planner') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Delivery Date</label>
                    <input type="date" name="date" class="filter-control" value="{{ $date }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Delivery Agent</label>
                    <select name="agent_id" class="filter-control">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                        <option value="{{ $agent->user_id }}" {{ $agentId == $agent->user_id ? 'selected' : '' }}>
                            {{ $agent->name }} ({{ $agent->vehicle_type ?? 'Bike' }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="filter-btn">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Main Grid --}}
    <div class="route-grid">
        {{-- Left Panel - Shipments List --}}
        <div>
            <div class="shipments-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <div class="panel-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h3>Available Shipments</h3>
                            <span>{{ $shipments->count() }} shipments found</span>
                        </div>
                    </div>
                    <button type="button" class="select-all-btn" id="selectAllBtn">
                        <i class="fas fa-check-double"></i> Select All
                    </button>
                </div>
                <div class="shipment-list" id="shipmentList">
                    @forelse($shipments as $shipment)
                    <div class="shipment-item" data-id="{{ $shipment->id }}"
                         data-lat="{{ $shipment->destination_latitude ?? 22.524768 }}"
                         data-lng="{{ $shipment->destination_longitude ?? 72.955568 }}"
                         data-address="{{ $shipment->full_address }}"
                         data-customer="{{ $shipment->receiver_name }}"
                         data-city="{{ $shipment->city }}"
                         data-phone="{{ $shipment->receiver_phone }}">
                        <div class="shipment-header">
                            <input type="checkbox" class="shipment-checkbox" value="{{ $shipment->id }}">
                            <span class="shipment-number">{{ $shipment->shipment_number }}</span>
                            <span class="shipment-badge">{{ $shipment->city }}</span>
                        </div>
                        <div class="shipment-details">
                            <div class="shipment-customer">
                                <i class="fas fa-user" style="margin-right: 5px; color: #667eea;"></i>
                                {{ $shipment->receiver_name }}
                            </div>
                            <div class="shipment-address">
                                <i class="fas fa-map-pin"></i>
                                {{ $shipment->full_address }}
                            </div>
                            <div class="shipment-meta">
                                <span><i class="fas fa-phone"></i> {{ $shipment->receiver_phone }}</span>
                                @if($shipment->weight)
                                <span><i class="fas fa-weight"></i> {{ $shipment->weight }} kg</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 3rem 1rem;">
                        <i class="fas fa-box-open" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                        <p style="color: #64748b;">No shipments found for the selected criteria</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Optimized Order Panel --}}
            <div class="order-panel">
                <div class="order-header">
                    <i class="fas fa-sort-amount-down"></i>
                    <h4>Optimized Delivery Order</h4>
                </div>
                <div class="order-list" id="optimizedOrder">
                    <div class="empty-order">
                        <i class="fas fa-route"></i>
                        <p>Select shipments and click "Optimize Route" to see the best order</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel - Map --}}
        <div class="map-card">
            <div class="map-header">
                <div class="map-title">
                    <i class="fas fa-map-marked-alt"></i>
                    <h5>Route Visualization</h5>
                </div>
                <div class="map-actions">
                    <button type="button" class="map-btn map-btn-primary" id="optimizeRouteBtn" disabled>
                        <i class="fas fa-route"></i> Optimize Route
                    </button>
                    <button type="button" class="map-btn map-btn-success" id="assignRouteBtn" disabled>
                        <i class="fas fa-check"></i> Assign to Agent
                    </button>
                    <button type="button" class="map-btn map-btn-secondary" onclick="resetMap()">
                        <i class="fas fa-redo-alt"></i> Reset
                    </button>
                </div>
            </div>
            <div class="map-container">
                <div id="routeMap"></div>
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
                    <button class="map-control-btn" onclick="toggleRouteInfo()" title="Toggle Route Info">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>
            <div class="route-info-card" id="routeInfo">
                <div class="route-info-grid">
                    <div class="route-info-item">
                        <div class="route-info-label">
                            <i class="fas fa-road"></i> Total Distance
                        </div>
                        <div class="route-info-value" id="totalDistance">0 <small>km</small></div>
                    </div>
                    <div class="route-info-item">
                        <div class="route-info-label">
                            <i class="fas fa-clock"></i> Est. Time
                        </div>
                        <div class="route-info-value" id="totalTime">0 <small>mins</small></div>
                    </div>
                    <div class="route-info-item">
                        <div class="route-info-label">
                            <i class="fas fa-map-pin"></i> Total Stops
                        </div>
                        <div class="route-info-value" id="totalStops">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Assign Modal --}}
<div class="modal" id="assignModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fas fa-user-tie"></i> Assign Route to Agent
            </h5>
            <button type="button" class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="assignForm">
                @csrf
                <div class="modal-form-group">
                    <label class="modal-form-label">Select Delivery Agent</label>
                    <select name="agent_id" class="modal-form-control" required id="agentSelect">
                        <option value="">Choose an agent...</option>
                        @foreach($agents as $agent)
                        <option value="{{ $agent->user_id }}">
                            {{ $agent->name }} ({{ $agent->vehicle_type ?? 'Bike' }})
                            @if($agent->city) - {{ $agent->city }} @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="shipment_ids" id="assignShipmentIds">
                <input type="hidden" name="route_order" id="assignRouteOrder">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-secondary" onclick="closeModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="modal-btn modal-btn-primary" id="confirmAssignBtn">
                <i class="fas fa-check"></i> Assign Route
            </button>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="toast"></div>

<!-- Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<!-- SortableJS for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // ==================== GLOBAL VARIABLES ====================
    let map = null;
    let routingControl = null;
    let warehouseMarker = null;
    let shipmentMarkers = [];
    let selectedShipments = [];
    let optimizedOrder = [];
    let currentRouteOrder = [];

    // Warehouse location
    const WAREHOUSE = {
        lat: {{ $warehouse['lat'] }},
        lng: {{ $warehouse['lng'] }}
    };

    // ==================== INITIALIZATION ====================
    document.addEventListener('DOMContentLoaded', function() {
        initializeMap();
        initializeSortable();
        setupEventListeners();
        updateSelectedShipments();
    });

    function initializeMap() {
        map = L.map('routeMap').setView([WAREHOUSE.lat, WAREHOUSE.lng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Add warehouse marker
        let warehouseIcon = L.divIcon({
            html: `<div style="
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 20px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                border: 2px solid white;
            "><i class="fas fa-warehouse"></i></div>`,
            className: 'custom-div-icon',
            iconSize: [40, 40],
            popupAnchor: [0, -20]
        });

        warehouseMarker = L.marker([WAREHOUSE.lat, WAREHOUSE.lng], { icon: warehouseIcon })
            .addTo(map)
            .bindPopup(`
                <div style="text-align: center;">
                    <b>Warehouse</b><br>
                    Start Point
                </div>
            `);

        // Add scale control
        L.control.scale({ imperial: false, metric: true }).addTo(map);
    }

    function initializeSortable() {
        // Make shipment list sortable for manual ordering
        new Sortable(document.getElementById('optimizedOrder'), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                updateRouteOrderFromManual();
            }
        });
    }

    function setupEventListeners() {
        // Select all button
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.shipment-checkbox');
            let allChecked = true;

            checkboxes.forEach(cb => {
                if (!cb.checked) allChecked = false;
            });

            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                if (cb.checked) {
                    cb.closest('.shipment-item').classList.add('selected');
                } else {
                    cb.closest('.shipment-item').classList.remove('selected');
                }
            });

            updateSelectedShipments();
            showToast(allChecked ? 'All shipments deselected' : 'All shipments selected', 'info');
        });

        // Individual checkbox change
        document.querySelectorAll('.shipment-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('.shipment-item').classList.add('selected');
                } else {
                    this.closest('.shipment-item').classList.remove('selected');
                }
                updateSelectedShipments();
            });
        });

        // Optimize route button
        document.getElementById('optimizeRouteBtn').addEventListener('click', optimizeRoute);

        // Assign route button
        document.getElementById('assignRouteBtn').addEventListener('click', openAssignModal);

        // Confirm assign button
        document.getElementById('confirmAssignBtn').addEventListener('click', assignRoute);
    }

    // ==================== SHIPMENT MANAGEMENT ====================

    function updateSelectedShipments() {
        selectedShipments = [];
        document.querySelectorAll('.shipment-checkbox:checked').forEach(cb => {
            let item = cb.closest('.shipment-item');
            selectedShipments.push({
                id: item.dataset.id,
                lat: parseFloat(item.dataset.lat),
                lng: parseFloat(item.dataset.lng),
                address: item.dataset.address,
                customer: item.dataset.customer,
                city: item.dataset.city,
                phone: item.dataset.phone
            });
        });

        // Enable/disable optimize button
        document.getElementById('optimizeRouteBtn').disabled = selectedShipments.length === 0;

        // Update markers on map
        updateShipmentMarkers();
    }

    function updateShipmentMarkers() {
        // Remove existing markers
        shipmentMarkers.forEach(marker => map.removeLayer(marker));
        shipmentMarkers = [];

        // Add new markers for selected shipments
        selectedShipments.forEach((shipment, index) => {
            let markerIcon = L.divIcon({
                html: `<div style="
                    width: 30px;
                    height: 30px;
                    background: #667eea;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: bold;
                    font-size: 14px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                    border: 2px solid white;
                ">${index + 1}</div>`,
                className: 'custom-div-icon',
                iconSize: [30, 30],
                popupAnchor: [0, -15]
            });

            let marker = L.marker([shipment.lat, shipment.lng], { icon: markerIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <b>${shipment.customer}</b><br>
                        ${shipment.address}<br>
                        <small>${shipment.city} | 📞 ${shipment.phone}</small>
                    </div>
                `);

            shipmentMarkers.push(marker);
        });
    }

    // ==================== ROUTE OPTIMIZATION ====================

    function optimizeRoute() {
        if (selectedShipments.length === 0) {
            showToast('Please select at least one shipment', 'error');
            return;
        }

        showLoading();

        let coordinates = [
            { lat: WAREHOUSE.lat, lng: WAREHOUSE.lng },
            ...selectedShipments
        ];

        fetch('{{ route("logistics.route.calculate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                shipment_ids: selectedShipments.map(s => s.id),
                start_lat: WAREHOUSE.lat,
                start_lng: WAREHOUSE.lng
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();

            if (data.success) {
                displayOptimizedRoute(data);
                showToast('Route optimized successfully!', 'success');
            } else {
                showToast('Error optimizing route', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Failed to optimize route', 'error');
        });
    }

    function displayOptimizedRoute(data) {
        // Remove existing route
        if (routingControl) {
            map.removeControl(routingControl);
        }

        // Create waypoints
        let waypoints = data.waypoints.map(w => L.latLng(w.lat, w.lng));

        // Add routing control
        routingControl = L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            showAlternatives: false,
            lineOptions: {
                styles: [{ color: '#667eea', weight: 6, opacity: 0.8 }]
            },
            createMarker: function() { return null; } // Don't create extra markers
        }).addTo(map);

        // Update optimized order list
        let orderList = document.getElementById('optimizedOrder');
        orderList.innerHTML = '';

        data.waypoints.slice(1).forEach((wp, index) => {
            let shipment = selectedShipments.find(s => s.lat === wp.lat && s.lng === wp.lng);
            if (shipment) {
                let item = document.createElement('div');
                item.className = 'order-item';
                item.dataset.id = shipment.id;
                item.innerHTML = `
                    <span class="order-number">${index + 1}</span>
                    <div class="order-content">
                        <div class="order-customer">${shipment.customer}</div>
                        <div class="order-address">${shipment.city}</div>
                    </div>
                `;
                orderList.appendChild(item);
                currentRouteOrder.push(shipment.id);
            }
        });

        // Update route info
        document.getElementById('routeInfo').style.display = 'block';
        document.getElementById('totalDistance').innerText = data.total_distance.toFixed(1);
        document.getElementById('totalTime').innerText = Math.round(data.total_duration);
        document.getElementById('totalStops').innerText = data.waypoints.length - 1;

        // Enable assign button
        document.getElementById('assignRouteBtn').disabled = false;

        // Store route order
        window.routeOrder = currentRouteOrder;
    }

    function updateRouteOrderFromManual() {
        let orderItems = document.querySelectorAll('#optimizedOrder .order-item');
        currentRouteOrder = [];

        orderItems.forEach((item, index) => {
            let orderNumber = item.querySelector('.order-number');
            orderNumber.textContent = index + 1;
            currentRouteOrder.push(item.dataset.id);
        });

        // Update markers order
        updateMarkerNumbers();

        // Recalculate route based on manual order
        recalculateRouteFromOrder();
    }

    function updateMarkerNumbers() {
        shipmentMarkers.forEach((marker, index) => {
            let newIcon = L.divIcon({
                html: `<div style="
                    width: 30px;
                    height: 30px;
                    background: #667eea;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: bold;
                    font-size: 14px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                    border: 2px solid white;
                ">${index + 1}</div>`,
                className: 'custom-div-icon',
                iconSize: [30, 30]
            });
            marker.setIcon(newIcon);
        });
    }

    function recalculateRouteFromOrder() {
        if (routingControl) {
            map.removeControl(routingControl);
        }

        let waypoints = [
            L.latLng(WAREHOUSE.lat, WAREHOUSE.lng),
            ...currentRouteOrder.map(id => {
                let shipment = selectedShipments.find(s => s.id == id);
                return L.latLng(shipment.lat, shipment.lng);
            })
        ];

        routingControl = L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            lineOptions: {
                styles: [{ color: '#667eea', weight: 6, opacity: 0.8 }]
            },
            createMarker: function() { return null; }
        }).addTo(map);
    }

    // ==================== ASSIGNMENT FUNCTIONS ====================

    function openAssignModal() {
        if (selectedShipments.length === 0) {
            showToast('No shipments selected', 'error');
            return;
        }

        document.getElementById('assignShipmentIds').value = JSON.stringify(selectedShipments.map(s => s.id));
        document.getElementById('assignRouteOrder').value = JSON.stringify(currentRouteOrder);
        document.getElementById('assignModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('assignModal').style.display = 'none';
    }

    function assignRoute() {
        let form = document.getElementById('assignForm');
        let formData = new FormData(form);

        showLoading();

        fetch('{{ route("logistics.route.assign") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            closeModal();

            if (data.success) {
                showToast('✅ Route assigned successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('❌ Error assigning route', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('❌ Failed to assign route', 'error');
        });
    }

    // ==================== MAP UTILITIES ====================

    function centerMap() {
        map.setView([WAREHOUSE.lat, WAREHOUSE.lng], 12);
    }

    function zoomIn() {
        map.zoomIn();
    }

    function zoomOut() {
        map.zoomOut();
    }

    function resetMap() {
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }

        // Clear selections
        document.querySelectorAll('.shipment-checkbox:checked').forEach(cb => {
            cb.checked = false;
            cb.closest('.shipment-item').classList.remove('selected');
        });

        document.getElementById('optimizedOrder').innerHTML = `
            <div class="empty-order">
                <i class="fas fa-route"></i>
                <p>Select shipments and click "Optimize Route" to see the best order</p>
            </div>
        `;

        document.getElementById('routeInfo').style.display = 'none';
        document.getElementById('assignRouteBtn').disabled = true;
        document.getElementById('optimizeRouteBtn').disabled = true;

        updateSelectedShipments();
        showToast('Map reset', 'info');
    }

    function toggleRouteInfo() {
        let info = document.getElementById('routeInfo');
        info.style.display = info.style.display === 'none' ? 'block' : 'none';
    }

    // ==================== UTILITY FUNCTIONS ====================

    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

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

    // Close modal on outside click
    window.onclick = function(event) {
        let modal = document.getElementById('assignModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection
