@extends('layouts.app')

@section('page-title', 'Shipment #' . $shipment->shipment_number)

@section('content')
<style>
    /* ================= PROFESSIONAL SHIPMENT DETAILS STYLES ================= */
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
    .shipment-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        padding: clamp(16px, 3vw, 30px);
        width: 100%;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* ================= MAIN CARD ================= */
    .shipment-card {
        background: #ffffff;
        border-radius: 30px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        animation: slideIn 0.5s ease;
    }

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

    /* ================= HEADER ================= */
    .shipment-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: clamp(1.5rem, 4vw, 2rem);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .shipment-header::before {
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
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
        z-index: 1;
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
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .header-subtitle {
        opacity: 0.9;
        font-size: clamp(0.9rem, 2.5vw, 1rem);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-right {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: clamp(0.85rem, 2vw, 0.9rem);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-badge.picked {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .status-badge.in_transit {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: white;
    }

    .status-badge.out_for_delivery {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-badge.delivered {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-badge.failed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .status-badge.returned {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .header-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        font-size: clamp(0.8rem, 2vw, 0.85rem);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        backdrop-filter: blur(5px);
    }

    .header-btn:hover {
        background: white;
        color: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* ================= PROGRESS BAR ================= */
    .progress-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #e5e7eb;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
        min-width: 80px;
    }

    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 15px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #e5e7eb;
        z-index: 1;
    }

    .step.completed:not(:last-child)::after {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .step-icon {
        width: 32px;
        height: 32px;
        background: #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        position: relative;
        z-index: 2;
        font-size: 16px;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .step.completed .step-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .step.active .step-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        animation: pulse 2s infinite;
    }

    .step-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        word-break: break-word;
    }

    .step.completed .step-label {
        color: #10b981;
    }

    .step.active .step-label {
        color: #667eea;
        font-weight: 700;
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
        background: linear-gradient(135deg, #667eea, #764ba2);
        width: 0%;
        transition: width 0.3s ease;
    }

    /* ================= INFO GRID ================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        padding: clamp(1.5rem, 4vw, 2rem);
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
        border-color: #667eea;
    }

    .info-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 5px 10px rgba(102, 126, 234, 0.2);
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.25rem 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
        word-break: break-word;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
        text-align: right;
        word-break: break-word;
    }

    .info-value.highlight {
        color: #667eea;
        font-size: 1.1rem;
    }

    .address-text {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #334155;
        word-break: break-word;
    }

    /* ================= CHARGES CARD ================= */
    .charges-card {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: white;
    }

    .charges-card .info-title {
        color: white;
        border-bottom-color: rgba(255, 255, 255, 0.2);
    }

    .charges-card .info-icon {
        background: rgba(255, 255, 255, 0.2);
    }

    .charges-card .info-label {
        color: rgba(255, 255, 255, 0.7);
    }

    .charges-card .info-value {
        color: white;
    }

    .total-charge {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* ================= TRACKING HISTORY ================= */
    .tracking-section {
        padding: clamp(1.5rem, 4vw, 2rem);
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #667eea;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        opacity: 0.3;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        z-index: 2;
    }

    .timeline-item.completed .timeline-dot {
        background: #10b981;
    }

    .timeline-item.current .timeline-dot {
        background: #667eea;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
        }
    }

    .timeline-content {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .timeline-status {
        font-weight: 700;
        color: #1e293b;
        text-transform: uppercase;
        font-size: 0.9rem;
        background: linear-gradient(135deg, #667eea10, #764ba210);
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
    }

    .timeline-time {
        color: #64748b;
        font-size: 0.85rem;
    }

    .timeline-location {
        color: #667eea;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .timeline-remarks {
        color: #64748b;
        font-size: 0.85rem;
        font-style: italic;
        background: #f1f5f9;
        padding: 0.5rem;
        border-radius: 8px;
    }

    /* ================= ACTION BUTTONS ================= */
    .actions-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: white;
        text-align: center;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
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

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .btn i {
        font-size: 1rem;
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: scale(1.2);
    }

    /* ================= STATUS UPDATE FORM ================= */
    .status-update-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }

    .status-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .status-form-group {
        flex: 1;
        min-width: 150px;
    }

    .status-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .status-select,
    .status-input,
    .status-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        background: white;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .status-select:focus,
    .status-input:focus,
    .status-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .status-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .status-submit {
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .status-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    /* ================= POD SECTION ================= */
    .pod-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: white;
        border-top: 1px solid #e5e7eb;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .pod-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .pod-card:hover {
        border-color: #667eea;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
    }

    .pod-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pod-title i {
        color: #667eea;
    }

    .pod-image {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .pod-image:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
    }

    .pod-notes {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #475569;
        border: 1px solid #e5e7eb;
        margin-top: 0.5rem;
        font-style: italic;
    }

    .pod-upload-form {
        margin-top: 1rem;
    }

    .file-input-group {
        margin-bottom: 1rem;
    }

    .file-label {
        display: block;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .file-input {
        width: 100%;
        padding: 0.5rem;
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-input:hover {
        border-color: #667eea;
        background: #f8fafc;
    }

    .file-preview {
        margin-top: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .file-preview-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        position: relative;
    }

    .file-preview-item img {
        width: 40px;
        height: 40px;
        border-radius: 4px;
        object-fit: cover;
    }

    .file-preview-remove {
        color: #ef4444;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0 0.25rem;
    }

    .file-preview-remove:hover {
        color: #dc2626;
        transform: scale(1.2);
    }

    .upload-progress {
        margin: 1rem 0;
        display: none;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        width: 0%;
        transition: width 0.3s ease;
    }

    .progress-text {
        display: flex;
        justify-content: space-between;
        margin-top: 0.25rem;
        font-size: 0.8rem;
        color: #64748b;
    }

    /* ================= RELATED SHIPMENTS ================= */
    .related-section {
        padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .related-card {
        background: white;
        padding: 1rem;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        display: block;
    }

    .related-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        border-color: #667eea;
    }

    .related-number {
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .related-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .related-status.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .related-status.delivered {
        background: #d1fae5;
        color: #065f46;
    }

    .related-status.in_transit {
        background: #dbeafe;
        color: #1e40af;
    }

    .related-address {
        font-size: 0.85rem;
        color: #64748b;
        word-break: break-word;
    }

    /* ================= TOAST ================= */
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
        .shipment-page {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .status-form {
            flex-direction: column;
            align-items: stretch;
        }

        .status-form-group {
            width: 100%;
        }

        .status-submit {
            width: 100%;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .progress-steps {
            flex-direction: column;
            align-items: flex-start;
        }

        .step {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .step:not(:last-child)::after {
            display: none;
        }

        .step-icon {
            margin: 0;
        }

        .pod-section {
            grid-template-columns: 1fr;
        }

        .toast {
            left: 15px;
            right: 15px;
            width: calc(100% - 30px);
        }
    }

    /* ================= PRINT STYLES ================= */
    @media print {
        .header-actions,
        .actions-section,
        .status-update-section,
        .pod-upload-form,
        .btn {
            display: none !important;
        }

        .shipment-card {
            box-shadow: none;
            border: 1px solid #000;
        }

        .status-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="shipment-page">
    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Processing...</div>
    </div>

    {{-- Header --}}
    <div class="shipment-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="header-title">
                    Shipment Details
                    <span class="status-badge {{ $shipment->status }}">
                        {{ strtoupper(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </h1>
                <div class="header-subtitle">
                    <span><i class="fas fa-hashtag"></i> {{ $shipment->shipment_number }}</span>
                    @if($shipment->tracking_number)
                    <span>•</span>
                    <span><i class="fas fa-barcode"></i> {{ $shipment->tracking_number }}</span>
                    @endif
                </div>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="header-btn" onclick="copyShipmentNo()" title="Copy Shipment Number">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                    <a href="{{ route('logistics.track', $shipment->tracking_number ?? $shipment->shipment_number) }}"
                       class="header-btn" target="_blank" title="Track Shipment">
                        <i class="fas fa-map-marked-alt"></i> Track
                    </a>
                    @if($shipment->status != 'delivered')
                    <button class="header-btn" onclick="quickDeliver()" title="Mark as Delivered">
                        <i class="fas fa-check-circle"></i> Deliver
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="progress-section">
        @php
            $statusOrder = ['pending' => 0, 'picked' => 20, 'in_transit' => 40, 'out_for_delivery' => 70, 'delivered' => 100];
            $progress = $statusOrder[$shipment->status] ?? 0;
        @endphp
        <div class="progress-steps">
            <div class="step {{ in_array($shipment->status, ['picked', 'in_transit', 'out_for_delivery', 'delivered']) ? 'completed' : '' }} {{ $shipment->status == 'pending' ? 'active' : '' }}">
                <div class="step-icon">📦</div>
                <div class="step-label">Pending</div>
            </div>
            <div class="step {{ in_array($shipment->status, ['in_transit', 'out_for_delivery', 'delivered']) ? 'completed' : '' }} {{ $shipment->status == 'picked' ? 'active' : '' }}">
                <div class="step-icon">📌</div>
                <div class="step-label">Picked Up</div>
            </div>
            <div class="step {{ in_array($shipment->status, ['out_for_delivery', 'delivered']) ? 'completed' : '' }} {{ $shipment->status == 'in_transit' ? 'active' : '' }}">
                <div class="step-icon">🚚</div>
                <div class="step-label">In Transit</div>
            </div>
            <div class="step {{ $shipment->status == 'delivered' ? 'completed' : '' }} {{ $shipment->status == 'out_for_delivery' ? 'active' : '' }}">
                <div class="step-icon">🚀</div>
                <div class="step-label">Out for Delivery</div>
            </div>
            <div class="step {{ $shipment->status == 'delivered' ? 'completed active' : '' }}">
                <div class="step-icon">✅</div>
                <div class="step-label">Delivered</div>
            </div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        {{-- Receiver Info --}}
        <div class="info-card">
            <div class="info-title">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <span>Receiver Details</span>
            </div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $shipment->receiver_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $shipment->receiver_phone }}</span>
                </div>
                @if($shipment->receiver_alternate_phone)
                <div class="info-row">
                    <span class="info-label">Alt. Phone:</span>
                    <span class="info-value">{{ $shipment->receiver_alternate_phone }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Address Info --}}
        <div class="info-card">
            <div class="info-title">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <span>Delivery Address</span>
            </div>
            <div class="address-text">
                <i class="fas fa-map-pin" style="color: #667eea; margin-right: 0.5rem;"></i>
                {{ $shipment->full_address }}
            </div>
        </div>

        {{-- Package Info --}}
        <div class="info-card">
            <div class="info-title">
                <div class="info-icon"><i class="fas fa-box"></i></div>
                <span>Package Details</span>
            </div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Weight:</span>
                    <span class="info-value">{{ $shipment->weight ?? 'N/A' }} kg</span>
                </div>
                @if($shipment->length && $shipment->width && $shipment->height)
                <div class="info-row">
                    <span class="info-label">Dimensions:</span>
                    <span class="info-value">{{ $shipment->length }} x {{ $shipment->width }} x {{ $shipment->height }} cm</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Quantity:</span>
                    <span class="info-value">{{ $shipment->quantity }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Package Type:</span>
                    <span class="info-value">{{ ucfirst($shipment->package_type ?? 'box') }}</span>
                </div>
            </div>
        </div>

        {{-- Courier Info --}}
        <div class="info-card">
            <div class="info-title">
                <div class="info-icon"><i class="fas fa-truck"></i></div>
                <span>Courier Details</span>
            </div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Courier Partner:</span>
                    <span class="info-value">{{ $shipment->courier_partner ?? 'Not Assigned' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tracking Number:</span>
                    <span class="info-value">{{ $shipment->tracking_number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">AWB Number:</span>
                    <span class="info-value">{{ $shipment->awb_number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Shipping Method:</span>
                    <span class="info-value">{{ ucfirst($shipment->shipping_method ?? 'standard') }}</span>
                </div>
            </div>
        </div>

        {{-- Dates Info --}}
        <div class="info-card">
            <div class="info-title">
                <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                <span>Delivery Dates</span>
            </div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Pickup Date:</span>
                    <span class="info-value">{{ $shipment->pickup_date ? $shipment->pickup_date->format('d M Y') : 'Not picked' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estimated Delivery:</span>
                    <span class="info-value">{{ $shipment->estimated_delivery_date ? $shipment->estimated_delivery_date->format('d M Y') : 'Not set' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Actual Delivery:</span>
                    <span class="info-value">{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('d M Y') : 'Not delivered' }}</span>
                </div>
            </div>
        </div>

        {{-- Charges Info --}}
        <div class="info-card charges-card">
            <div class="info-title">
                <div class="info-icon"><i class="fas fa-coins"></i></div>
                <span>Shipping Charges</span>
            </div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Shipping Charge:</span>
                    <span class="info-value">₹{{ number_format($shipment->shipping_charge, 2) }}</span>
                </div>
                @if($shipment->cod_charge > 0)
                <div class="info-row">
                    <span class="info-label">COD Charge:</span>
                    <span class="info-value">₹{{ number_format($shipment->cod_charge, 2) }}</span>
                </div>
                @endif
                @if($shipment->insurance_charge > 0)
                <div class="info-row">
                    <span class="info-label">Insurance:</span>
                    <span class="info-value">₹{{ number_format($shipment->insurance_charge, 2) }}</span>
                </div>
                @endif
                <div class="total-charge">
                    <div class="info-row">
                        <span class="info-label">Total Charge:</span>
                        <span class="info-value">₹{{ number_format($shipment->total_charge, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tracking History --}}
    <div class="tracking-section">
        <h3 class="section-title">
            <i class="fas fa-history"></i>
            Tracking History
        </h3>

        <div class="timeline">
            @forelse($shipment->trackings->sortByDesc('tracked_at') as $index => $track)
            <div class="timeline-item {{ $index === 0 ? 'current' : 'completed' }}">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <span class="timeline-status">{{ strtoupper(str_replace('_', ' ', $track->status)) }}</span>
                        <span class="timeline-time">{{ $track->tracked_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @if($track->location)
                    <div class="timeline-location">
                        <i class="fas fa-map-pin"></i>
                        <span>{{ $track->location }}</span>
                    </div>
                    @endif
                    @if($track->remarks)
                    <div class="timeline-remarks">
                        "{{ $track->remarks }}"
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem; color: #64748b;">
                <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p>No tracking history available</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="actions-section">
        <div class="action-buttons">
            <a href="{{ route('logistics.shipments.edit', $shipment->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Shipment
            </a>
            @if(!$shipment->courier_partner)
            <a href="{{ route('logistics.shipments.edit', $shipment->id) }}" class="btn btn-warning">
                <i class="fas fa-truck"></i> Assign Courier
            </a>
            @endif
            @if($shipment->status != 'delivered')
            <button onclick="showStatusUpdate()" class="btn btn-success">
                <i class="fas fa-sync-alt"></i> Update Status
            </button>
            @endif
            <a href="{{ route('logistics.shipments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    {{-- Status Update Form --}}
    @if($shipment->status != 'delivered')
    <div id="statusUpdateSection" class="status-update-section" style="display: none;">
        <h3 class="section-title" style="margin-bottom: 1rem;">
            <i class="fas fa-truck"></i> Update Shipment Status
        </h3>
        <form id="statusForm" class="status-form">
            @csrf
            <div class="status-form-group">
                <label class="status-label">Status</label>
                <select name="status" class="status-select" required>
                    <option value="">Select Status</option>
                    <option value="picked" {{ $shipment->status == 'picked' ? 'selected' : '' }}>📦 Picked Up</option>
                    <option value="in_transit" {{ $shipment->status == 'in_transit' ? 'selected' : '' }}>🚚 In Transit</option>
                    <option value="out_for_delivery" {{ $shipment->status == 'out_for_delivery' ? 'selected' : '' }}>🚀 Out for Delivery</option>
                    <option value="delivered">✅ Delivered</option>
                    <option value="failed">❌ Failed</option>
                    <option value="returned">🔄 Returned</option>
                </select>
            </div>
            <div class="status-form-group">
                <label class="status-label">Location</label>
                <input type="text" name="location" class="status-input" placeholder="Current location">
            </div>
            <div style="flex: 2; min-width: 250px;">
                <label class="status-label">Remarks</label>
                <textarea name="remarks" class="status-textarea" placeholder="Additional notes..."></textarea>
            </div>
            <button type="submit" class="status-submit">
                <i class="fas fa-check"></i> Update Status
            </button>
        </form>
    </div>
    @endif

    {{-- Proof of Delivery Section --}}
    @if($shipment->status == 'delivered' || $shipment->pod_signature || $shipment->pod_photo)
    <div class="pod-section">
        @if($shipment->pod_signature)
        <div class="pod-card">
            <div class="pod-title">
                <i class="fas fa-signature"></i> Signature
            </div>
            <img src="{{ Storage::url($shipment->pod_signature) }}" alt="Signature" class="pod-image" onclick="window.open(this.src)">
        </div>
        @endif

        @if($shipment->pod_photo)
        <div class="pod-card">
            <div class="pod-title">
                <i class="fas fa-camera"></i> Delivery Photo
            </div>
            <img src="{{ Storage::url($shipment->pod_photo) }}" alt="Delivery Photo" class="pod-image" onclick="window.open(this.src)">
        </div>
        @endif

        @if($shipment->delivery_notes)
        <div class="pod-card">
            <div class="pod-title">
                <i class="fas fa-sticky-note"></i> Delivery Notes
            </div>
            <div class="pod-notes">
                {{ $shipment->delivery_notes }}
            </div>
        </div>
        @endif

        {{-- Upload POD Form - Only show if delivered and missing files --}}
        @if($shipment->status == 'delivered' && (!$shipment->pod_signature || !$shipment->pod_photo))
        <div class="pod-card">
            <div class="pod-title">
                <i class="fas fa-cloud-upload-alt"></i> Upload Proof of Delivery
            </div>

            {{-- Upload Progress --}}
            <div class="upload-progress" id="uploadProgress">
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" id="progressBar"></div>
                </div>
                <div class="progress-text">
                    <span id="uploadStatus">Uploading...</span>
                    <span id="uploadPercent">0%</span>
                </div>
            </div>

            <form class="pod-upload-form" id="podForm" enctype="multipart/form-data">
                @csrf

                @if(!$shipment->pod_signature)
                <div class="file-input-group">
                    <label class="file-label">
                        <i class="fas fa-signature"></i> Signature Image
                    </label>
                    <input type="file" name="signature" id="signatureInput" class="file-input" accept="image/*" onchange="previewFile(this, 'signaturePreview')">
                    <div id="signaturePreview" class="file-preview"></div>
                    <small style="color: #64748b;">Max 5MB (JPG, PNG, GIF)</small>
                </div>
                @endif

                @if(!$shipment->pod_photo)
                <div class="file-input-group">
                    <label class="file-label">
                        <i class="fas fa-camera"></i> Delivery Photo
                    </label>
                    <input type="file" name="photo" id="photoInput" class="file-input" accept="image/*" onchange="previewFile(this, 'photoPreview')">
                    <div id="photoPreview" class="file-preview"></div>
                    <small style="color: #64748b;">Max 10MB (JPG, PNG, GIF)</small>
                </div>
                @endif

                <div class="file-input-group">
                    <label class="file-label">
                        <i class="fas fa-sticky-note"></i> Delivery Notes
                    </label>
                    <textarea name="delivery_notes" class="status-textarea" placeholder="Any notes about the delivery..." rows="3">{{ old('delivery_notes') }}</textarea>
                </div>

                <button type="submit" class="pod-upload-btn" id="podSubmitBtn">
                    <i class="fas fa-cloud-upload-alt"></i> Upload POD
                </button>
            </form>
        </div>
        @endif
    </div>
    @endif

    {{-- Related Shipments --}}
    @if(isset($relatedShipments) && $relatedShipments->count() > 0)
    <div class="related-section">
        <h3 class="section-title">
            <i class="fas fa-link"></i> Related Shipments
        </h3>
        <div class="related-grid">
            @foreach($relatedShipments as $related)
            <a href="{{ route('logistics.shipments.show', $related->id) }}" class="related-card">
                <div class="related-number">
                    <i class="fas fa-box"></i> #{{ $related->shipment_number }}
                </div>
                <span class="related-status {{ $related->status }}">
                    {{ ucfirst(str_replace('_', ' ', $related->status)) }}
                </span>
                <div class="related-address">
                    <i class="fas fa-map-pin"></i> {{ $related->city }}, {{ $related->state }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Toast --}}
<div id="toast" class="toast"></div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    let currentShipmentId = {{ $shipment->id }};

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

    function copyShipmentNo() {
        navigator.clipboard.writeText('{{ $shipment->shipment_number }}')
            .then(() => showToast('✅ Shipment number copied!', 'success'))
            .catch(() => showToast('❌ Failed to copy', 'error'));
    }

    function showStatusUpdate() {
        document.getElementById('statusUpdateSection').style.display = 'block';
        document.getElementById('statusUpdateSection').scrollIntoView({ behavior: 'smooth' });
    }

    // Status Update
    document.getElementById('statusForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!confirm('Update shipment status?')) return;

        showLoading();

        const formData = new FormData(this);

        fetch('{{ route("logistics.shipments.status", $shipment->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('✅ ' + data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('❌ ' + data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('❌ Error updating status', 'error');
            console.error('Error:', error);
        });
    });

    // Quick Deliver
    function quickDeliver() {
        if (!confirm('Mark this shipment as delivered?')) return;

        showLoading();

        fetch('{{ route("logistics.shipments.status", $shipment->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: 'delivered',
                location: 'Delivered',
                remarks: 'Marked delivered from shipment page'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('✅ Shipment marked as delivered!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('❌ ' + data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('❌ Error', 'error');
            console.error('Error:', error);
        });
    }

    // File Preview
    function previewFile(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Check file size
            const maxSize = input.id === 'signatureInput' ? 5 * 1024 * 1024 : 10 * 1024 * 1024;
            if (file.size > maxSize) {
                showToast(`File size must be less than ${maxSize / (1024 * 1024)}MB`, 'error');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const fileSize = (file.size / 1024).toFixed(1);
                preview.innerHTML = `
                    <div class="file-preview-item">
                        <img src="${e.target.result}" alt="Preview">
                        <span>${file.name} (${fileSize} KB)</span>
                        <span class="file-preview-remove" onclick="removeFile('${input.id}', '${previewId}')">×</span>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    }

    function removeFile(inputId, previewId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).innerHTML = '';
        showToast('File removed', 'info');
    }

    // POD Upload with AJAX
    document.getElementById('podForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if any file is selected
        const signatureFile = document.getElementById('signatureInput')?.files[0];
        const photoFile = document.getElementById('photoInput')?.files[0];

        if (!signatureFile && !photoFile) {
            showToast('Please select at least one file to upload', 'error');
            return;
        }

        // Validate file sizes
        if (signatureFile && signatureFile.size > 5 * 1024 * 1024) {
            showToast('Signature image must be less than 5MB', 'error');
            return;
        }

        if (photoFile && photoFile.size > 10 * 1024 * 1024) {
            showToast('Photo must be less than 10MB', 'error');
            return;
        }

        showLoading();

        // Show progress bar
        document.getElementById('uploadProgress').style.display = 'block';
        document.getElementById('progressBar').style.width = '0%';
        document.getElementById('uploadPercent').textContent = '0%';
        document.getElementById('uploadStatus').textContent = 'Uploading...';

        const formData = new FormData(this);

        // Simulate progress (since fetch doesn't have progress events)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 10;
            if (progress <= 90) {
                document.getElementById('progressBar').style.width = progress + '%';
                document.getElementById('uploadPercent').textContent = progress + '%';
            }
        }, 300);

        fetch('{{ route("logistics.shipments.upload-pod", $shipment->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            clearInterval(progressInterval);
            document.getElementById('progressBar').style.width = '100%';
            document.getElementById('uploadPercent').textContent = '100%';
            document.getElementById('uploadStatus').textContent = 'Processing...';
            return response.json();
        })
        .then(data => {
            hideLoading();
            setTimeout(() => {
                document.getElementById('uploadProgress').style.display = 'none';
            }, 1000);

            if (data.success) {
                showToast('✅ ' + data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('❌ ' + (data.message || 'Error uploading POD'), 'error');
                document.getElementById('progressBar').style.width = '0%';
                document.getElementById('uploadPercent').textContent = '0%';
                document.getElementById('uploadStatus').textContent = 'Upload failed';
            }
        })
        .catch(error => {
            clearInterval(progressInterval);
            hideLoading();
            document.getElementById('uploadProgress').style.display = 'none';
            showToast('❌ Network error: ' + error.message, 'error');
            console.error('Error:', error);
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });

    // Auto-hide status update section after 30 seconds
    setTimeout(() => {
        const statusSection = document.getElementById('statusUpdateSection');
        if (statusSection) {
            statusSection.style.display = 'none';
        }
    }, 30000);
</script>
@endsection
