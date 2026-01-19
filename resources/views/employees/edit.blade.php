@extends('layouts.app')

@section('content')
    {{-- ADMIN + HR ONLY --}}
    @if (!in_array(auth()->user()->role, ['admin', 'hr']))
        <div class="unauthorized-container">
            <div class="unauthorized-card">
                <div class="unauthorized-icon">🚫</div>
                <h2 class="unauthorized-title">Access Restricted</h2>
                <p class="unauthorized-message">Only administrators and HR managers can edit employee details.</p>
                <a href="{{ route('employees.index') }}" class="btn-back">
                    ← Back to Employees
                </a>
            </div>
        </div>
    @else
        <div class="form-container">
            <div class="form-card">
                <div class="form-header">
                    <div class="form-icon">✏️</div>
                    <div class="form-header-text">
                        <h1 class="form-title">Edit Employee</h1>
                        <p class="form-subtitle">Update details for {{ $employee->name }}</p>
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

                @if (session('success'))
                    <div class="success-message">
                        <span class="success-icon">✓</span>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employees.update', $employee->id) }}" class="employee-form">
                    @csrf
                    @method('PUT')

                    <div class="employee-info">
                        <div class="employee-avatar">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                        <div class="employee-details">
                            <div class="employee-code">{{ $employee->employee_code }}</div>
                            <div class="employee-name">{{ $employee->name }}</div>
                            <div class="employee-email">{{ $employee->email }}</div>
                        </div>
                    </div>

                    <div class="form-grid">
                        {{-- Name Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">👤</span>
                                Full Name
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                                class="form-input" placeholder="Enter employee name">
                        </div>

                        {{-- Email Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">📧</span>
                                Email Address
                                <span class="required">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" required
                                class="form-input" placeholder="employee@company.com">
                        </div>

                        {{-- Password Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">🔒</span>
                                Password
                                <span class="optional">(Optional)</span>
                            </label>
                            <div class="password-wrapper">
                                <input type="password" name="password" class="form-input password-input"
                                    placeholder="Leave blank to keep current" id="password">
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    👁️
                                </button>
                            </div>
                            <div class="password-hint">
                                Only enter if you want to change the password
                            </div>
                        </div>

                        {{-- Phone Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">📱</span>
                                Phone Number
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $employee->phone) }}"
                                class="form-input" placeholder="+1 234 567 8900">
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
                                        {{ old('department', $employee->department) == 'Human Resources' ? 'selected' : '' }}>
                                        Human Resources</option>
                                    <option value="Engineering"
                                        {{ old('department', $employee->department) == 'Engineering' ? 'selected' : '' }}>
                                        Engineering</option>
                                    <option value="Sales"
                                        {{ old('department', $employee->department) == 'Sales' ? 'selected' : '' }}>Sales
                                    </option>
                                    <option value="Marketing"
                                        {{ old('department', $employee->department) == 'Marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="Finance"
                                        {{ old('department', $employee->department) == 'Finance' ? 'selected' : '' }}>
                                        Finance</option>
                                    <option value="Operations"
                                        {{ old('department', $employee->department) == 'Operations' ? 'selected' : '' }}>
                                        Operations</option>
                                    <option value="Customer Support"
                                        {{ old('department', $employee->department) == 'Customer Support' ? 'selected' : '' }}>
                                        Customer Support</option>
                                    <option value="Other"
                                        {{ old('department', $employee->department) == 'Other' ? 'selected' : '' }}>Other
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
                            <input type="date" name="joining_date"
                                value="{{ old('joining_date', $employee->joining_date) }}" class="form-input">
                        </div>

                        {{-- Employee Code Field --}}
                        <div class="form-group">
                            <label class="form-label">
                                <span class="label-icon">#️⃣</span>
                                Employee Code
                            </label>
                            <input type="text" name="employee_code"
                                value="{{ old('employee_code', $employee->employee_code) }}" class="form-input" readonly>
                            <div class="field-hint">Employee code cannot be changed</div>
                        </div>

                        {{-- Role Field --}}
                        @if (auth()->user()->role === 'admin')
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="label-icon">🎭</span>
                                    Role
                                </label>
                                <div class="select-wrapper">
                                    <select name="role" class="form-select">
                                        <option value="staff"
                                            {{ old('role', $employee->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="hr"
                                            {{ old('role', $employee->role) == 'hr' ? 'selected' : '' }}>HR Manager
                                        </option>
                                        <option value="admin"
                                            {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Administrator
                                        </option>
                                    </select>
                                    <span class="select-arrow">▼</span>
                                </div>
                                <div class="field-hint">Admin only: Change user role</div>
                            </div>
                        @endif

                        {{-- Status Field --}}
                        @if (auth()->user()->role === 'admin')
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="label-icon">📊</span>
                                    Status
                                </label>
                                <div class="select-wrapper">
                                    <select name="status" class="form-select">
                                        <option value="active"
                                            {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                        <option value="on_leave"
                                            {{ old('status', $employee->status) == 'on_leave' ? 'selected' : '' }}>On Leave
                                        </option>
                                    </select>
                                    <span class="select-arrow">▼</span>
                                </div>
                                <div class="field-hint">Admin only: Change employment status</div>
                            </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <span class="btn-icon">💾</span>
                            Update Employee
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
            max-width: 1000px;
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
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

        /* Employee Info */
        .employee-info {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 2px solid #e5e7eb;
        }

        .employee-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
        }

        .employee-details {
            flex: 1;
        }

        .employee-code {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 8px;
            border: 1px solid #fbbf24;
        }

        .employee-name {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .employee-email {
            color: #64748b;
            font-size: 14px;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 2px solid #10b981;
            color: #065f46;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .success-icon {
            font-size: 20px;
            color: #10b981;
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

        .optional {
            color: #6b7280;
            font-size: 12px;
            font-weight: normal;
            margin-left: 6px;
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .form-input[readonly] {
            background: #f8fafc;
            color: #6b7280;
            cursor: not-allowed;
            border-color: #e5e7eb;
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
            font-style: italic;
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.4);
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

            .employee-info {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }

            .employee-avatar {
                width: 60px;
                height: 60px;
                font-size: 24px;
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
