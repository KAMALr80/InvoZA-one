@extends('layouts.app')

@section('content')
    {{-- ADMIN ONLY GUARD --}}
    @if (auth()->user()->role !== 'admin')
        <div class="unauthorized-container">
            <div class="unauthorized-card">
                <div class="unauthorized-icon">🚫</div>
                <h2 class="unauthorized-title">Access Denied</h2>
                <p class="unauthorized-message">You don't have permission to add employees.</p>
                <a href="{{ route('employees.index') }}" class="btn-back">
                    ← Back to Employees
                </a>
            </div>
        </div>
    @else
        <div class="form-container">
            <div class="form-card">
                <div class="form-header">
                    <div class="form-icon">➕</div>
                    <div class="form-header-text">
                        <h1 class="form-title">Add New Employee</h1>
                        <p class="form-subtitle">Fill in the details to add a new team member</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="error-alert">
                        <span class="error-icon">⚠️</span>
                        <div class="error-content">
                            <h4 class="error-title">Please fix the following errors:</h4>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('employees.store') }}" class="employee-form">
                    @csrf

                    <div class="form-grid">
                        {{-- Name Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">👤</span>
                                Full Name
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-input"
                                placeholder="Enter employee name">
                        </div>

                        {{-- Email Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">📧</span>
                                Email Address
                                <span class="required">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="form-input"
                                placeholder="employee@company.com">
                        </div>

                        {{-- Password Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">🔒</span>
                                Password
                                <span class="required">*</span>
                            </label>
                            <div class="password-wrapper">
                                <input type="password" name="password" required class="form-input password-input"
                                    placeholder="Enter password" id="password">
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    👁️
                                </button>
                            </div>
                            <div class="password-hint">
                                Minimum 8 characters with letters and numbers
                            </div>
                        </div>

                        {{-- Phone Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">📱</span>
                                Phone Number
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input"
                                placeholder="+1 234 567 8900">
                        </div>

                        {{-- Department Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">🏢</span>
                                Department
                            </label>
                            <div class="select-wrapper">
                                <select name="department" class="form-select">
                                    <option value="">Select Department</option>
                                    <option value="Human Resources"
                                        {{ old('department') == 'Human Resources' ? 'selected' : '' }}>Human Resources
                                    </option>
                                    <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>
                                        Engineering</option>
                                    <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales
                                    </option>
                                    <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance
                                    </option>
                                    <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>
                                        Operations</option>
                                    <option value="Customer Support"
                                        {{ old('department') == 'Customer Support' ? 'selected' : '' }}>Customer Support
                                    </option>
                                    <option value="Other" {{ old('department') == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                <span class="select-arrow">▼</span>
                            </div>
                        </div>

                        {{-- Joining Date Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">📅</span>
                                Joining Date
                            </label>
                            <input type="date" name="joining_date" value="{{ old('joining_date') }}" class="form-input">
                        </div>

                        {{-- Employee Code Field (if you want to show it) --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">#️⃣</span>
                                Employee Code
                            </label>
                            <input type="text" name="employee_code" value="{{ old('employee_code') }}"
                                class="form-input" placeholder="Will be auto-generated if left blank">
                            <div class="field-hint">Leave blank for auto-generation</div>
                        </div>

                        {{-- Role Field (optional) --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">🎭</span>
                                Role
                            </label>
                            <div class="select-wrapper">
                                <select name="role" class="form-select">
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR Manager</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator
                                    </option>
                                </select>
                                <span class="select-arrow">▼</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <span class="btn-icon">💾</span>
                            Save Employee
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn-cancel">
                            <span class="btn-icon">❌</span>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const toggleButton = document.querySelector('.password-toggle');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleButton.textContent = '🙈';
                } else {
                    passwordInput.type = 'password';
                    toggleButton.textContent = '👁️';
                }
            }

            // Set default date to today
            document.addEventListener('DOMContentLoaded', function() {
                const dateInput = document.querySelector('input[name="joining_date"]');
                if (!dateInput.value) {
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.value = today;
                }
            });
        </script>
    @endif

    <style>
        /* Main Container */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        /* Form Header */
        .form-header {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 32px;
        }

        .form-icon {
            font-size: 48px;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }

        .form-header-text {
            flex: 1;
        }

        .form-title {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 16px;
            margin: 8px 0 0 0;
        }

        /* Error Alert */
        .error-alert {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 2px solid #ef4444;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 32px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .error-icon {
            font-size: 24px;
            color: #dc2626;
        }

        .error-content {
            flex: 1;
        }

        .error-title {
            font-size: 16px;
            font-weight: 700;
            color: #991b1b;
            margin: 0 0 8px 0;
        }

        .error-list {
            margin: 0;
            padding-left: 20px;
            color: #7f1d1d;
        }

        .error-list li {
            margin-bottom: 4px;
            font-size: 14px;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .label-icon {
            font-size: 16px;
            opacity: 0.8;
        }

        .required {
            color: #ef4444;
            margin-left: 4px;
        }

        /* Form Inputs */
        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            color: #1e293b;
            background: white;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        /* Password Field */
        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 60px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            background: #f3f4f6;
            color: #4b5563;
        }

        .password-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
        }

        /* Select Wrapper */
        .select-wrapper {
            position: relative;
        }

        .form-select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            color: #1e293b;
            background: white;
            appearance: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }

        .select-arrow {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            font-size: 12px;
        }

        /* Field Hints */
        .field-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
            font-style: italic;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 16px;
            padding-top: 32px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 18px 32px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
        }

        .btn-cancel {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
            padding: 18px 32px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-2px);
        }

        .btn-icon {
            font-size: 18px;
        }

        /* Unauthorized Container */
        .unauthorized-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .unauthorized-card {
            background: white;
            border-radius: 24px;
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        .unauthorized-icon {
            font-size: 80px;
            margin-bottom: 24px;
            opacity: 0.8;
        }

        .unauthorized-title {
            font-size: 28px;
            font-weight: 800;
            color: #dc2626;
            margin: 0 0 16px 0;
        }

        .unauthorized-message {
            color: #64748b;
            font-size: 16px;
            margin: 0 0 32px 0;
            line-height: 1.6;
        }

        .btn-back {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 16px;
            }

            .form-card {
                padding: 24px;
            }

            .form-header {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }

            .form-icon {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }

            .form-title {
                font-size: 24px;
            }

            .form-subtitle {
                font-size: 14px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }

            .unauthorized-card {
                padding: 40px 24px;
            }

            .unauthorized-icon {
                font-size: 60px;
            }

            .unauthorized-title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {

            .form-input,
            .form-select {
                padding: 14px 16px;
                font-size: 14px;
            }

            .btn-submit,
            .btn-cancel {
                padding: 16px 24px;
                font-size: 15px;
            }
        }
    </style>
@endsection
