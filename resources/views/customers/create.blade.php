@extends('layouts.app')

@section('content')
    <style>
        /* Customer Form Styles */
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 50px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #10b981 0%, #3b82f6 50%, #8b5cf6 100%);
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .form-icon span {
            font-size: 36px;
            color: white;
        }

        .form-title {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 15px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .required::after {
            content: "*";
            color: #ef4444;
            margin-left: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: white;
            font-size: 15px;
            color: #374151;
            transition: all 0.2s;
            outline: none;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .form-input.success {
            border-color: #10b981;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
            line-height: 1.5;
            padding: 16px;
            padding-left: 48px;
        }

        .form-textarea+.input-icon {
            top: 20px;
            transform: none;
        }

        .char-count {
            position: absolute;
            right: 12px;
            bottom: 12px;
            font-size: 12px;
            color: #9ca3af;
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .form-footer {
            display: flex;
            gap: 16px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }

        .submit-btn {
            flex: 1;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 18px 32px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(16, 185, 129, 0.35);
        }

        .submit-btn:active:not(:disabled) {
            transform: translateY(-1px);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .cancel-btn {
            background: #f3f4f6;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            padding: 18px 32px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .cancel-btn:hover {
            background: #e5e7eb;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            display: none;
        }

        .error-message.show {
            display: flex;
        }

        .success-message {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border-left: 4px solid #10b981;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
            display: none;
        }

        .success-message.show {
            display: flex;
        }

        .validation-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            display: none;
        }

        .validation-icon.success {
            color: #10b981;
            display: block;
        }

        .validation-icon.error {
            color: #ef4444;
            display: block;
        }

        .hint-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
            font-style: italic;
        }

        /* Animation */
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

        .form-group {
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.4s;
        }

        .form-group:nth-child(5) {
            animation-delay: 0.5s;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .form-container {
                padding: 30px 20px;
                margin: 20px;
            }

            .form-footer {
                flex-direction: column;
            }

            .form-title {
                font-size: 28px;
            }
        }
    </style>

    <div class="form-container">
        <div class="form-header">
            <div class="form-icon">
                <span>👤</span>
            </div>
            <h1 class="form-title">Add New Customer</h1>
            <p class="form-subtitle">Fill in customer details to add to your database</p>
        </div>

        @if (session('success'))
            <div class="success-message show">
                <span style="font-size: 18px;">✅</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message show" style="margin-bottom: 24px;">
                <span style="font-size: 18px;">⚠️</span>
                Please fix the errors below to continue.
            </div>
        @endif

        <form method="POST" action="{{ route('customers.store') }}" id="customerForm">
            @csrf

            <div class="form-group">
                <label class="form-label required">
                    <span>👤</span>
                    Full Name
                </label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" name="name" placeholder="Enter customer's full name"
                        class="form-input @error('name') error @endif"
                       value="{{ old('name') }}"
                       required
                       autofocus>
                <span class="validation-icon success">✓</span>
                <span class="validation-icon error">✗</span>
            </div>
            @error('name')
                <div class="error-message show">
                    <span>⚠️</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
                    <label class="form-label required">
                        <span>📱</span>
                        Mobile Number
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">📱</span>
                        <input type="tel" name="mobile" placeholder="Enter 10-digit mobile number"
                            class="form-input @error('mobile') error @endif"
                       value="{{ old('mobile') }}"
                       required
                       pattern="[0-9]{10}"
                       maxlength="10">
                <span class="validation-icon success">✓</span>
                <span class="validation-icon error">✗</span>
            </div>
            @error('mobile')
                <div class="error-message show">
                    <span>⚠️</span>
                    {{ $message }}
                </div>
            @enderror
            <div class="hint-text">Enter
                        10-digit mobile number without country code
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span>📧</span>
                        Email Address
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email" name="email" placeholder="customer@example.com"
                            class="form-input @error('email') error @endif"
                       value="{{ old('email') }}">
                <span class="validation-icon success">✓</span>
                <span class="validation-icon error">✗</span>
            </div>
            @error('email')
                <div class="error-message show">
                    <span>⚠️</span>
                    {{ $message }}
                </div>
            @enderror
            <div class="hint-text">Optional
                        - for sending invoices and updates
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span>🏢</span>
                        GST Number
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">🏢</span>
                        <input type="text" name="gst_no" placeholder="Ex: 27ABCDE1234F1Z5"
                            class="form-input @error('gst_no') error @endif"
                       value="{{ old('gst_no') }}"
                       pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}">
                <span class="validation-icon success">✓</span>
                <span class="validation-icon error">✗</span>
            </div>
            @error('gst_no')
                <div class="error-message show">
                    <span>⚠️</span>
                    {{ $message }}
                </div>
            @enderror
            <div class="hint-text">Optional
                        - 15-character GSTIN format
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span>📍</span>
                        Address
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">📍</span>
                        <textarea name="address" placeholder="Enter complete address including city, state, and PIN code"
                            class="form-input form-textarea @error('address') error @endif"
                          rows="4">{{ old('address') }}</textarea>
                <div class="char-count">
                    <span id="addressCount">0</span>/200
                </div>
                <span class="validation-icon success">✓</span>
                <span class="validation-icon error">✗</span>
            </div>
            @error('address')
                <div class="error-message show">
                    <span>⚠️</span>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-footer">
                    <a href="{{ route('customers.index') }}" class="cancel-btn">
                        <span>←</span>
                        Cancel
                    </a>
                    <button type="submit" id="saveBtn" class="submit-btn">
                        <span>💾</span>
                        Save Customer
                    </button>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('customerForm');
                const saveBtn = document.getElementById('saveBtn');
                const inputs = form.querySelectorAll('.form-input');
                const addressTextarea = form.querySelector('textarea[name="address"]');
                const addressCount = document.getElementById('addressCount');

                // Real-time validation
                inputs.forEach(input => {
                    const validationSuccess = input.parentNode.querySelector('.validation-icon.success');
                    const validationError = input.parentNode.querySelector('.validation-icon.error');
                    const errorMessage = input.parentNode.parentNode.querySelector('.error-message');

                    input.addEventListener('input', function() {
                        validateField(this, validationSuccess, validationError, errorMessage);
                    });

                    input.addEventListener('blur', function() {
                        validateField(this, validationSuccess, validationError, errorMessage);
                    });

                    // Validate on page load if there's existing value
                    if (input.value) {
                        validateField(input, validationSuccess, validationError, errorMessage);
                    }
                });

                // Address character counter
                if (addressTextarea && addressCount) {
                    addressTextarea.addEventListener('input', function() {
                        const length = this.value.length;
                        addressCount.textContent = length;

                        if (length > 200) {
                            this.value = this.value.substring(0, 200);
                            addressCount.textContent = 200;
                        }
                    });

                    // Initialize counter
                    addressCount.textContent = addressTextarea.value.length;
                }

                // Form submission
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Validate all required fields
                    inputs.forEach(input => {
                        if (input.hasAttribute('required') && !input.value.trim()) {
                            isValid = false;
                            input.classList.add('error');
                            input.classList.remove('success');

                            const validationSuccess = input.parentNode.querySelector(
                                '.validation-icon.success');
                            const validationError = input.parentNode.querySelector(
                                '.validation-icon.error');
                            const errorMessage = input.parentNode.parentNode.querySelector(
                                '.error-message');

                            if (validationSuccess) validationSuccess.style.display = 'none';
                            if (validationError) validationError.style.display = 'block';
                            if (errorMessage && !errorMessage.classList.contains('show')) {
                                errorMessage.textContent = 'This field is required';
                                errorMessage.classList.add('show');
                            }
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        return;
                    }

                    // Show loading state
                    saveBtn.disabled = true;
                    saveBtn.innerHTML = `
                <span style="
                    display: inline-block;
                    width: 20px;
                    height: 20px;
                    border: 2px solid rgba(255,255,255,0.3);
                    border-top-color: white;
                    border-radius: 50%;
                    animation: buttonSpin 0.6s linear infinite;
                "></span>
                Saving...
            `;

                    // Add spin animation
                    const style = document.createElement('style');
                    style.innerHTML = `
                @keyframes buttonSpin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
                    document.head.appendChild(style);
                });

                // Validation function
                function validateField(input, successIcon, errorIcon, errorMessage) {
                    const value = input.value.trim();
                    let isValid = true;
                    let message = '';

                    // Clear previous states
                    input.classList.remove('error', 'success');
                    if (successIcon) successIcon.style.display = 'none';
                    if (errorIcon) errorIcon.style.display = 'none';
                    if (errorMessage) errorMessage.classList.remove('show');

                    // Required validation
                    if (input.hasAttribute('required') && !value) {
                        isValid = false;
                        message = 'This field is required';
                    }

                    // Email validation
                    if (input.type === 'email' && value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            isValid = false;
                            message = 'Please enter a valid email address';
                        }
                    }

                    // Mobile validation
                    if (input.name === 'mobile' && value) {
                        const mobileRegex = /^[0-9]{10}$/;
                        if (!mobileRegex.test(value)) {
                            isValid = false;
                            message = 'Please enter a valid 10-digit mobile number';
                        }
                    }

                    // GST validation
                    if (input.name === 'gst_no' && value) {
                        const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
                        if (!gstRegex.test(value)) {
                            isValid = false;
                            message = 'Please enter a valid 15-character GSTIN';
                        }
                    }

                    // Update UI based on validation
                    if (isValid && value) {
                        input.classList.add('success');
                        if (successIcon) successIcon.style.display = 'block';
                    } else if (!isValid) {
                        input.classList.add('error');
                        if (errorIcon) errorIcon.style.display = 'block';
                        if (errorMessage && message) {
                            errorMessage.textContent = message;
                            errorMessage.classList.add('show');
                        }
                    }

                    return isValid;
                }

                // Auto-format mobile number
                const mobileInput = form.querySelector('input[name="mobile"]');
                if (mobileInput) {
                    mobileInput.addEventListener('input', function() {
                        // Remove non-numeric characters
                        this.value = this.value.replace(/\D/g, '');

                        // Limit to 10 digits
                        if (this.value.length > 10) {
                            this.value = this.value.substring(0, 10);
                        }
                    });
                }

                // Auto-format GST (uppercase)
                const gstInput = form.querySelector('input[name="gst_no"]');
                if (gstInput) {
                    gstInput.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                }
            });
        </script>
@endsection
