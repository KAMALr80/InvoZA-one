@extends('layouts.app')

@section('page-title', 'Edit Agent - ' . $agent->name)

@section('content')
    <style>
        /* ================= PROFESSIONAL ATTRACTIVE EDIT AGENT STYLES ================= */
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
        .edit-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: clamp(16px, 3vw, 30px);
            width: 100%;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* ================= MAIN CARD ================= */
        .edit-card {
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
        .edit-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: clamp(1.5rem, 4vw, 2rem);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .edit-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
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
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
            min-width: 280px;
        }

        .header-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 2.5rem;
            flex-shrink: 0;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
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

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .status-badge.available {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-badge.busy {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .status-badge.offline {
            background: linear-gradient(135deg, #6b7280, #4b5563);
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
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-size: clamp(0.9rem, 2vw, 1rem);
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* ================= FORM SECTIONS ================= */
        .form-section {
            padding: clamp(1.5rem, 4vw, 2rem);
            border-bottom: 1px solid #e5e7eb;
            background: white;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-section:hover {
            background: #f8fafc;
        }

        .form-section::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 2rem;
            right: 2rem;
            height: 2px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .form-section:hover::after {
            opacity: 1;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .form-section:hover .section-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
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
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
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

        .form-control[readonly] {
            background: #f1f5f9;
            color: #475569;
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

        /* ================= CHECKBOX STYLES ================= */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .checkbox-group:hover {
            border-color: #667eea;
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .checkbox-input {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-label {
            font-weight: 500;
            color: #1e293b;
            cursor: pointer;
            font-size: 0.95rem;
        }

        /* ================= FILE UPLOAD ================= */
        .file-upload {
            border: 2px dashed #e5e7eb;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .file-upload:hover {
            border-color: #667eea;
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
        }

        .file-upload input {
            display: none;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #667eea;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .file-upload-text {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .file-upload-hint {
            font-size: 0.85rem;
            color: #64748b;
        }

        /* ================= FILE PREVIEW ================= */
        .file-preview {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .file-preview-item {
            background: linear-gradient(135deg, #667eea10, #764ba210);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            border: 1px solid #667eea30;
            color: #1e293b;
        }

        .file-preview-remove {
            color: #ef4444;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 0 0.25rem;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .file-preview-remove:hover {
            background: #fee2e2;
            color: #dc2626;
            transform: scale(1.2);
        }

        /* ================= CURRENT DOCUMENTS ================= */
        .current-doc {
            margin-top: 0.5rem;
            padding: 0.75rem 1rem;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.95rem;
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

        .current-doc:hover {
            border-color: #667eea;
            background: white;
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .current-doc a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .current-doc a:hover {
            text-decoration: underline;
        }

        .delete-doc {
            color: #ef4444;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .delete-doc:hover {
            background: #fee2e2;
            transform: scale(1.1) rotate(90deg);
        }

        /* ================= SERVICE AREAS GRID ================= */
        .service-areas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .service-area-item {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .service-area-item:hover {
            border-color: #667eea;
            background: white;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .service-area-item .checkbox-input {
            width: 18px;
            height: 18px;
        }

        .service-area-item .checkbox-label {
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
        }

        /* ================= ACTION BUTTONS ================= */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            flex-wrap: wrap;
            padding: 1.5rem clamp(1.5rem, 4vw, 2rem);
            background: #f8fafc;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(239, 68, 68, 0.4);
        }

        .btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: scale(1.2);
        }

        /* ================= ALERTS ================= */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        .alert-error {
            background: #fee2e2;
            border-left-color: #ef4444;
            color: #991b1b;
        }

        .alert-success {
            background: #d1fae5;
            border-left-color: #10b981;
            color: #065f46;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .alert i {
            font-size: 1.2rem;
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
            z-index: 9999;
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
            to {
                transform: rotate(360deg);
            }
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
            .header-left {
                flex-direction: column;
                text-align: center;
            }

            .header-title {
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .service-areas-grid {
                grid-template-columns: 1fr;
            }

            .toast {
                left: 15px;
                right: 15px;
                width: calc(100% - 30px);
            }
        }

        @media print {

            .header-actions,
            .action-buttons,
            .file-upload,
            .btn {
                display: none !important;
            }
        }
    </style>

    <div class="edit-page">
        <div class="container">
            <div class="edit-card">
                {{-- Loading Overlay --}}
                <div id="loadingOverlay" class="loading-overlay">
                    <div class="spinner"></div>
                    <div class="loading-text">Updating agent data...</div>
                </div>

                {{-- Header --}}
                <div class="edit-header">
                    <div class="header-content">
                        <div class="header-left">
                            <div class="header-avatar">
                                {{ substr($agent->name, 0, 1) }}
                            </div>
                            <div class="header-info">
                                <div class="header-title">
                                    {{ $agent->name }}
                                    <span class="status-badge {{ $agent->status }}">
                                        {{ ucfirst($agent->status) }}
                                    </span>
                                </div>
                                <div class="header-subtitle">
                                    <span><i class="fas fa-id-card"></i> {{ $agent->agent_code }}</span>
                                    <span>•</span>
                                    <span><i class="fas fa-phone"></i> {{ $agent->phone }}</span>
                                    @if ($agent->email)
                                        <span>•</span>
                                        <span><i class="fas fa-envelope"></i> {{ $agent->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('logistics.agents.show', $agent->id) }}" class="header-btn">
                                <i class="fas fa-arrow-left"></i> Back to Profile
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="form-section" style="padding-bottom: 0;">
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="form-section" style="padding-bottom: 0;">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                {{-- Last Updated Info --}}
                <div class="form-section" style="padding-bottom: 0;">
                    <div
                        style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; background: linear-gradient(135deg, #f8fafc, #e2e8f0); padding: 1rem 1.5rem; border-radius: 16px; border: 1px solid #e5e7eb;">
                        <span style="font-weight: 600; color: #475569;"><i class="fas fa-calendar-alt"></i> Last
                            Updated:</span>
                        <span style="color: #64748b;">{{ $agent->updated_at->format('d M Y, h:i A') }}</span>
                        <span style="font-weight: 600; color: #475569; margin-left: auto;"><i class="fas fa-clock"></i>
                            {{ $agent->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('logistics.agents.update', $agent->id) }}"
                    enctype="multipart/form-data" id="agentForm">
                    @csrf
                    @method('PUT')

                    {{-- Personal Information --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-user"></i></div>
                            <div>
                                <h3 class="section-title">Personal Information</h3>
                                <p class="section-subtitle">Update agent's personal details</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Full Name <span class="required-star">*</span>
                                </label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $agent->name) }}" required placeholder="Enter full name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Phone Number <span class="required-star">*</span>
                                </label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $agent->phone) }}" required
                                    placeholder="10 digit mobile number">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $agent->email) }}" placeholder="email@example.com">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone-alt"></i>
                                    Alternate Phone
                                </label>
                                <input type="text" name="alternate_phone" class="form-control"
                                    value="{{ old('alternate_phone', $agent->alternate_phone) }}"
                                    placeholder="Alternate contact number">
                            </div>
                        </div>
                    </div>

                    {{-- Address Information --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <h3 class="section-title">Address Information</h3>
                                <p class="section-subtitle">Update agent's address details</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group" style="grid-column: 1/-1;">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Address
                                </label>
                                <textarea name="address" class="form-control" placeholder="Complete address">{{ old('address', $agent->address) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-city"></i>
                                    City
                                </label>
                                <input type="text" name="city" class="form-control"
                                    value="{{ old('city', $agent->city) }}" placeholder="City">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map"></i>
                                    State
                                </label>
                                <input type="text" name="state" class="form-control"
                                    value="{{ old('state', $agent->state) }}" placeholder="State">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-mail-bulk"></i>
                                    Pincode
                                </label>
                                <input type="text" name="pincode" class="form-control"
                                    value="{{ old('pincode', $agent->pincode) }}" placeholder="Pincode">
                            </div>
                        </div>
                    </div>

                    {{-- Vehicle Information --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-truck"></i></div>
                            <div>
                                <h3 class="section-title">Vehicle Information</h3>
                                <p class="section-subtitle">Update vehicle and license details</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-motorcycle"></i>
                                    Vehicle Type
                                </label>
                                <select name="vehicle_type" class="form-control">
                                    <option value="">Select Vehicle</option>
                                    <option value="bike"
                                        {{ old('vehicle_type', $agent->vehicle_type) == 'bike' ? 'selected' : '' }}>🏍️
                                        Bike</option>
                                    <option value="cycle"
                                        {{ old('vehicle_type', $agent->vehicle_type) == 'cycle' ? 'selected' : '' }}>🚲
                                        Cycle</option>
                                    <option value="van"
                                        {{ old('vehicle_type', $agent->vehicle_type) == 'van' ? 'selected' : '' }}>🚐 Van
                                    </option>
                                    <option value="truck"
                                        {{ old('vehicle_type', $agent->vehicle_type) == 'truck' ? 'selected' : '' }}>🚛
                                        Truck</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i>
                                    Vehicle Number
                                </label>
                                <input type="text" name="vehicle_number" class="form-control"
                                    value="{{ old('vehicle_number', $agent->vehicle_number) }}"
                                    placeholder="e.g., GJ01AB1234">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    License Number
                                </label>
                                <input type="text" name="license_number" class="form-control"
                                    value="{{ old('license_number', $agent->license_number) }}"
                                    placeholder="Driving license number">
                            </div>
                        </div>
                    </div>

                    {{-- Employment Details --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-briefcase"></i></div>
                            <div>
                                <h3 class="section-title">Employment Details</h3>
                                <p class="section-subtitle">Update employment and compensation</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-briefcase"></i>
                                    Employment Type
                                </label>
                                <select name="employment_type" class="form-control">
                                    <option value="full_time"
                                        {{ old('employment_type', $agent->employment_type) == 'full_time' ? 'selected' : '' }}>
                                        Full Time</option>
                                    <option value="part_time"
                                        {{ old('employment_type', $agent->employment_type) == 'part_time' ? 'selected' : '' }}>
                                        Part Time</option>
                                    <option value="contract"
                                        {{ old('employment_type', $agent->employment_type) == 'contract' ? 'selected' : '' }}>
                                        Contract</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Joining Date
                                </label>
                                <input type="date" name="joining_date" class="form-control"
                                    value="{{ old('joining_date', $agent->joining_date ? $agent->joining_date->format('Y-m-d') : '') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-rupee-sign"></i>
                                    Salary (₹)
                                </label>
                                <input type="number" name="salary" class="form-control"
                                    value="{{ old('salary', $agent->salary) }}" step="0.01" min="0"
                                    placeholder="0.00">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-percent"></i>
                                    Commission Type
                                </label>
                                <select name="commission_type" class="form-control">
                                    <option value="">No Commission</option>
                                    <option value="fixed"
                                        {{ old('commission_type', $agent->commission_type) == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount</option>
                                    <option value="percentage"
                                        {{ old('commission_type', $agent->commission_type) == 'percentage' ? 'selected' : '' }}>
                                        Percentage</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-coins"></i>
                                    Commission Value
                                </label>
                                <input type="number" name="commission_value" class="form-control"
                                    value="{{ old('commission_value', $agent->commission_value) }}" step="0.01"
                                    min="0" placeholder="0">
                            </div>
                        </div>
                    </div>

                    {{-- Bank Details --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-university"></i></div>
                            <div>
                                <h3 class="section-title">Bank Details</h3>
                                <p class="section-subtitle">Update bank account information</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-university"></i>
                                    Bank Name
                                </label>
                                <input type="text" name="bank_name" class="form-control"
                                    value="{{ old('bank_name', $agent->bank_name) }}"
                                    placeholder="e.g., State Bank of India">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-credit-card"></i>
                                    Account Number
                                </label>
                                <input type="text" name="account_number" class="form-control"
                                    value="{{ old('account_number', $agent->account_number) }}"
                                    placeholder="Bank account number">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-code"></i>
                                    IFSC Code
                                </label>
                                <input type="text" name="ifsc_code" class="form-control"
                                    value="{{ old('ifsc_code', $agent->ifsc_code) }}" placeholder="e.g., SBIN0001234">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-mobile-alt"></i>
                                    UPI ID
                                </label>
                                <input type="text" name="upi_id" class="form-control"
                                    value="{{ old('upi_id', $agent->upi_id) }}" placeholder="agent@bank">
                            </div>
                        </div>
                    </div>

                    {{-- Service Areas --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-map-marked-alt"></i></div>
                            <div>
                                <h3 class="section-title">Service Areas</h3>
                                <p class="section-subtitle">Select areas where agent can deliver</p>
                            </div>
                        </div>

                        @php
                            $serviceAreas = $agent->service_areas ? json_decode($agent->service_areas, true) : [];
                            $oldAreas = old('service_areas', $serviceAreas);
                        @endphp

                        <div class="service-areas-grid">
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Ahmedabad" class="checkbox-input"
                                    id="area1" {{ in_array('Ahmedabad', $oldAreas) ? 'checked' : '' }}>
                                <label for="area1" class="checkbox-label">Ahmedabad</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Gandhinagar" class="checkbox-input"
                                    id="area2" {{ in_array('Gandhinagar', $oldAreas) ? 'checked' : '' }}>
                                <label for="area2" class="checkbox-label">Gandhinagar</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Surat" class="checkbox-input"
                                    id="area3" {{ in_array('Surat', $oldAreas) ? 'checked' : '' }}>
                                <label for="area3" class="checkbox-label">Surat</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Vadodara" class="checkbox-input"
                                    id="area4" {{ in_array('Vadodara', $oldAreas) ? 'checked' : '' }}>
                                <label for="area4" class="checkbox-label">Vadodara</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Rajkot" class="checkbox-input"
                                    id="area5" {{ in_array('Rajkot', $oldAreas) ? 'checked' : '' }}>
                                <label for="area5" class="checkbox-label">Rajkot</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Bhavnagar" class="checkbox-input"
                                    id="area6" {{ in_array('Bhavnagar', $oldAreas) ? 'checked' : '' }}>
                                <label for="area6" class="checkbox-label">Bhavnagar</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Jamnagar" class="checkbox-input"
                                    id="area7" {{ in_array('Jamnagar', $oldAreas) ? 'checked' : '' }}>
                                <label for="area7" class="checkbox-label">Jamnagar</label>
                            </div>
                            <div class="service-area-item">
                                <input type="checkbox" name="service_areas[]" value="Junagadh" class="checkbox-input"
                                    id="area8" {{ in_array('Junagadh', $oldAreas) ? 'checked' : '' }}>
                                <label for="area8" class="checkbox-label">Junagadh</label>
                            </div>
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-file-alt"></i></div>
                            <div>
                                <h3 class="section-title">Documents</h3>
                                <p class="section-subtitle">Upload or replace agent documents</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            {{-- Aadhar Card --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Aadhar Card
                                </label>
                                @if ($agent->aadhar_card)
                                    <div class="current-doc">
                                        <a href="{{ Storage::url($agent->aadhar_card) }}" target="_blank">
                                            <i class="fas fa-file-pdf"></i> View Current Aadhar
                                        </a>
                                        <span class="delete-doc" onclick="markForDeletion('aadhar')"
                                            title="Remove this document"><i class="fas fa-trash-alt"></i></span>
                                        <input type="hidden" name="delete_aadhar" id="delete_aadhar" value="0">
                                    </div>
                                @endif
                                <div class="file-upload" onclick="document.getElementById('aadhar_input').click()">
                                    <input type="file" name="aadhar_card" id="aadhar_input" accept="image/*,.pdf"
                                        onchange="previewFile(this, 'aadhar_preview')">
                                    <div class="file-upload-label">
                                        <span class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                        <span
                                            class="file-upload-text">{{ $agent->aadhar_card ? 'Replace Aadhar Card' : 'Upload Aadhar Card' }}</span>
                                        <span class="file-upload-hint">JPG, PNG or PDF (Max 2MB)</span>
                                    </div>
                                </div>
                                <div id="aadhar_preview" class="file-preview"></div>
                            </div>

                            {{-- Driving License --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Driving License
                                </label>
                                @if ($agent->driving_license)
                                    <div class="current-doc">
                                        <a href="{{ Storage::url($agent->driving_license) }}" target="_blank">
                                            <i class="fas fa-file-pdf"></i> View Current License
                                        </a>
                                        <span class="delete-doc" onclick="markForDeletion('license')"
                                            title="Remove this document"><i class="fas fa-trash-alt"></i></span>
                                        <input type="hidden" name="delete_license" id="delete_license" value="0">
                                    </div>
                                @endif
                                <div class="file-upload" onclick="document.getElementById('license_input').click()">
                                    <input type="file" name="driving_license" id="license_input"
                                        accept="image/*,.pdf" onchange="previewFile(this, 'license_preview')">
                                    <div class="file-upload-label">
                                        <span class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                        <span
                                            class="file-upload-text">{{ $agent->driving_license ? 'Replace License' : 'Upload Driving License' }}</span>
                                        <span class="file-upload-hint">JPG, PNG or PDF (Max 2MB)</span>
                                    </div>
                                </div>
                                <div id="license_preview" class="file-preview"></div>
                            </div>

                            {{-- Photo --}}
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-camera"></i>
                                    Profile Photo
                                </label>
                                @if ($agent->photo)
                                    <div class="current-doc">
                                        <a href="{{ Storage::url($agent->photo) }}" target="_blank">
                                            <i class="fas fa-image"></i> View Current Photo
                                        </a>
                                        <span class="delete-doc" onclick="markForDeletion('photo')"
                                            title="Remove this document"><i class="fas fa-trash-alt"></i></span>
                                        <input type="hidden" name="delete_photo" id="delete_photo" value="0">
                                    </div>
                                @endif
                                <div class="file-upload" onclick="document.getElementById('photo_input').click()">
                                    <input type="file" name="photo" id="photo_input" accept="image/*"
                                        onchange="previewFile(this, 'photo_preview')">
                                    <div class="file-upload-label">
                                        <span class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></span>
                                        <span
                                            class="file-upload-text">{{ $agent->photo ? 'Replace Photo' : 'Upload Photo' }}</span>
                                        <span class="file-upload-hint">JPG or PNG (Max 2MB)</span>
                                    </div>
                                </div>
                                <div id="photo_preview" class="file-preview"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Status & Active --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-toggle-on"></i></div>
                            <div>
                                <h3 class="section-title">Status & Availability</h3>
                                <p class="section-subtitle">Update agent status and activity</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-toggle-on"></i>
                                    Current Status
                                </label>
                                <select name="status" class="form-control">
                                    <option value="available"
                                        {{ old('status', $agent->status) == 'available' ? 'selected' : '' }}>✅ Available -
                                        Ready for deliveries</option>
                                    <option value="busy"
                                        {{ old('status', $agent->status) == 'busy' ? 'selected' : '' }}>🚚 Busy - Currently
                                        delivering</option>
                                    <option value="offline"
                                        {{ old('status', $agent->status) == 'offline' ? 'selected' : '' }}>⭕ Offline - Not
                                        available</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        class="checkbox-input" {{ old('is_active', $agent->is_active) ? 'checked' : '' }}>
                                    <label for="is_active" class="checkbox-label">Agent is active and can receive
                                        deliveries</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="action-buttons">
                        <a href="{{ route('logistics.agents.show', $agent->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Update Agent
                        </button>
                    </div>
                </form>

                {{-- Delete Form --}}
                <div class="action-buttons"
                    style="justify-content: flex-start; padding-top: 0; border-top: 2px dashed #e5e7eb; margin-top: 0;">
                    <form method="POST" action="{{ route('logistics.agents.destroy', $agent->id) }}"
                        onsubmit="return confirm('⚠️ Are you sure you want to delete this agent?\n\nThis action cannot be undone and will remove all associated data.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete Agent Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="toast"></div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
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

        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'error');
                    input.value = '';
                    return;
                }

                const fileItem = document.createElement('div');
                fileItem.className = 'file-preview-item';
                fileItem.innerHTML = `
                <span><i class="fas fa-file"></i> ${file.name} (${(file.size / 1024).toFixed(1)} KB)</span>
                <span class="file-preview-remove" onclick="removeFile('${input.id}', '${previewId}')"><i class="fas fa-times-circle"></i></span>
            `;
                preview.appendChild(fileItem);
            }
        }

        function removeFile(inputId, previewId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).innerHTML = '';
            showToast('File removed', 'warning');
        }

        function markForDeletion(type) {
            if (confirm(`Remove current ${type} document?`)) {
                document.getElementById(`delete_${type}`).value = '1';
                showToast(`${type} document will be removed when you save`, 'warning');

                // Hide the current doc section
                const docSection = event.target.closest('.current-doc');
                if (docSection) {
                    docSection.style.opacity = '0.5';
                    docSection.style.pointerEvents = 'none';
                }
            }
        }

        // Form validation before submit
        document.getElementById('agentForm').addEventListener('submit', function(e) {
            const required = ['name', 'phone'];
            let missing = [];

            required.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input.value || input.value.trim() === '') {
                    missing.push(field.replace('_', ' '));
                }
            });

            if (missing.length > 0) {
                e.preventDefault();
                showToast('Please fill required fields: ' + missing.join(', '), 'error');
                return;
            }

            // Phone number validation
            const phone = document.querySelector('[name="phone"]').value;
            const phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                showToast('Please enter a valid 10-digit phone number', 'error');
                return;
            }

            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';
        });

        // Warn before leaving if changes made
        let formChanged = false;
        document.getElementById('agentForm').addEventListener('input', () => formChanged = true);

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });

        // Character count and validation styling
        document.querySelectorAll('input[type="text"], textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                this.style.borderColor = this.value ? '#10b981' : '#e5e7eb';
            });

            // Trigger on load
            if (field.value) {
                field.style.borderColor = '#10b981';
            }
        });

        // Add animation to checkboxes
        document.querySelectorAll('.checkbox-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('.checkbox-group, .service-area-item').style.background =
                        'linear-gradient(135deg, #667eea10, #764ba210)';
                } else {
                    this.closest('.checkbox-group, .service-area-item').style.background = '#f8fafc';
                }
            });

            // Trigger on load
            if (checkbox.checked) {
                checkbox.closest('.checkbox-group, .service-area-item').style.background =
                    'linear-gradient(135deg, #667eea10, #764ba210)';
            }
        });
    </script>
@endsection
