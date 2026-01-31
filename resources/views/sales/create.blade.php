@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div
        style="
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            border-radius: 20px;
            max-width: 1300px;
            margin: 30px auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border: 1px solid #e5e7eb;
        ">

        {{-- INVOICE HEADER WITH CUSTOMER CLEAR BUTTON --}}
        <div
            style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                <div
                    style="
                        width: 60px;
                        height: 60px;
                        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                        border-radius: 16px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
                    ">
                    <span style="font-size: 28px; color: white;">🧾</span>
                </div>
                <div>
                    <h1
                        style="
                            font-size: 32px;
                            font-weight: 800;
                            margin: 0;
                            color: #1e293b;
                            letter-spacing: -0.5px;
                        ">
                        Create New Invoice</h1>
                    <p style="color: #64748b; margin: 5px 0 0; font-size: 15px;">
                        Step 1: Select customer → Step 2: Add products
                    </p>
                </div>
            </div>

            {{-- CLEAR CUSTOMER BUTTON (TOP RIGHT) --}}
            <div id="clearCustomerContainer" style="display: none;">
                <button type="button" onclick="clearCustomerSelection()"
                    style="
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
                    "
                    onmouseover="
                        this.style.background='#fee2e2';
                        this.style.borderColor='#fca5a5';
                        this.style.transform='translateY(-2px)';
                        this.style.boxShadow='0 6px 16px rgba(220, 38, 38, 0.15)';
                    "
                    onmouseout="
                        this.style.background='#fef2f2';
                        this.style.borderColor='#fecaca';
                        this.style.transform='translateY(0)';
                        this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.1)';
                    ">
                    <span style="font-size: 16px;">✕</span>
                    Clear Customer
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('sales.store') }}" onsubmit="return handleSubmit(this)">
            @csrf
            <input type="text" id="barcodeInput" autocomplete="off"
                style="
                    position: absolute;
                    opacity: 0;
                    height: 0;
                    width: 0;
                    pointer-events: none;
                ">

            <input type="hidden" name="invoice_token" value="{{ Str::uuid() }}">

            {{-- CUSTOMER + SEARCH SECTION --}}
            <div
                style="
                    background: white;
                    padding: 25px;
                    border-radius: 16px;
                    margin-bottom: 30px;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                ">
                <h3
                    style="
                        font-size: 18px;
                        font-weight: 700;
                        color: #374151;
                        margin: 0 0 20px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                    <span
                        style="
                            display: inline-flex;
                            width: 24px;
                            height: 24px;
                            background: #3b82f6;
                            color: white;
                            border-radius: 6px;
                            align-items: center;
                            justify-content: center;
                            font-size: 14px;
                        ">1</span>
                    Step 1: Select Customer (Required)
                </h3>

                {{-- SELECTED CUSTOMER INFO DISPLAY --}}
                <div id="selectedCustomerInfo"
                    style="
                    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                    border: 1.5px solid #0ea5e9;
                    border-radius: 12px;
                    padding: 16px 20px;
                    margin-bottom: 20px;
                    display: none;
                    animation: fadeIn 0.3s ease-out;
                ">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div
                                style="
                                width: 44px;
                                height: 44px;
                                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                                border-radius: 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <span style="font-size: 22px; color: white;">👤</span>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #0c4a6e; font-weight: 600; margin-bottom: 4px;">
                                    CUSTOMER SELECTED
                                </div>
                                <div id="selectedCustomerName" style="font-weight: 700; color: #1e293b; font-size: 16px;">
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; font-size: 13px; color: #475569;">
                            <div id="selectedCustomerMobile" style="display: flex; align-items: center; gap: 4px;">
                                <span>📱</span>
                                <span id="selectedCustomerMobileText">Loading...</span>
                            </div>
                            <div id="selectedCustomerEmail" style="display: flex; align-items: center; gap: 4px;">
                                <span>✉️</span>
                                <span id="selectedCustomerEmailText">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    {{-- Customer Selection --}}
                    <div style="position: relative;">
                        <label
                            style="
                                display: block;
                                color: #374151;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">
                            Select Customer
                            <span style="color: #ef4444; font-weight: bold;">*</span>
                            <span id="customerStatus"
                                style="font-size: 12px; color: #dc2626; margin-left: 8px; font-weight: normal;"></span>
                        </label>
                        <div style="display: flex; gap: 12px;">
                            <div style="position: relative; flex: 1;">
                                <input type="text" id="customerSearch"
                                    placeholder="Type customer name or mobile to search..." autocomplete="off"
                                    style="
                                        width: 90%;
                                        padding: 12px 16px;
                                        border-radius: 10px;
                                        border: 1.5px solid #d1d5db;
                                        background: white;
                                        font-size: 15px;
                                        color: #374151;
                                        transition: all 0.2s;
                                        outline: none;
                                    "
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">

                                <input type="hidden" name="customer_id" id="customer_id">

                                <div id="customerResults"
                                    style="
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
                                    ">
                                </div>
                            </div>

                            <button type="button" onclick="openCustomerModal()"
                                style="
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
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.3)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.25)'">
                                <span style="font-size: 16px;">+</span>
                                Add New
                            </button>
                        </div>
                    </div>

                    {{-- Product Search --}}
                    <div>
                        <label
                            style="
                                display: block;
                                color: #374151;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">
                            Step 2: Search Products
                            <span id="productStatus"
                                style="font-size: 12px; color: #dc2626; margin-left: 8px; font-weight: normal;"></span>
                        </label>
                        <div style="position: relative;">
                            <input type="text" id="productSearch" disabled placeholder="First select a customer above..."
                                onkeydown="return event.key !== 'Enter'"
                                style="
                                    width: 92%;
                                    padding: 12px 16px;
                                    padding-right: 40px;
                                    border-radius: 12px;
                                    border: 1.5px solid #e5e7eb;
                                    background: #f3f4f6;
                                    font-size: 15px;
                                    color: #9ca3af;
                                    transition: all 0.2s;
                                    outline: none;
                                    cursor: not-allowed;
                                ">
                            <span
                                style="
                                    position: absolute;
                                    right: 16px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #9ca3af;
                                    font-size: 18px;
                                ">🔍</span>
                            <div id="productResults"
                                style="
                                    display: none;
                                    position: absolute;
                                    top: calc(100% + 8px);
                                    width: 100%;
                                    background: white;
                                    border: 1.5px solid #e5e7eb;
                                    border-radius: 12px;
                                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                                    max-height: 300px;
                                    overflow-y: auto;
                                    z-index: 1000;
                                ">
                            </div>
                        </div>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 6px;">
                            <span id="productSearchHint">Select a customer first to enable product search</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rest of the code remains the same... --}}
            {{-- ITEMS TABLE SECTION --}}
            <div
                style="
                    background: white;
                    padding: 25px;
                    border-radius: 16px;
                    margin-bottom: 30px;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                    overflow: hidden;
                ">
                <h3
                    style="
                        font-size: 18px;
                        font-weight: 700;
                        color: #374151;
                        margin: 0 0 20px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                    <span
                        style="
                            display: inline-flex;
                            width: 24px;
                            height: 24px;
                            background: #f59e0b;
                            color: white;
                            border-radius: 6px;
                            align-items: center;
                            justify-content: center;
                            font-size: 14px;
                        ">2</span>
                    Invoice Items
                    <span id="itemsStatus"
                        style="font-size: 14px; color: #dc2626; margin-left: 8px; font-weight: normal;"></span>
                </h3>

                <div style="overflow-x: auto; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr
                                style="
                                    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                                    color: #1e40af;
                                    border-bottom: 2px solid #dbeafe;
                                ">
                                <th
                                    style="
                                        padding: 16px 20px;
                                        text-align: left;
                                        font-weight: 700;
                                        font-size: 14px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        color: #374151;
                                    ">
                                    Product</th>
                                <th
                                    style="
                                        padding: 16px 20px;
                                        text-align: left;
                                        font-weight: 700;
                                        font-size: 14px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        color: #374151;
                                    ">
                                    Price (₹)</th>
                                <th
                                    style="
                                        padding: 16px 20px;
                                        text-align: left;
                                        font-weight: 700;
                                        font-size: 14px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        color: #374151;
                                    ">
                                    Quantity</th>
                                <th
                                    style="
                                        padding: 16px 20px;
                                        text-align: left;
                                        font-weight: 700;
                                        font-size: 14px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        color: #374151;
                                    ">
                                    Total (₹)</th>
                                <th
                                    style="
                                        padding: 16px 20px;
                                        text-align: left;
                                        font-weight: 700;
                                        font-size: 14px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        color: #374151;
                                    ">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTable"
                            style="
                                background: #fafafa;
                                border-bottom: 1px solid #e5e7eb;
                            ">
                            <tr id="emptyState"
                                style="
                                    background: #f8fafc;
                                    text-align: center;
                                    color: #64748b;
                                    font-style: italic;
                                ">
                                <td colspan="5" style="padding: 60px 20px;">
                                    <div
                                        style="
                                            display: flex;
                                            flex-direction: column;
                                            align-items: center;
                                            gap: 12px;
                                            color: #94a3b8;
                                        ">
                                        <span style="font-size: 48px;">📦</span>
                                        <p style="margin: 0; font-size: 15px;">
                                            Select a customer first, then search and add products
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTALS SECTION --}}
            <div
                style="
                    background: white;
                    padding: 25px;
                    border-radius: 16px;
                    margin-bottom: 30px;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                ">
                <h3
                    style="
                        font-size: 18px;
                        font-weight: 700;
                        color: #374151;
                        margin: 0 0 20px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                    <span
                        style="
                            display: inline-flex;
                            width: 24px;
                            height: 24px;
                            background: #10b981;
                            color: white;
                            border-radius: 6px;
                            align-items: center;
                            justify-content: center;
                            font-size: 14px;
                        ">3</span>
                    Invoice Summary
                </h3>

                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <div>
                        <label
                            style="
                                display: block;
                                color: #64748b;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">Sub
                            Total</label>
                        <div style="position: relative;">
                            <span
                                style="
                                    position: absolute;
                                    left: 16px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #374151;
                                    font-weight: 600;
                                    font-size: 18px;
                                ">₹</span>
                            <input id="sub_total" name="sub_total" readonly value="0.00"
                                style="
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
                                ">
                        </div>
                    </div>

                    <div>
                        <label
                            style="
                                display: block;
                                color: #64748b;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">Discount
                            (₹)</label>
                        <div style="position: relative;">
                            <span
                                style="
                                    position: absolute;
                                    left: 16px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #374151;
                                    font-weight: 600;
                                    font-size: 18px;
                                ">₹</span>
                            <input id="discount" name="discount" value="0" oninput="calculate()"
                                style="
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
                                "
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <div>
                        <label
                            style="
                                display: block;
                                color: #64748b;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">Tax
                            (%)</label>
                        <div style="position: relative;">
                            <span
                                style="
                                    position: absolute;
                                    left: 16px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #374151;
                                    font-weight: 600;
                                    font-size: 18px;
                                ">%</span>
                            <input id="tax" name="tax" value="0" oninput="calculate()"
                                style="
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
                                "
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <div>
                        <label
                            style="
                                display: block;
                                color: #1e293b;
                                font-weight: 700;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">Grand
                            Total</label>
                        <div style="position: relative;">
                            <span
                                style="
                                    position: absolute;
                                    left: 16px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #1e293b;
                                    font-weight: 700;
                                    font-size: 18px;
                                ">₹</span>
                            <input id="grand_total" name="grand_total" readonly value="0.00"
                                style="
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
                                ">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div style="text-align: right;">
                <button type="submit" id="saveBtn"
                    style="
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
                    "
                    onmouseover="
                        this.style.transform='translateY(-3px)';
                        this.style.boxShadow='0 12px 25px rgba(0, 0, 0, 0.25)';
                    "
                    onmouseout="
                        this.style.transform='translateY(0)';
                        this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.2)';
                    ">
                    <span
                        style="
                            background: rgba(255, 255, 255, 0.2);
                            width: 36px;
                            height: 36px;
                            border-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 18px;
                        ">💾</span>
                    Save & Generate Invoice
                </button>
            </div>
        </form>
    </div>

    {{-- CUSTOMER MODAL (same as before) --}}
    <div id="customerModal"
        style="
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        ">
        <div
            style="
                background: white;
                width: 100%;
                max-width: 500px;
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                animation: modalSlideIn 0.3s ease-out;
                border: 1px solid #e5e7eb;
            ">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 25px;">
                <div
                    style="
                        width: 48px;
                        height: 48px;
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                    <span style="font-size: 24px; color: white;">👤</span>
                </div>
                <div>
                    <h3
                        style="
                            font-size: 22px;
                            font-weight: 700;
                            color: #1e293b;
                            margin: 0;
                            letter-spacing: -0.5px;
                        ">
                        Add New Customer</h3>
                    <p style="color: #64748b; margin: 4px 0 0; font-size: 14px;">
                        Fill customer details below
                    </p>
                </div>
            </div>

            <div style="display: grid; gap: 16px;">
                <div>
                    <label
                        style="
                            display: block;
                            color: #374151;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Full
                        Name <span style="color: #ef4444;">*</span></label>
                    <input id="c_name" placeholder="Enter customer name"
                        style="
                            width: 100%;
                            padding: 14px 16px;
                            border-radius: 12px;
                            border: 1.5px solid #e5e7eb;
                            background: white;
                            font-size: 15px;
                            color: #374151;
                            outline: none;
                            transition: all 0.2s;
                        "
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label
                            style="
                                display: block;
                                color: #374151;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">Mobile
                            <span style="color: #ef4444;">*</span></label>
                        <input id="c_mobile" placeholder="Enter mobile number"
                            style="
                                width: 100%;
                                padding: 14px 16px;
                                border-radius: 12px;
                                border: 1.5px solid #e5e7eb;
                                background: white;
                                font-size: 15px;
                                color: #374151;
                                outline: none;
                                transition: all 0.2s;
                            "
                            onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label
                            style="
                                display: block;
                                color: #374151;
                                font-weight: 600;
                                margin-bottom: 8px;
                                font-size: 14px;
                            ">Email</label>
                        <input id="c_email" placeholder="Enter email address"
                            style="
                                width: 100%;
                                padding: 14px 16px;
                                border-radius: 12px;
                                border: 1.5px solid #e5e7eb;
                                background: white;
                                font-size: 15px;
                                color: #374151;
                                outline: none;
                                transition: all 0.2s;
                            "
                            onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div>
                    <label
                        style="
                            display: block;
                            color: #374151;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Address</label>
                    <textarea id="c_address" placeholder="Enter customer address" rows="3"
                        style="
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
                        "
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"></textarea>
                </div>
            </div>

            <div
                style="
                    display: flex;
                    gap: 12px;
                    margin-top: 30px;
                    padding-top: 25px;
                    border-top: 1px solid #e5e7eb;
                    justify-content: flex-end;
                ">
                <button onclick="closeCustomerModal()"
                    style="
                        background: #f3f4f6;
                        color: #374151;
                        border: 1.5px solid #e5e7eb;
                        padding: 12px 24px;
                        border-radius: 10px;
                        font-weight: 600;
                        font-size: 15px;
                        cursor: pointer;
                        transition: all 0.2s;
                    "
                    onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    Cancel
                </button>
                <button onclick="saveCustomer()" id="saveCustomerBtn"
                    style="
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
                    "
                    onmouseover="
                        this.style.transform='translateY(-2px)';
                        this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.3)';
                    "
                    onmouseout="
                        this.style.transform='translateY(0)';
                        this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.25)';
                    ">
                    <span style="font-size: 16px;">✓</span>
                    Save Customer
                </button>
            </div>
        </div>
    </div>

    <style>
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
    </style>

    {{-- ================= JS ================= --}}
    <script>
        let products = @json($products);
        let isSavingCustomer = false;
        let customerTimer = null;
        let isCustomerSelected = false;

        // Barcode scanner variables
        let isScannerEnabled = false;
        let barcodeBuffer = '';
        let barcodeTimeout = null;

        const customerSearch = document.getElementById('customerSearch');
        const customerResults = document.getElementById('customerResults');
        const customerIdInput = document.getElementById('customer_id');
        const customerStatus = document.getElementById('customerStatus');

        // Customer info elements
        const selectedCustomerInfo = document.getElementById('selectedCustomerInfo');
        const selectedCustomerName = document.getElementById('selectedCustomerName');
        const selectedCustomerMobileText = document.getElementById('selectedCustomerMobileText');
        const selectedCustomerEmailText = document.getElementById('selectedCustomerEmailText');
        const clearCustomerContainer = document.getElementById('clearCustomerContainer');

        const productSearch = document.getElementById('productSearch');
        const productResults = document.getElementById('productResults');
        const productStatus = document.getElementById('productStatus');
        const productSearchHint = document.getElementById('productSearchHint');

        const itemsTable = document.getElementById('itemsTable');
        const emptyState = document.getElementById('emptyState');
        const itemsStatus = document.getElementById('itemsStatus');
        const saveCustomerBtn = document.getElementById('saveCustomerBtn');

        const barcodeInput = document.getElementById('barcodeInput');

        // ========== BARCODE SCANNER FUNCTIONS ==========
        function enableBarcodeScanner() {
            isScannerEnabled = true;
            barcodeInput.disabled = false;
            barcodeInput.value = '';
            barcodeBuffer = '';

            if (!isInputFieldActive()) {
                setTimeout(() => {
                    barcodeInput.focus();
                }, 100);
            }
            console.log('Barcode scanner enabled');
        }

        function disableBarcodeScanner() {
            isScannerEnabled = false;
            barcodeInput.disabled = true;
            barcodeInput.value = '';
            barcodeBuffer = '';
            if (barcodeTimeout) {
                clearTimeout(barcodeTimeout);
                barcodeTimeout = null;
            }
            console.log('Barcode scanner disabled');
        }

        function isInputFieldActive() {
            const activeElement = document.activeElement;
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

        barcodeInput.addEventListener('input', function(e) {
            if (!isScannerEnabled || !isCustomerSelected) return;

            barcodeBuffer += this.value;
            this.value = '';

            if (barcodeTimeout) {
                clearTimeout(barcodeTimeout);
            }

            barcodeTimeout = setTimeout(() => {
                if (barcodeBuffer.length > 0) {
                    processBarcode(barcodeBuffer.trim());
                    barcodeBuffer = '';
                }
            }, 100);
        });

        barcodeInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                if (!isScannerEnabled || !isCustomerSelected) {
                    showToast('Please select customer first', 'error');
                    this.value = '';
                    return;
                }

                const scannedCode = this.value.trim();
                if (scannedCode) {
                    processBarcode(scannedCode);
                }
                this.value = '';
            }
        });

        function processBarcode(code) {
            if (!code || code.length === 0) return;

            const product = products.find(p =>
                p.product_code && p.product_code.toString() === code.toString()
            );

            if (!product) {
                showToast(`Product not found for code: ${code}`, 'error');
                return;
            }

            addProduct(product);
            showToast(`Product added: ${product.name}`, 'success');
        }

        // ========== CUSTOMER FUNCTIONS ==========
        function selectCustomer(customer) {
            // Set customer data
            customerSearch.value = customer.name; // Keep customer name in search bar
            customerIdInput.value = customer.id;

            // Update selected customer display
            selectedCustomerName.textContent = customer.name;
            selectedCustomerMobileText.textContent = customer.mobile || 'Not provided';
            selectedCustomerEmailText.textContent = customer.email || 'Not provided';

            // Show customer info and clear button
            selectedCustomerInfo.style.display = 'block';
            clearCustomerContainer.style.display = 'block';

            // Hide search results
            customerResults.style.display = 'none';

            // Update state
            isCustomerSelected = true;

            // Enable product search
            enableProductSearch();

            // Clear customer status
            customerStatus.textContent = '';

            // Show success feedback on search bar
            customerSearch.style.borderColor = '#10b981';

            // Focus on product search
            setTimeout(() => {
                productSearch.focus();
            }, 100);

            // Enable barcode scanner
            setTimeout(() => {
                enableBarcodeScanner();
            }, 500);

            // Show toast
            showToast(`Customer "${customer.name}" selected. Now you can add products.`, 'success');

            // Update UI state
            updateUIState();
        }

        function clearCustomerSelection() {
            // Clear customer data
            customerSearch.value = '';
            customerIdInput.value = '';
            isCustomerSelected = false;

            // Hide customer info and clear button
            selectedCustomerInfo.style.display = 'none';
            clearCustomerContainer.style.display = 'none';

            // Disable product search
            disableProductSearch();

            // Clear all products from table
            clearAllProducts();

            // Disable barcode scanner
            disableBarcodeScanner();

            // Reset search bar border
            customerSearch.style.borderColor = '#d1d5db';

            // Focus back on customer search
            customerSearch.focus();

            // Show message
            customerStatus.textContent = 'Please select a customer first';
            customerStatus.style.color = '#dc2626';

            // Update UI state
            updateUIState();

            showToast('Customer selection cleared', 'info');
        }

        function enableProductSearch() {
            productSearch.disabled = false;
            productSearch.placeholder = "Type product name to search...";
            productSearch.style.background = 'white';
            productSearch.style.color = '#374151';
            productSearch.style.cursor = 'text';
            productSearchHint.textContent = 'Start typing to search products';
            productSearchHint.style.color = '#059669';
            productStatus.textContent = '';
        }

        function disableProductSearch() {
            productSearch.disabled = true;
            productSearch.value = '';
            productSearch.placeholder = "First select a customer above...";
            productSearch.style.background = '#f3f4f6';
            productSearch.style.color = '#9ca3af';
            productSearch.style.cursor = 'not-allowed';
            productSearchHint.textContent = 'Select a customer first to enable product search';
            productSearchHint.style.color = '#6b7280';
            productStatus.textContent = 'Customer required';
        }

        function clearAllProducts() {
            document.querySelectorAll('#itemsTable tr[data-pid]').forEach(row => {
                row.remove();
            });

            if (emptyState) emptyState.style.display = '';

            calculate();
            itemsStatus.textContent = 'Add products after selecting customer';
        }

        // ========== CUSTOMER SEARCH FUNCTIONALITY ==========
        customerSearch.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(customerTimer);

            if (query.length < 2) {
                customerResults.style.display = 'none';
                return;
            }

            customerResults.innerHTML = `
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
            customerResults.style.display = 'block';

            customerTimer = setTimeout(() => {
                fetch(`{{ route('customers.ajax.search') }}?search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(customers => {
                        customerResults.innerHTML = '';

                        if (customers.length === 0) {
                            customerResults.innerHTML = `
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
                            customerResults.style.display = 'block';
                            return;
                        }

                        customers.forEach((customer, index) => {
                            const item = document.createElement('div');
                            item.style.cssText = `
                                padding: 14px 16px;
                                cursor: pointer;
                                border-bottom: ${index === customers.length - 1 ? 'none' : '1px solid #f1f5f9'};
                                transition: all 0.2s;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            `;

                            item.innerHTML = `
                                <div style="flex: 1;">
                                    <div style="
                                        font-weight: 600;
                                        color: #374151;
                                        margin-bottom: 4px;
                                        font-size: 15px;
                                    ">
                                        ${customer.name}
                                    </div>
                                    <div style="
                                        display: flex;
                                        gap: 12px;
                                        font-size: 13px;
                                        color: #64748b;
                                    ">
                                        <span>📱 ${customer.mobile || 'No phone'}</span>
                                        ${customer.email ? `<span>✉️ ${customer.email}</span>` : ''}
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

                            customerResults.appendChild(item);
                        });

                        customerResults.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        customerResults.innerHTML = `
                            <div style="
                                padding: 20px;
                                text-align: center;
                                color: #ef4444;
                            ">
                                <div style="font-size: 40px; margin-bottom: 10px;">⚠️</div>
                                Search failed. Please try again.
                            </div>
                        `;
                        customerResults.style.display = 'block';
                    });
            }, 500);
        });

        // ========== PRODUCT SEARCH ==========
        productSearch.addEventListener('input', function() {
            if (!isCustomerSelected) {
                showToast('Please select a customer first', 'error');
                customerSearch.focus();
                this.value = '';
                return;
            }

            let val = this.value.toLowerCase().trim();
            productResults.innerHTML = '';

            if (!val) {
                productResults.style.display = 'none';
                return;
            }

            const filteredProducts = products.filter(p =>
                p.name.toLowerCase().includes(val) ||
                (p.product_code && p.product_code.toLowerCase().includes(val))
            );

            const exactMatch = products.find(
                p => p.product_code && p.product_code.toLowerCase() === val
            );

            if (exactMatch) {
                addProduct(exactMatch);
                productResults.style.display = 'none';
                productSearch.value = '';
                showToast(`Product added: ${exactMatch.name}`, 'success');
                return;
            }

            if (filteredProducts.length === 0) {
                productResults.innerHTML = `
                    <div style="
                        padding: 20px;
                        text-align: center;
                        color: #94a3b8;
                        font-style: italic;
                    ">
                        No products found matching "${val}"
                    </div>
                `;
                productResults.style.display = 'block';
                return;
            }

            filteredProducts.forEach((p, index) => {
                let item = document.createElement('div');
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
                        <div style="font-weight: 600; color: #374151; margin-bottom: 4px;">${p.name}</div>
                        <div style="font-size: 13px; color: #64748b;">Code: ${p.product_code}</div>
                    </div>
                    <div style="
                        background: #10b981;
                        color: white;
                        padding: 6px 12px;
                        border-radius: 8px;
                        font-weight: 700;
                        font-size: 15px;
                    ">
                        ₹${parseFloat(p.price).toFixed(2)}
                    </div>
                `;
                item.onmouseover = () => {
                    item.style.background = '#f8fafc';
                };
                item.onmouseout = () => {
                    item.style.background = 'white';
                };
                item.onclick = () => addProduct(p);
                productResults.appendChild(item);
            });

            productResults.style.display = 'block';
        });

        // Add product to table
        function addProduct(p) {
            if (!isCustomerSelected) {
                showToast('Please select a customer first', 'error');
                customerSearch.focus();
                return;
            }

            productResults.style.display = 'none';
            productSearch.value = '';

            if (emptyState) emptyState.style.display = 'none';

            let existingRow = null;
            document.querySelectorAll('#itemsTable tr').forEach(row => {
                if (row.dataset.pid == p.id) {
                    existingRow = row;
                }
            });

            if (existingRow) {
                let qtyInput = existingRow.querySelector('.qty');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                existingRow.style.background = '#f0f9ff';
                setTimeout(() => {
                    existingRow.style.background = '';
                }, 300);
            } else {
                const rowId = `product-row-${p.id}`;
                itemsTable.insertAdjacentHTML('beforeend', `
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
                                ">${p.name.charAt(0)}</div>
                                <div>
                                    <div style="font-weight: 600; color: #374151;">${p.name}</div>
                                    <div style="font-size: 13px; color: #64748b;">PRD00${p.id || 'N/A'}</div>
                                </div>
                            </div>
                            <input type="hidden" name="items[product_id][]" value="${p.id}">
                        </td>
                        <td style="padding: 20px;">
                            <input name="items[price][]" value="${parseFloat(p.price).toFixed(2)}" oninput="calculate()" style="
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
                            <input type="number" class="qty" name="items[quantity][]" value="1" min="1" oninput="calculate()" style="
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
                            <input name="items[total][]" readonly value="${parseFloat(p.price).toFixed(2)}" style="
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
                            <button type="button" onclick="removeProduct('${rowId}')" style="
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
                `);

                setTimeout(() => {
                    const newRow = document.getElementById(rowId);
                    if (newRow) newRow.style.background = '';
                }, 300);
            }

            calculate();
            updateUIState();
        }

        function removeProduct(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    row.remove();
                    if (itemsTable.children.length === 1) {
                        emptyState.style.display = '';
                    }
                    calculate();
                    updateUIState();
                }, 300);
            }
        }

        function updateUIState() {
            const hasProducts = document.querySelectorAll('#itemsTable tr[data-pid]').length > 0;

            if (!isCustomerSelected) {
                customerStatus.textContent = 'Required';
                customerStatus.style.color = '#dc2626';
                itemsStatus.textContent = 'Select customer first';
                itemsStatus.style.color = '#dc2626';
            } else if (!hasProducts) {
                customerStatus.textContent = '✅ Selected';
                customerStatus.style.color = '#059669';
                itemsStatus.textContent = 'No products added yet';
                itemsStatus.style.color = '#f59e0b';
            } else {
                customerStatus.textContent = '✅ Selected';
                customerStatus.style.color = '#059669';
                itemsStatus.textContent = '';
            }
        }

        function calculate() {
            let subTotal = 0;
            document.querySelectorAll('#itemsTable tr[data-pid]').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const price = parseFloat(row.querySelector('[name="items[price][]"]').value) || 0;
                const total = qty * price;
                row.querySelector('[name="items[total][]"]').value = total.toFixed(2);
                subTotal += total;
            });

            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const tax = parseFloat(document.getElementById('tax').value) || 0;
            const taxAmount = (subTotal * tax) / 100;
            const grandTotal = subTotal - discount + taxAmount;

            document.getElementById('sub_total').value = subTotal.toFixed(2);
            document.getElementById('grand_total').value = grandTotal.toFixed(2);
        }

        function handleSubmit(form) {
            if (!isCustomerSelected) {
                showToast('Please select a customer first', 'error');
                customerSearch.focus();
                customerSearch.classList.add('shake');
                setTimeout(() => customerSearch.classList.remove('shake'), 500);
                return false;
            }

            const hasProducts = document.querySelectorAll('#itemsTable tr[data-pid]').length > 0;
            if (!hasProducts) {
                showToast('Please add at least one product', 'error');
                productSearch.focus();
                productSearch.classList.add('shake');
                setTimeout(() => productSearch.classList.remove('shake'), 500);
                return false;
            }

            const btn = document.getElementById('saveBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `
                <span style="
                    background: rgba(255, 255, 255, 0.2);
                    width: 36px;
                    height: 36px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    animation: spin 1s linear infinite;
                ">⏳</span>
                Processing...
            `;

            return true;
        }

        // ========== EVENT LISTENERS ==========
        customerSearch.addEventListener('focus', () => {
            disableBarcodeScanner();
        });

        productSearch.addEventListener('focus', () => {
            disableBarcodeScanner();
        });

        customerSearch.addEventListener('blur', () => {
            setTimeout(() => {
                if (isCustomerSelected && !isInputFieldActive()) {
                    enableBarcodeScanner();
                }
            }, 200);
        });

        productSearch.addEventListener('blur', () => {
            setTimeout(() => {
                if (isCustomerSelected && !isInputFieldActive()) {
                    enableBarcodeScanner();
                }
            }, 200);
        });

        document.addEventListener('click', (e) => {
            if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
                customerResults.style.display = 'none';
            }
            if (!productSearch.contains(e.target) && !productResults.contains(e.target)) {
                productResults.style.display = 'none';
            }
        });

        document.addEventListener('mousedown', (e) => {
            if (isInputFieldActive() || e.target.closest('button') || e.target.closest('a')) {
                return;
            }

            if (isCustomerSelected && isScannerEnabled && !isInputFieldActive()) {
                setTimeout(() => {
                    barcodeInput.focus();
                }, 50);
            }
        });

        // ========== CUSTOMER MODAL FUNCTIONS ==========
        function openCustomerModal() {
            ['c_name', 'c_mobile', 'c_email', 'c_address'].forEach(id => {
                document.getElementById(id).value = '';
            });

            document.getElementById('customerModal').style.display = 'flex';
            document.getElementById('c_name').focus();
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').style.display = 'none';
        }

        function saveCustomer() {
            if (isSavingCustomer) return;

            const name = document.getElementById('c_name').value.trim();
            const mobile = document.getElementById('c_mobile').value.trim();
            const email = document.getElementById('c_email').value.trim();
            const address = document.getElementById('c_address').value.trim();

            if (!name) {
                showToast('Customer name is required', 'error');
                document.getElementById('c_name').focus();
                return;
            }
            if (!mobile) {
                showToast('Mobile number is required', 'error');
                document.getElementById('c_mobile').focus();
                return;
            }

            isSavingCustomer = true;
            const originalText = saveCustomerBtn.innerHTML;
            saveCustomerBtn.innerHTML = `
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
            saveCustomerBtn.disabled = true;

            fetch("{{ route('customers.store.ajax') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
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
                    isSavingCustomer = false;
                    saveCustomerBtn.innerHTML = originalText;
                    saveCustomerBtn.disabled = false;
                });
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 30px;
                right: 30px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                font-weight: 600;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideInRight 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 300px;
                max-width: 400px;
            `;
            toast.innerHTML = `
                <span style="font-size: 20px;">${type === 'success' ? '✓' : type === 'error' ? '⚠' : 'ℹ'}</span>
                <span style="flex: 1;">${message}</span>
            `;

            document.querySelectorAll('.toast-notification').forEach(el => el.remove());
            toast.className = 'toast-notification';

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            disableBarcodeScanner();
            updateUIState();
        });

        // Add animation styles
        const animationStyle = document.createElement('style');
        animationStyle.innerHTML = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
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
            @keyframes buttonSpin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
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
        `;
        document.head.appendChild(animationStyle);
    </script>
@endsection
