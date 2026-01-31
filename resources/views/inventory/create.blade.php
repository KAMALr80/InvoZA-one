@extends('layouts.app')

@section('content')
<div style="
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    margin-left: 260px;
    margin-top: 70px;
">

    @if(auth()->user()->role !== 'admin')
    <!-- Unauthorized Access Card -->
    <div style="
        max-width: 500px;
        width: 100%;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        text-align: center;
        border: 1px solid #e5e7eb;
    ">
        <div style="
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px auto;
            font-size: 32px;
        ">
            🚫
        </div>

        <h2 style="
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        ">
            Access Restricted
        </h2>

        <p style="
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        ">
            Only administrators have permission to add new products to the inventory.
            Please contact your system administrator for access.
        </p>

        <a href="{{ route('inventory.index') }}" style="
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
        "
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.35)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.25)';">
            <span>←</span>
            Back to Inventory
        </a>
    </div>

    @else
    <!-- Create Product Form -->
    <div style="
        max-width: 900px;
        width: 100%;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    ">
        <!-- Form Header -->
        <div style="
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        ">
            <!-- Background pattern -->
            <div style="
                position: absolute;
                right: -40px;
                top: -40px;
                width: 200px;
                height: 200px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                transform: rotate(45deg);
            "></div>
            <div style="
                position: absolute;
                left: -60px;
                bottom: -60px;
                width: 180px;
                height: 180px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
            "></div>

            <div style="
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 10px;
                position: relative;
                z-index: 1;
            ">
                <div style="
                    background: rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(10px);
                    width: 70px;
                    height: 70px;
                    border-radius: 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 32px;
                ">
                    ➕
                </div>
                <div>
                    <h2 style="
                        margin: 0;
                        font-size: 32px;
                        font-weight: 800;
                    ">
                        Add New Product
                    </h2>
                    <p style="
                        margin: 5px 0 0 0;
                        opacity: 0.9;
                        font-size: 16px;
                    ">
                        Add a new item to your inventory
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form method="POST" action="{{ route('inventory.store') }}" style="padding: 40px;">
            @csrf

            <!-- Two Column Layout -->
            <div style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                margin-bottom: 40px;
            ">
                <!-- Left Column - Basic Info -->
                <div>
                    <h3 style="
                        margin: 0 0 20px 0;
                        font-size: 20px;
                        font-weight: 700;
                        color: #1f2937;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    ">
                        <span style="
                            background: #10b981;
                            width: 6px;
                            height: 20px;
                            border-radius: 3px;
                            display: inline-block;
                        "></span>
                        Basic Information
                    </h3>

                    <!-- Product Name -->
                    <div style="margin-bottom: 25px;">
                        <label style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <span style="
                                background: #dbeafe;
                                width: 24px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #3b82f6;
                            ">
                                📝
                            </span>
                            Product Name *
                        </label>
                        <input type="text"
                               name="name"
                               required
                               placeholder="Enter product name"
                               style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                               "
                               onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    </div>

                   

                    <!-- Category -->
                    <div style="margin-bottom: 25px;">
                        <label style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <span style="
                                background: #e0e7ff;
                                width: 24px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #6366f1;
                            ">
                                🏷️
                            </span>
                            Category *
                        </label>
                        <select name="category"
                                required
                                style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                               "
                               onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.1)';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            <option value="">Select Category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Home & Kitchen">Home & Kitchen</option>
                            <option value="Books">Books</option>
                            <option value="Sports">Sports</option>
                            <option value="Beauty">Beauty</option>
                            <option value="Toys">Toys</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column - Stock & Pricing -->
                <div>
                    <h3 style="
                        margin: 0 0 20px 0;
                        font-size: 20px;
                        font-weight: 700;
                        color: #1f2937;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    ">
                        <span style="
                            background: #8b5cf6;
                            width: 6px;
                            height: 20px;
                            border-radius: 3px;
                            display: inline-block;
                        "></span>
                        Stock & Pricing
                    </h3>

                    <!-- Quantity -->
                    <div style="margin-bottom: 25px;">
                        <label style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <span style="
                                background: #fce7f3;
                                width: 24px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #db2777;
                            ">
                                📦
                            </span>
                            Initial Quantity *
                        </label>
                        <div style="position: relative;">
                            <input type="number"
                                   name="quantity"
                                   required
                                   min="0"
                                   placeholder="0"
                                   style="
                                        width: 100%;
                                        padding: 16px 20px 16px 50px;
                                        border: 2px solid #e5e7eb;
                                        border-radius: 12px;
                                        font-size: 16px;
                                        color: #1f2937;
                                        background: white;
                                        transition: all 0.3s ease;
                                   "
                                   onfocus="this.style.borderColor='#db2777'; this.style.boxShadow='0 0 0 3px rgba(219, 39, 119, 0.1)';"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            <div style="
                                position: absolute;
                                left: 20px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #db2777;
                                font-weight: 600;
                                font-size: 14px;
                            ">
                                Qty
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div style="margin-bottom: 25px;">
                        <label style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <span style="
                                background: #d1fae5;
                                width: 24px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #10b981;
                            ">
                                💰
                            </span>
                            Price (₹) *
                        </label>
                        <div style="position: relative;">
                            <input type="number"
                                   name="price"
                                   required
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   style="
                                        width: 100%;
                                        padding: 16px 20px 16px 50px;
                                        border: 2px solid #e5e7eb;
                                        border-radius: 12px;
                                        font-size: 16px;
                                        color: #1f2937;
                                        background: white;
                                        transition: all 0.3s ease;
                                   "
                                   onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            <div style="
                                position: absolute;
                                left: 20px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #10b981;
                                font-weight: 600;
                                font-size: 14px;
                            ">
                                ₹
                            </div>
                        </div>




            </div>

            <!-- Action Buttons -->
            <div style="
                display: flex;
                gap: 15px;
                justify-content: flex-end;
                padding-top: 30px;
                border-top: 1px solid #e5e7eb;
                flex-wrap: wrap;
            ">
                <a href="{{ route('inventory.index') }}" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    background: white;
                    color: #4b5563;
                    text-decoration: none;
                    padding: 16px 30px;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 16px;
                    transition: all 0.3s ease;
                    border: 2px solid #e5e7eb;
                "
                onmouseover="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#9ca3af';"
                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb';">
                    <span>←</span>
                    Cancel
                </a>

                <button type="submit" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    border: none;
                    padding: 16px 30px;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 16px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
                "
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.35)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.25)';">
                    <span>💾</span>
                    Save Product
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Responsive adjustments -->
<style>
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        div[style*="margin-left: 260px; margin-top: 70px;"] {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 10px !important;
        }

        div[style*="max-width: 900px;"] {
            margin: 10px !important;
        }

        div[style*="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="padding: 40px;"] {
            padding: 20px !important;
        }

        h2[style*="font-size: 32px;"] {
            font-size: 24px !important;
        }
    }

    @media (max-width: 480px) {
        div[style*="display: flex; justify-content: flex-end;"] {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        a[style*="padding: 16px 30px;"],
        button[style*="padding: 16px 30px;"] {
            width: 100% !important;
            justify-content: center !important;
        }

        div[style*="padding: 30px;"] {
            padding: 15px !important;
        }
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
