@extends('layouts.app')

@section('content')
    <div
        style="
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
">

        @if (auth()->user()->role !== 'admin')
            <!-- Unauthorized Access Card -->
            <div
                style="
        max-width: 500px;
        width: 100%;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        text-align: center;
        border: 1px solid #e5e7eb;
    ">
                <div
                    style="
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

                <h2
                    style="
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        ">
                    Access Denied
                </h2>

                <p
                    style="
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        ">
                    You don't have permission to edit products. Only administrators can modify inventory items.
                </p>

                <a href="{{ route('inventory.index') }}"
                    style="
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
            <!-- Edit Product Form -->
            <div
                style="
        max-width: 800px;
        width: 100%;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    ">
                <!-- Form Header -->
                <div
                    style="
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            color: white;
        ">
                    <div
                        style="
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 10px;
            ">
                        <div
                            style="
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
                            ✏️
                        </div>
                        <div>
                            <h2
                                style="
                        margin: 0;
                        font-size: 32px;
                        font-weight: 800;
                    ">
                                Edit Product
                            </h2>
                            <p
                                style="
                        margin: 5px 0 0 0;
                        opacity: 0.9;
                        font-size: 16px;
                    ">
                                Update product information in the inventory
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Product Info Card -->
                <div
                    style="
            padding: 30px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e5e7eb;
        ">
                    <div
                        style="
                display: flex;
                align-items: center;
                gap: 15px;
                flex-wrap: wrap;
            ">
                        <div
                            style="
                    background: white;
                    padding: 12px 20px;
                    border-radius: 12px;
                    border: 2px solid #e5e7eb;
                ">
                            <div
                                style="
                        font-size: 12px;
                        color: #6b7280;
                        font-weight: 600;
                        margin-bottom: 4px;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">
                                Product Code
                            </div>
                            <div
                                style="
                        font-weight: 800;
                        color: #1f2937;
                        font-size: 18px;
                    ">
                                {{ $product->product_code }}
                            </div>
                        </div>

                        <div
                            style="
                    background: white;
                    padding: 12px 20px;
                    border-radius: 12px;
                    border: 2px solid #e5e7eb;
                ">
                            <div
                                style="
                        font-size: 12px;
                        color: #6b7280;
                        font-weight: 600;
                        margin-bottom: 4px;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">
                                Current Stock
                            </div>
                            <div
                                style="
                        font-weight: 800;
                        color: {{ $product->quantity <= 10 ? '#ef4444' : '#059669' }};
                        font-size: 18px;
                    ">
                                {{ $product->quantity }} units
                            </div>
                        </div>

                        <div
                            style="
                    background: white;
                    padding: 12px 20px;
                    border-radius: 12px;
                    border: 2px solid #e5e7eb;
                ">
                            <div
                                style="
                        font-size: 12px;
                        color: #6b7280;
                        font-weight: 600;
                        margin-bottom: 4px;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">
                                Last Updated
                            </div>
                            <div
                                style="
                        font-weight: 600;
                        color: #1f2937;
                        font-size: 14px;
                    ">
                                {{ $product->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('inventory.update', $product->id) }}" style="padding: 40px;">
                    @csrf
                    @method('PUT')

                    <!-- Two Column Layout -->
                    <div
                        style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                margin-bottom: 40px;
            ">
                        <!-- Left Column -->
                        <div>
                            <h3
                                style="
                        margin: 0 0 20px 0;
                        font-size: 20px;
                        font-weight: 700;
                        color: #1f2937;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    ">
                                <span
                                    style="
                            background: #667eea;
                            width: 6px;
                            height: 20px;
                            border-radius: 3px;
                            display: inline-block;
                        "></span>
                                Product Details
                            </h3>

                            <!-- Name Field -->
                            <div style="margin-bottom: 25px;">
                                <label
                                    style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                                    <span
                                        style="
                                background: #dbeafe;
                                width: 20x;
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
                                    Product Name
                                </label>
                                <input type="text" name="name" value="{{ $product->name }}" required
                                    style="
                                    width: 50%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                               "
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            </div>

                            <!-- Price Field -->
                            <div style="margin-bottom: 25px;">
                                <label
                                    style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                                    <span
                                        style="
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
                                    Price (₹)
                                </label>
                                <input type="number" name="price" value="{{ $product->price }}" step="0.01"
                                    min="0" required
                                    style="
                                    width: 50%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                               "
                                    onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            </div>

                            <!-- Quantity Field -->
                            <div style="margin-bottom: 25px;">
                                <label
                                    style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                                    <span
                                        style="
                                background: #fef3c7;
                                width: 20px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #d97706;
                            ">
                                        📦
                                    </span>
                                    Quantity
                                </label>
                                <input type="number" name="quantity" value="{{ $product->quantity }}" min="0"
                                    required
                                    style="
                                    width: 50%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                               "
                                    onfocus="this.style.borderColor='#d97706'; this.style.boxShadow='0 0 0 3px rgba(217, 119, 6, 0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <h3
                                style="
                        margin: 0 0 20px 0;
                        font-size: 20px;
                        font-weight: 800;
                        color: #1f2937;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    ">
                                <span
                                    style="
                            background: #8b5cf6;
                            width: 6px;
                            height: 20px;
                            border-radius: 3px;
                            display: inline-block;
                        "></span>
                                Additional Information
                            </h3>

                            <!-- Category Field -->
                            <div style="margin-bottom: 25px;">
                                <label
                                    style="
                            display: block;
                            margin-bottom: 10px;
                            font-weight: 600;
                            color: #374151;
                            font-size: 15px;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                                    <span
                                        style="
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
                                    Category
                                </label>
                                <input type="text" name="category" value="{{ $product->category }}" required
                                    style="
                                    width: 50%;
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
                            </div>




                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        style="
                display: flex;
                gap: 15px;
                justify-content: flex-end;
                padding-top: 30px;
                border-top: 1px solid #e5e7eb;
                flex-wrap: wrap;
            ">
                        <a href="{{ route('inventory.index') }}"
                            style="
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

                        <button type="submit"
                            style="
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
                            <span>✅</span>
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>

    <style>
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
