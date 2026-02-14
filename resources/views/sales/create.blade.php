@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="invoice-container">
        {{-- INVOICE HEADER WITH CUSTOMER CLEAR BUTTON --}}
        <div class="invoice-header">
            <div class="header-left">
                <div class="header-icon">
                    <span>🧾</span>
                </div>
                <div>
                    <h1 class="header-title">Create New Invoice</h1>
                    <p class="header-subtitle">Step 1: Select customer → Step 2: Add products</p>
                </div>
            </div>

            {{-- CLEAR CUSTOMER BUTTON --}}
            <div id="clearCustomerContainer" style="display: none;">
                <button type="button" onclick="InvoiceManager.clearCustomerSelection()" class="btn-clear-customer">
                    <span>✕</span>
                    Clear Customer
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('sales.store') }}" id="invoiceForm"
            onsubmit="return InvoiceManager.handleSubmit(event)">
            @csrf
            <input type="text" id="barcodeInput" autocomplete="off" class="barcode-scanner-input">
            <input type="hidden" name="invoice_token" value="{{ Str::uuid() }}">

            {{-- CUSTOMER + SEARCH SECTION --}}
            <div class="section-card">
                <h3 class="section-title">
                    <span class="step-badge step-1">1</span>
                    Step 1: Select Customer (Required)
                </h3>

                {{-- SELECTED CUSTOMER INFO DISPLAY --}}
                <div id="selectedCustomerInfo" class="selected-customer-card" style="display: none;">
                    <div class="selected-customer-content">
                        <div class="customer-avatar">
                            <span>👤</span>
                        </div>
                        <div class="customer-details">
                            <div class="customer-label">CUSTOMER SELECTED</div>
                            <div id="selectedCustomerName" class="customer-name"></div>
                        </div>
                        <div class="customer-contact">
                            <div id="selectedCustomerMobile" class="contact-item">
                                <span>📱</span>
                                <span id="selectedCustomerMobileText"></span>
                            </div>
                            <div id="selectedCustomerEmail" class="contact-item">
                                <span>✉️</span>
                                <span id="selectedCustomerEmailText"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-2">
                    {{-- Customer Selection --}}
                    <div class="form-group">
                        <label class="form-label">
                            Select Customer
                            <span class="required-star">*</span>
                            <span id="customerStatus" class="status-text"></span>
                        </label>
                        <div class="customer-search-group">
                            <div class="search-wrapper">
                                <input type="text" id="customerSearch"
                                    placeholder="Type customer name or mobile to search..." autocomplete="off"
                                    class="form-input">
                                <input type="hidden" name="customer_id" id="customer_id">
                                <div id="customerResults" class="search-results"></div>
                            </div>
                            <button type="button" onclick="InvoiceManager.openCustomerModal()" class="btn-add-customer">
                                <span>+</span>
                                Add New
                            </button>
                        </div>
                    </div>

                    {{-- Product Search --}}
                    <div class="form-group">
                        <label class="form-label">
                            Step 2: Search Products
                            <span id="productStatus" class="status-text"></span>
                        </label>
                        <div class="search-wrapper">
                            <input type="text" id="productSearch" disabled placeholder="First select a customer above..."
                                class="form-input form-input-disabled">
                            <span class="search-icon">🔍</span>
                            <div id="productResults" class="search-results"></div>
                        </div>
                        <div class="hint-text">
                            <span id="productSearchHint">Select a customer first to enable product search</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEMS TABLE SECTION --}}
            <div class="section-card">
                <h3 class="section-title">
                    <span class="step-badge step-2">2</span>
                    Invoice Items
                    <span id="itemsStatus" class="status-text"></span>
                </h3>

                <div class="table-container">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price (₹)</th>
                                <th>Quantity</th>
                                <th>Total (₹)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTable">
                            <tr id="emptyState" class="empty-state">
                                <td colspan="5">
                                    <div class="empty-state-content">
                                        <span class="empty-icon">📦</span>
                                        <p>Select a customer first, then search and add products</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTALS SECTION --}}
            <div class="section-card">
                <h3 class="section-title">
                    <span class="step-badge step-3">3</span>
                    Invoice Summary
                </h3>

                <div class="totals-grid">
                    <div class="total-item">
                        <label class="total-label">Sub Total</label>
                        <div class="input-prefix">
                            <span class="prefix">₹</span>
                            <input id="sub_total" name="sub_total" readonly value="0.00" class="total-input">
                        </div>
                    </div>
                    <div class="total-item">
                        <label class="total-label">Discount (₹)</label>
                        <div class="input-prefix">
                            <span class="prefix">₹</span>
                            <input id="discount" name="discount" value="0" oninput="InvoiceManager.calculate()"
                                class="total-input-editable">
                        </div>
                    </div>
                    <div class="total-item">
                        <label class="total-label">Tax (%)</label>
                        <div class="input-prefix">
                            <span class="prefix">%</span>
                            <input id="tax" name="tax" value="0" oninput="InvoiceManager.calculate()"
                                class="total-input-editable">
                        </div>
                    </div>
                    <div class="total-item">
                        <label class="total-label grand-total-label">Grand Total</label>
                        <div class="input-prefix">
                            <span class="prefix grand-prefix">₹</span>
                            <input id="grand_total" name="grand_total" readonly value="0.00"
                                class="grand-total-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="form-actions">
                <button type="submit" id="saveBtn" class="btn-submit">
                    <span class="btn-icon">💾</span>
                    Save & Generate Invoice
                </button>
            </div>
        </form>
    </div>

    {{-- CUSTOMER MODAL --}}
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <span>👤</span>
                </div>
                <div>
                    <h3 class="modal-title">Add New Customer</h3>
                    <p class="modal-subtitle">Fill customer details below</p>
                </div>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required-star">*</span></label>
                    <input id="c_name" placeholder="Enter customer name" class="form-input">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Mobile <span class="required-star">*</span></label>
                        <input id="c_mobile" placeholder="Enter mobile number" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input id="c_email" placeholder="Enter email address" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea id="c_address" placeholder="Enter customer address" rows="3" class="form-textarea"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button onclick="InvoiceManager.closeCustomerModal()" class="btn-cancel">Cancel</button>
                <button onclick="InvoiceManager.saveCustomer()" id="saveCustomerBtn" class="btn-save-customer">
                    <span>✓</span>
                    Save Customer
                </button>
            </div>
        </div>
    </div>

    <style>
        /* ========== GLOBAL STYLES ========== */
        .invoice-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            border-radius: 20px;
            max-width: 1300px;
            margin: 30px auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border: 1px solid #e5e7eb;
        }

        /* Header Styles */
        .invoice-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
        }

        .header-icon span {
            font-size: 28px;
            color: white;
        }

        .header-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            color: #64748b;
            margin: 5px 0 0;
            font-size: 15px;
        }

        /* Section Cards */
        .section-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #374151;
            margin: 0 0 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-badge {
            display: inline-flex;
            width: 24px;
            height: 24px;
            color: white;
            border-radius: 6px;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .step-1 {
            background: #3b82f6;
        }

        .step-2 {
            background: #f59e0b;
        }

        .step-3 {
            background: #10b981;
        }

        /* Form Elements */
        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .required-star {
            color: #ef4444;
            font-weight: bold;
        }

        .status-text {
            font-size: 12px;
            margin-left: 8px;
            font-weight: normal;
        }

        .form-input {
            width: 90%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid #d1d5db;
            background: white;
            font-size: 15px;
            color: #374151;
            transition: all 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input-disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: white;
            font-size: 15px;
            color: #374151;
            outline: none;
            resize: vertical;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-textarea:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* Grid Layouts */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        /* Customer Selection */
        .customer-search-group {
            display: flex;
            gap: 12px;
        }

        .search-wrapper {
            position: relative;
            flex: 1;
        }

        .search-results {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
        }

        .hint-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        /* Selected Customer Card */
        .selected-customer-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1.5px solid #0ea5e9;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease-out;
        }

        .selected-customer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .customer-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .customer-avatar span {
            font-size: 22px;
            color: white;
        }

        .customer-details {
            flex: 1;
        }

        .customer-label {
            font-size: 12px;
            color: #0c4a6e;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .customer-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
        }

        .customer-contact {
            display: flex;
            gap: 10px;
            font-size: 13px;
            color: #475569;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Buttons */
        .btn-add-customer {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            transition: all 0.2s;
        }

        .btn-add-customer:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-clear-customer {
            background: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #fecaca;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
        }

        .btn-clear-customer:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.15);
        }

        .btn-submit {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.25);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .invoice-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-bottom: 2px solid #dbeafe;
        }

        .invoice-table td {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .empty-state td {
            background: #f8fafc;
            text-align: center;
            color: #64748b;
            font-style: italic;
        }

        .empty-state-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 48px;
        }

        /* Totals Section */
        .totals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .total-item {
            position: relative;
        }

        .total-label {
            display: block;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .grand-total-label {
            color: #1e293b;
            font-weight: 700;
        }

        .input-prefix {
            position: relative;
        }

        .prefix {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #374151;
            font-weight: 600;
            font-size: 18px;
        }

        .grand-prefix {
            color: #1e293b;
            font-weight: 700;
        }

        .total-input {
            width: 70%;
            padding: 14px 16px 14px 42px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: #f8fafc;
            font-size: 18px;
            font-weight: 700;
            color: #374151;
            text-align: right;
            outline: none;
        }

        .total-input-editable {
            width: 70%;
            padding: 14px 16px 14px 42px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: white;
            font-size: 16px;
            color: #374151;
            text-align: right;
            outline: none;
            transition: all 0.2s;
        }

        .total-input-editable:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .grand-total-input {
            width: 70%;
            padding: 14px 16px 14px 42px;
            border-radius: 12px;
            border: 2px solid #1e293b;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            text-align: right;
            outline: none;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
        }

        /* Form Actions */
        .form-actions {
            text-align: right;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
            border: 1px solid #e5e7eb;
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .modal-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon span {
            font-size: 24px;
            color: white;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .modal-subtitle {
            color: #64748b;
            margin: 4px 0 0;
            font-size: 14px;
        }

        .modal-body {
            display: grid;
            gap: 16px;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .btn-save-customer {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            transition: all 0.2s;
        }

        .btn-save-customer:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-save-customer:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Barcode Scanner Input */
        .barcode-scanner-input {
            position: absolute;
            opacity: 0;
            height: 0;
            width: 0;
            pointer-events: none;
        }

        /* Toast Notifications */
        .toast-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            max-width: 400px;
            animation: slideInRight 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
        }

        .toast-success {
            background: #10b981;
        }

        .toast-error {
            background: #ef4444;
        }

        .toast-info {
            background: #3b82f6;
        }

        .toast-icon {
            font-size: 20px;
        }

        .toast-message {
            flex: 1;
            color: white;
        }

        /* Animations */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(10px);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes buttonSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .invoice-container {
                padding: 30px;
                margin: 20px;
            }

            .totals-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .invoice-container {
                padding: 20px;
            }

            .header-title {
                font-size: 24px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }

            .customer-search-group {
                flex-direction: column;
            }

            .btn-add-customer {
                padding: 12px;
                justify-content: center;
            }

            .totals-grid {
                grid-template-columns: 1fr;
            }

            .selected-customer-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .customer-contact {
                flex-direction: column;
                width: 100%;
            }
        }
    </style>

    <script>
        /**
         * Invoice Manager - Centralized invoice management system
         * Handles customer selection, product management, barcode scanning, and invoice submission
         */
        const InvoiceManager = (function() {
            // ========== STATE MANAGEMENT ==========
            let state = {
                products: @json($products),
                isCustomerSelected: false,
                isSavingCustomer: false,
                isScannerEnabled: false,
                barcodeBuffer: '',
                barcodeTimeout: null,
                customerTimer: null
            };

            // ========== DOM ELEMENTS ==========
            const elements = {
                // Customer elements
                customerSearch: document.getElementById('customerSearch'),
                customerResults: document.getElementById('customerResults'),
                customerIdInput: document.getElementById('customer_id'),
                customerStatus: document.getElementById('customerStatus'),
                selectedCustomerInfo: document.getElementById('selectedCustomerInfo'),
                selectedCustomerName: document.getElementById('selectedCustomerName'),
                selectedCustomerMobileText: document.getElementById('selectedCustomerMobileText'),
                selectedCustomerEmailText: document.getElementById('selectedCustomerEmailText'),
                clearCustomerContainer: document.getElementById('clearCustomerContainer'),

                // Product elements
                productSearch: document.getElementById('productSearch'),
                productResults: document.getElementById('productResults'),
                productStatus: document.getElementById('productStatus'),
                productSearchHint: document.getElementById('productSearchHint'),

                // Table elements
                itemsTable: document.getElementById('itemsTable'),
                emptyState: document.getElementById('emptyState'),
                itemsStatus: document.getElementById('itemsStatus'),

                // Barcode elements
                barcodeInput: document.getElementById('barcodeInput'),

                // Modal elements
                customerModal: document.getElementById('customerModal'),
                saveCustomerBtn: document.getElementById('saveCustomerBtn')
            };

            // ========== INITIALIZATION ==========
            function init() {
                disableBarcodeScanner();
                attachEventListeners();
                updateUIState();
            }

            // ========== EVENT LISTENERS ==========
            function attachEventListeners() {
                // Customer search
                if (elements.customerSearch) {
                    elements.customerSearch.addEventListener('input', handleCustomerSearch);
                    elements.customerSearch.addEventListener('focus', disableBarcodeScanner);
                    elements.customerSearch.addEventListener('blur', handleCustomerBlur);
                }

                // Product search
                if (elements.productSearch) {
                    elements.productSearch.addEventListener('input', handleProductSearch);
                    elements.productSearch.addEventListener('focus', disableBarcodeScanner);
                    elements.productSearch.addEventListener('blur', handleProductBlur);
                }

                // Barcode scanner
                if (elements.barcodeInput) {
                    elements.barcodeInput.addEventListener('input', handleBarcodeInput);
                    elements.barcodeInput.addEventListener('keydown', handleBarcodeKeydown);
                }

                // Document click (close search results)
                document.addEventListener('click', handleDocumentClick);
                document.addEventListener('mousedown', handleDocumentMousedown);
            }

            // ========== CUSTOMER FUNCTIONS ==========
            function handleCustomerSearch() {
                const query = this.value.trim();

                clearTimeout(state.customerTimer);

                if (query.length < 2) {
                    elements.customerResults.style.display = 'none';
                    return;
                }

                // Show loading state
                elements.customerResults.innerHTML = getSearchLoadingHTML();
                elements.customerResults.style.display = 'block';

                state.customerTimer = setTimeout(() => {
                    performCustomerSearch(query);
                }, 500);
            }

            function performCustomerSearch(query) {
                fetch(`{{ route('customers.ajax.search') }}?search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(customers => {
                        elements.customerResults.innerHTML = '';

                        if (customers.length === 0) {
                            elements.customerResults.innerHTML = getNoCustomersHTML();
                            elements.customerResults.style.display = 'block';
                            return;
                        }

                        customers.forEach((customer, index) => {
                            const customerElement = createCustomerElement(customer, index, customers
                                .length);
                            elements.customerResults.appendChild(customerElement);
                        });

                        elements.customerResults.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        elements.customerResults.innerHTML = getSearchErrorHTML();
                        elements.customerResults.style.display = 'block';
                    });
            }

            function createCustomerElement(customer, index, total) {
                const item = document.createElement('div');
                item.style.cssText = `
            padding: 14px 16px;
            cursor: pointer;
            border-bottom: ${index === total - 1 ? 'none' : '1px solid #f1f5f9'};
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        `;

                item.innerHTML = `
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #374151; margin-bottom: 4px; font-size: 15px;">
                    ${escapeHTML(customer.name)}
                </div>
                <div style="display: flex; gap: 12px; font-size: 13px; color: #64748b;">
                    <span>📱 ${escapeHTML(customer.mobile || 'No phone')}</span>
                    ${customer.email ? `<span>✉️ ${escapeHTML(customer.email)}</span>` : ''}
                </div>
            </div>
            <div style="
                background: #3b82f6;
                color: white;
                padding: 6px 12px;
                border-radius: 8px;
                font-weight: 600;
                font-size: 13px;
                white-space: nowrap;
            ">
                Select
            </div>
        `;

                item.onmouseover = () => {
                    item.style.background = '#f8fafc';
                };
                item.onmouseout = () => {
                    item.style.background = 'white';
                };
                item.onclick = () => selectCustomer(customer);

                return item;
            }

            function selectCustomer(customer) {
                // Update hidden input
                if (elements.customerIdInput) elements.customerIdInput.value = customer.id;
                if (elements.customerSearch) {
                    elements.customerSearch.value = customer.name;
                    elements.customerSearch.style.borderColor = '#10b981';
                }

                // Update selected customer display
                if (elements.selectedCustomerName) elements.selectedCustomerName.textContent = customer.name;
                if (elements.selectedCustomerMobileText) elements.selectedCustomerMobileText.textContent = customer
                    .mobile || 'Not provided';
                if (elements.selectedCustomerEmailText) elements.selectedCustomerEmailText.textContent = customer
                    .email || 'Not provided';

                // Show customer info and clear button
                if (elements.selectedCustomerInfo) elements.selectedCustomerInfo.style.display = 'block';
                if (elements.clearCustomerContainer) elements.clearCustomerContainer.style.display = 'block';

                // Hide search results
                if (elements.customerResults) elements.customerResults.style.display = 'none';

                // Update state
                state.isCustomerSelected = true;

                // Enable product search
                enableProductSearch();

                // Clear customer status
                if (elements.customerStatus) elements.customerStatus.textContent = '';

                // Focus on product search
                setTimeout(() => {
                    if (elements.productSearch) elements.productSearch.focus();
                }, 100);

                // Enable barcode scanner
                setTimeout(() => {
                    enableBarcodeScanner();
                }, 500);

                // Show success toast
                showToast(`Customer "${customer.name}" selected. Now you can add products.`, 'success');

                // Update UI state
                updateUIState();
            }

            function clearCustomerSelection() {
                // Clear customer data
                if (elements.customerSearch) {
                    elements.customerSearch.value = '';
                    elements.customerSearch.style.borderColor = '#d1d5db';
                }
                if (elements.customerIdInput) elements.customerIdInput.value = '';

                state.isCustomerSelected = false;

                // Hide customer info and clear button
                if (elements.selectedCustomerInfo) elements.selectedCustomerInfo.style.display = 'none';
                if (elements.clearCustomerContainer) elements.clearCustomerContainer.style.display = 'none';

                // Disable product search and clear products
                disableProductSearch();
                clearAllProducts();

                // Disable barcode scanner
                disableBarcodeScanner();

                // Focus back on customer search
                if (elements.customerSearch) elements.customerSearch.focus();

                // Show message
                if (elements.customerStatus) {
                    elements.customerStatus.textContent = 'Please select a customer first';
                    elements.customerStatus.style.color = '#dc2626';
                }

                // Update UI state
                updateUIState();

                showToast('Customer selection cleared', 'info');
            }

            function handleCustomerBlur() {
                setTimeout(() => {
                    if (state.isCustomerSelected && !isInputFieldActive()) {
                        enableBarcodeScanner();
                    }
                }, 200);
            }

            // ========== PRODUCT FUNCTIONS ==========
            function enableProductSearch() {
                if (elements.productSearch) {
                    elements.productSearch.disabled = false;
                    elements.productSearch.placeholder = "Type product name to search...";
                    elements.productSearch.style.background = 'white';
                    elements.productSearch.style.color = '#374151';
                    elements.productSearch.style.cursor = 'text';
                }
                if (elements.productSearchHint) {
                    elements.productSearchHint.textContent = 'Start typing to search products';
                    elements.productSearchHint.style.color = '#059669';
                }
                if (elements.productStatus) elements.productStatus.textContent = '';
            }

            function disableProductSearch() {
                if (elements.productSearch) {
                    elements.productSearch.disabled = true;
                    elements.productSearch.value = '';
                    elements.productSearch.placeholder = "First select a customer above...";
                    elements.productSearch.style.background = '#f3f4f6';
                    elements.productSearch.style.color = '#9ca3af';
                    elements.productSearch.style.cursor = 'not-allowed';
                }
                if (elements.productSearchHint) {
                    elements.productSearchHint.textContent = 'Select a customer first to enable product search';
                    elements.productSearchHint.style.color = '#6b7280';
                }
                if (elements.productStatus) elements.productStatus.textContent = 'Customer required';
            }

            function handleProductSearch() {
                if (!state.isCustomerSelected) {
                    showToast('Please select a customer first', 'error');
                    if (elements.customerSearch) elements.customerSearch.focus();
                    this.value = '';
                    return;
                }

                const val = this.value.toLowerCase().trim();
                if (elements.productResults) elements.productResults.innerHTML = '';

                if (!val) {
                    if (elements.productResults) elements.productResults.style.display = 'none';
                    return;
                }

                const filteredProducts = state.products.filter(p =>
                    (p.name && p.name.toLowerCase().includes(val)) ||
                    (p.product_code && p.product_code.toString().toLowerCase().includes(val))
                );

                const exactMatch = state.products.find(
                    p => p.product_code && p.product_code.toString().toLowerCase() === val
                );

                if (exactMatch) {
                    addProduct(exactMatch);
                    if (elements.productResults) elements.productResults.style.display = 'none';
                    if (elements.productSearch) elements.productSearch.value = '';
                    showToast(`Product added: ${exactMatch.name}`, 'success');
                    return;
                }

                if (filteredProducts.length === 0) {
                    if (elements.productResults) {
                        elements.productResults.innerHTML = `
                    <div style="padding: 20px; text-align: center; color: #94a3b8; font-style: italic;">
                        No products found matching "${escapeHTML(val)}"
                    </div>
                `;
                        elements.productResults.style.display = 'block';
                    }
                    return;
                }

                filteredProducts.forEach((p, index) => {
                    if (!elements.productResults) return;

                    const item = document.createElement('div');
                    item.style.cssText = `
                padding: 14px 16px;
                cursor: pointer;
                border-bottom: ${index === filteredProducts.length - 1 ? 'none' : '1px solid #f1f5f9'};
                transition: all 0.2s;
                display: flex;
                justify-content: space-between;
                align-items: center;
            `;

                    item.innerHTML = `
                <div>
                    <div style="font-weight: 600; color: #374151; margin-bottom: 4px;">${escapeHTML(p.name)}</div>
                    <div style="font-size: 13px; color: #64748b;">Code: ${escapeHTML(p.product_code || 'N/A')}</div>
                </div>
                <div style="
                    background: #10b981;
                    color: white;
                    padding: 6px 12px;
                    border-radius: 8px;
                    font-weight: 700;
                    font-size: 15px;
                ">
                    ₹${parseFloat(p.price || 0).toFixed(2)}
                </div>
            `;

                    item.onmouseover = () => {
                        item.style.background = '#f8fafc';
                    };
                    item.onmouseout = () => {
                        item.style.background = 'white';
                    };
                    item.onclick = () => addProduct(p);

                    elements.productResults.appendChild(item);
                });

                if (elements.productResults) elements.productResults.style.display = 'block';
            }

            function addProduct(p) {
                if (!state.isCustomerSelected) {
                    showToast('Please select a customer first', 'error');
                    if (elements.customerSearch) elements.customerSearch.focus();
                    return;
                }

                if (elements.productResults) elements.productResults.style.display = 'none';
                if (elements.productSearch) elements.productSearch.value = '';
                if (elements.emptyState) elements.emptyState.style.display = 'none';

                // Check if product already exists
                let existingRow = null;
                if (elements.itemsTable) {
                    document.querySelectorAll('#itemsTable tr[data-pid]').forEach(row => {
                        if (row.dataset.pid == p.id) {
                            existingRow = row;
                        }
                    });
                }

                if (existingRow) {
                    // Increment quantity
                    const qtyInput = existingRow.querySelector('.qty');
                    if (qtyInput) {
                        qtyInput.value = parseInt(qtyInput.value || 0) + 1;
                        existingRow.style.background = '#f0f9ff';
                        setTimeout(() => {
                            existingRow.style.background = '';
                        }, 300);
                    }
                } else {
                    // Add new row
                    const rowId = `product-row-${p.id}`;
                    if (elements.itemsTable) {
                        elements.itemsTable.insertAdjacentHTML('beforeend', getProductRowHTML(p, rowId));

                        setTimeout(() => {
                            const newRow = document.getElementById(rowId);
                            if (newRow) newRow.style.background = '';
                        }, 300);
                    }
                }

                calculate();
                updateUIState();
            }

            function getProductRowHTML(p, rowId) {
                return `
            <tr data-pid="${p.id}" id="${rowId}" style="
                border-bottom: 1px solid #e5e7eb;
                animation: slideIn 0.3s ease-out;
                background: #f8fafc;
            ">
                <td style="padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="
                            width: 40px;
                            height: 40px;
                            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                            border-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-weight: 600;
                            font-size: 14px;
                        ">${escapeHTML(p.name?.charAt(0) || 'P')}</div>
                        <div>
                            <div style="font-weight: 600; color: #374151;">${escapeHTML(p.name || 'Product')}</div>
                            <div style="font-size: 13px; color: #64748b;">PRD${p.id || ''}</div>
                        </div>
                    </div>
                    <input type="hidden" name="items[product_id][]" value="${escapeHTML(p.id)}">
                </td>
                <td style="padding: 20px;">
                    <input name="items[price][]" value="${parseFloat(p.price || 0).toFixed(2)}" oninput="InvoiceManager.calculate()" style="
                        width: 100%;
                        padding: 10px 12px;
                        border-radius: 8px;
                        border: 1.5px solid #e5e7eb;
                        background: white;
                        font-size: 15px;
                        color: #374151;
                        text-align: right;
                        outline: none;
                        font-weight: 600;
                    " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e5e7eb'">
                </td>
                <td style="padding: 20px;">
                    <input type="number" class="qty" name="items[quantity][]" value="1" min="1" oninput="InvoiceManager.calculate()" style="
                        width: 100%;
                        padding: 10px 12px;
                        border-radius: 8px;
                        border: 1.5px solid #e5e7eb;
                        background: white;
                        font-size: 15px;
                        color: #374151;
                        text-align: center;
                        outline: none;
                        font-weight: 600;
                    " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e5e7eb'">
                </td>
                <td style="padding: 20px;">
                    <input name="items[total][]" readonly value="${parseFloat(p.price || 0).toFixed(2)}" style="
                        width: 100%;
                        padding: 10px 12px;
                        border-radius: 8px;
                        border: 1.5px solid #e5e7eb;
                        background: #f1f5f9;
                        font-size: 15px;
                        color: #1e293b;
                        text-align: right;
                        font-weight: 700;
                    ">
                </td>
                <td style="padding: 20px;">
                    <button type="button" onclick="InvoiceManager.removeProduct('${rowId}')" style="
                        background: #fef2f2;
                        color: #dc2626;
                        border: 1.5px solid #fecaca;
                        padding: 8px 16px;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 13px;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        transition: all 0.2s;
                    " onmouseover="
                        this.style.background='#fee2e2';
                        this.style.borderColor='#fca5a5';
                    " onmouseout="
                        this.style.background='#fef2f2';
                        this.style.borderColor='#fecaca';
                    ">
                        <span>🗑️</span>
                        Remove
                    </button>
                </td>
            </tr>
        `;
            }

            function removeProduct(rowId) {
                const row = document.getElementById(rowId);
                if (row) {
                    row.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        row.remove();
                        if (elements.itemsTable && elements.itemsTable.children.length === 1) {
                            if (elements.emptyState) elements.emptyState.style.display = '';
                        }
                        calculate();
                        updateUIState();
                    }, 300);
                }
            }

            function clearAllProducts() {
                if (elements.itemsTable) {
                    document.querySelectorAll('#itemsTable tr[data-pid]').forEach(row => {
                        row.remove();
                    });
                }

                if (elements.emptyState) elements.emptyState.style.display = '';

                calculate();
                if (elements.itemsStatus) {
                    elements.itemsStatus.textContent = 'Add products after selecting customer';
                }
            }

            function handleProductBlur() {
                setTimeout(() => {
                    if (state.isCustomerSelected && !isInputFieldActive()) {
                        enableBarcodeScanner();
                    }
                }, 200);
            }

            // ========== BARCODE SCANNER FUNCTIONS ==========
            function enableBarcodeScanner() {
                state.isScannerEnabled = true;
                if (elements.barcodeInput) {
                    elements.barcodeInput.disabled = false;
                    elements.barcodeInput.value = '';
                }
                state.barcodeBuffer = '';

                if (!isInputFieldActive()) {
                    setTimeout(() => {
                        if (elements.barcodeInput) elements.barcodeInput.focus();
                    }, 100);
                }
            }

            function disableBarcodeScanner() {
                state.isScannerEnabled = false;
                if (elements.barcodeInput) {
                    elements.barcodeInput.disabled = true;
                    elements.barcodeInput.value = '';
                }
                state.barcodeBuffer = '';

                if (state.barcodeTimeout) {
                    clearTimeout(state.barcodeTimeout);
                    state.barcodeTimeout = null;
                }
            }

            function handleBarcodeInput(e) {
                if (!state.isScannerEnabled || !state.isCustomerSelected) return;

                state.barcodeBuffer += e.target.value;
                e.target.value = '';

                if (state.barcodeTimeout) {
                    clearTimeout(state.barcodeTimeout);
                }

                state.barcodeTimeout = setTimeout(() => {
                    if (state.barcodeBuffer.length > 0) {
                        processBarcode(state.barcodeBuffer.trim());
                        state.barcodeBuffer = '';
                    }
                }, 100);
            }

            function handleBarcodeKeydown(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();

                    if (!state.isScannerEnabled || !state.isCustomerSelected) {
                        showToast('Please select customer first', 'error');
                        e.target.value = '';
                        return;
                    }

                    const scannedCode = e.target.value.trim();
                    if (scannedCode) {
                        processBarcode(scannedCode);
                    }
                    e.target.value = '';
                }
            }

            function processBarcode(code) {
                if (!code || code.length === 0) return;

                const product = state.products.find(p =>
                    p.product_code && p.product_code.toString() === code.toString()
                );

                if (!product) {
                    showToast(`Product not found for code: ${code}`, 'error');
                    return;
                }

                addProduct(product);
                showToast(`Product added: ${product.name}`, 'success');
            }

            // ========== CALCULATION FUNCTIONS ==========
            function calculate() {
                let subTotal = 0;

                document.querySelectorAll('#itemsTable tr[data-pid]').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty')?.value) || 0;
                    const price = parseFloat(row.querySelector('[name="items[price][]"]')?.value) || 0;
                    const total = qty * price;

                    const totalInput = row.querySelector('[name="items[total][]"]');
                    if (totalInput) totalInput.value = total.toFixed(2);

                    subTotal += total;
                });

                const discount = parseFloat(document.getElementById('discount')?.value) || 0;
                const tax = parseFloat(document.getElementById('tax')?.value) || 0;
                const taxAmount = (subTotal * tax) / 100;
                const grandTotal = Math.max(0, subTotal - discount + taxAmount);

                const subTotalInput = document.getElementById('sub_total');
                if (subTotalInput) subTotalInput.value = subTotal.toFixed(2);

                const grandTotalInput = document.getElementById('grand_total');
                if (grandTotalInput) grandTotalInput.value = grandTotal.toFixed(2);
            }

            // ========== FORM SUBMISSION ==========
            function handleSubmit(event) {
                event.preventDefault();

                // Validation
                if (!state.isCustomerSelected) {
                    showToast('Please select a customer first', 'error');
                    if (elements.customerSearch) {
                        elements.customerSearch.focus();
                        elements.customerSearch.classList.add('shake');
                        setTimeout(() => elements.customerSearch.classList.remove('shake'), 500);
                    }
                    return false;
                }

                const hasProducts = document.querySelectorAll('#itemsTable tr[data-pid]').length > 0;
                if (!hasProducts) {
                    showToast('Please add at least one product', 'error');
                    if (elements.productSearch) {
                        elements.productSearch.focus();
                        elements.productSearch.classList.add('shake');
                        setTimeout(() => elements.productSearch.classList.remove('shake'), 500);
                    }
                    return false;
                }

                // Disable submit button and show loading state
                const btn = document.getElementById('saveBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `
                <span class="btn-icon spin">⏳</span>
                Processing...
            `;
                }

                // Show processing toast
                showToast('Creating invoice...', 'info');

                // Submit the form
                document.getElementById('invoiceForm').submit();

                return false; // Prevent default form submission since we're using event.preventDefault()
            }

            // ========== CUSTOMER MODAL FUNCTIONS ==========
            function openCustomerModal() {
                // Clear form fields
                ['c_name', 'c_mobile', 'c_email', 'c_address'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });

                if (elements.customerModal) elements.customerModal.style.display = 'flex';

                const nameInput = document.getElementById('c_name');
                if (nameInput) nameInput.focus();
            }

            function closeCustomerModal() {
                if (elements.customerModal) elements.customerModal.style.display = 'none';
            }

            function saveCustomer() {
                if (state.isSavingCustomer) return;

                const name = document.getElementById('c_name')?.value.trim();
                const mobile = document.getElementById('c_mobile')?.value.trim();
                const email = document.getElementById('c_email')?.value.trim();
                const address = document.getElementById('c_address')?.value.trim();

                if (!name) {
                    showToast('Customer name is required', 'error');
                    document.getElementById('c_name')?.focus();
                    return;
                }

                if (!mobile) {
                    showToast('Mobile number is required', 'error');
                    document.getElementById('c_mobile')?.focus();
                    return;
                }

                state.isSavingCustomer = true;

                // Update button state
                if (elements.saveCustomerBtn) {
                    elements.saveCustomerBtn.innerHTML = `
                <span style="
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    border: 2px solid rgba(255,255,255,0.3);
                    border-top-color: white;
                    border-radius: 50%;
                    animation: buttonSpin 0.6s linear infinite;
                "></span>
                Saving...
            `;
                    elements.saveCustomerBtn.disabled = true;
                }

                fetch("{{ route('customers.store.ajax') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({
                            name: name,
                            mobile: mobile,
                            email: email,
                            address: address
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.customer) {
                            closeCustomerModal();
                            selectCustomer(data.customer);
                            showToast('Customer added and selected! Now add products.', 'success');
                        } else {
                            showToast(data.message || 'Error saving customer', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Failed to save customer. Please try again.', 'error');
                    })
                    .finally(() => {
                        state.isSavingCustomer = false;
                        if (elements.saveCustomerBtn) {
                            elements.saveCustomerBtn.innerHTML = `<span>✓</span>Save Customer`;
                            elements.saveCustomerBtn.disabled = false;
                        }
                    });
            }

            // ========== UI UTILITIES ==========
            function updateUIState() {
                const hasProducts = document.querySelectorAll('#itemsTable tr[data-pid]').length > 0;

                if (!state.isCustomerSelected) {
                    if (elements.customerStatus) {
                        elements.customerStatus.textContent = 'Required';
                        elements.customerStatus.style.color = '#dc2626';
                    }
                    if (elements.itemsStatus) {
                        elements.itemsStatus.textContent = 'Select customer first';
                        elements.itemsStatus.style.color = '#dc2626';
                    }
                } else if (!hasProducts) {
                    if (elements.customerStatus) {
                        elements.customerStatus.textContent = '✅ Selected';
                        elements.customerStatus.style.color = '#059669';
                    }
                    if (elements.itemsStatus) {
                        elements.itemsStatus.textContent = 'No products added yet';
                        elements.itemsStatus.style.color = '#f59e0b';
                    }
                } else {
                    if (elements.customerStatus) {
                        elements.customerStatus.textContent = '✅ Selected';
                        elements.customerStatus.style.color = '#059669';
                    }
                    if (elements.itemsStatus) {
                        elements.itemsStatus.textContent = '';
                    }
                }
            }

            function isInputFieldActive() {
                const activeElement = document.activeElement;
                if (!activeElement) return false;

                const activeTag = activeElement.tagName.toLowerCase();
                const activeId = activeElement.id;

                return (
                    activeTag === 'input' ||
                    activeTag === 'textarea' ||
                    activeTag === 'select' ||
                    activeId === 'customerSearch' ||
                    activeId === 'productSearch' ||
                    activeElement.closest('#customerModal')
                );
            }

            function handleDocumentClick(e) {
                // Close customer results
                if (elements.customerResults &&
                    !elements.customerSearch?.contains(e.target) &&
                    !elements.customerResults.contains(e.target)) {
                    elements.customerResults.style.display = 'none';
                }

                // Close product results
                if (elements.productResults &&
                    !elements.productSearch?.contains(e.target) &&
                    !elements.productResults.contains(e.target)) {
                    elements.productResults.style.display = 'none';
                }
            }

            function handleDocumentMousedown(e) {
                if (isInputFieldActive() || e.target.closest('button') || e.target.closest('a')) {
                    return;
                }

                if (state.isCustomerSelected && state.isScannerEnabled && !isInputFieldActive()) {
                    setTimeout(() => {
                        if (elements.barcodeInput) elements.barcodeInput.focus();
                    }, 50);
                }
            }

            // ========== HELPER FUNCTIONS ==========
            function escapeHTML(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function getSearchLoadingHTML() {
                return `
            <div style="padding: 20px; text-align: center; color: #64748b;">
                <div style="
                    display: inline-block;
                    width: 20px;
                    height: 20px;
                    border: 2px solid #e5e7eb;
                    border-top-color: #3b82f6;
                    border-radius: 50%;
                    animation: spin 0.8s linear infinite;
                    margin-right: 10px;
                "></div>
                Searching customers...
            </div>
        `;
            }

            function getNoCustomersHTML() {
                return `
            <div style="
                padding: 30px 20px;
                text-align: center;
                color: #64748b;
                font-style: italic;
            ">
                <div style="font-size: 40px; margin-bottom: 10px;">👤</div>
                No customers found
                <div style="font-size: 13px; margin-top: 8px; color: #94a3b8;">
                    Try different keywords or add a new customer
                </div>
            </div>
        `;
            }

            function getSearchErrorHTML() {
                return `
            <div style="
                padding: 20px;
                text-align: center;
                color: #ef4444;
            ">
                <div style="font-size: 40px; margin-bottom: 10px;">⚠️</div>
                Search failed. Please try again.
            </div>
        `;
            }

            function showToast(message, type = 'success') {
                // Remove existing toast
                document.querySelectorAll('.toast-notification').forEach(el => el.remove());

                const toast = document.createElement('div');
                toast.className = 'toast-notification';

                const bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
                const icon = type === 'success' ? '✓' : type === 'error' ? '⚠' : 'ℹ';

                toast.style.background = bgColor;
                toast.innerHTML = `
            <span class="toast-icon">${icon}</span>
            <span class="toast-message">${escapeHTML(message)}</span>
        `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 3000);
            }

            // ========== PUBLIC API ==========
            return {
                init,
                selectCustomer,
                clearCustomerSelection,
                addProduct,
                removeProduct,
                calculate,
                handleSubmit,
                openCustomerModal,
                closeCustomerModal,
                saveCustomer,
                showToast
            };
        })();

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            InvoiceManager.init();
        });

        // Make InvoiceManager globally available
        window.InvoiceManager = InvoiceManager;
    </script>
@endsection
