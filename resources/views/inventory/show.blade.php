@extends('layouts.app')

@section('content')
    <div
        style="
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Header -->
            <div
                style="
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        ">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>
                        <h1
                            style="
                        margin: 0;
                        font-size: 28px;
                        font-weight: 800;
                        color: #1f2937;
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    ">
                            <span
                                style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            width: 50px;
                            height: 50px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                            color: white;
                            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                        ">
                                📦
                            </span>
                            Product Details
                        </h1>
                        <p style="margin: 10px 0 0 0; color: #6b7280; font-size: 16px;">
                            View complete information about the product
                        </p>
                    </div>
                    <a href="{{ route('inventory.index') }}"
                        style="
                    background: #f3f4f6;
                    color: #4b5563;
                    text-decoration: none;
                    padding: 12px 24px;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 14px;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    transition: all 0.3s ease;
                "
                        onmouseover="this.style.background='#e5e7eb'; this.style.transform='translateY(-1px)';"
                        onmouseout="this.style.background='#f3f4f6'; this.style.transform='translateY(0)';">
                        ← Back to Inventory
                    </a>
                </div>
            </div>

            <!-- Product Details Card -->
            <div
                style="
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        ">
                <!-- Product Header -->
                <div
                    style="
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 2px solid #f3f4f6;
            ">
                    <div>
                        <h2
                            style="
                        margin: 0;
                        font-size: 24px;
                        font-weight: 800;
                        color: #1f2937;
                    ">
                            {{ $product->name }}
                        </h2>
                        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 16px;">
                            {{ $product->product_code }}
                        </p>
                    </div>

                    <div
                        style="
                    display: inline-flex;
                    align-items: center;
                    background: {{ $product->quantity <= 10 ? '#fee2e2' : '#d1fae5' }};
                    color: {{ $product->quantity <= 10 ? '#dc2626' : '#059669' }};
                    padding: 8px 16px;
                    border-radius: 20px;
                    font-weight: 700;
                    font-size: 16px;
                ">
                        {{ $product->quantity }} in stock
                        @if ($product->quantity <= 10)
                            <span style="margin-left: 8px; font-size: 14px;">⚠️</span>
                        @endif
                    </div>
                </div>

                <!-- Details Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    <!-- Left Column -->
                    <div>
                        <div style="margin-bottom: 25px;">
                            <h3
                                style="
                            margin: 0 0 12px 0;
                            font-size: 14px;
                            font-weight: 600;
                            color: #6b7280;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        ">
                                Product Information
                            </h3>
                            <div style="background: #f9fafb; padding: 20px; border-radius: 12px;">
                                <div
                                    style="display: grid; grid-template-columns: 100px 1fr; gap: 15px; align-items: center;">
                                    <span style="font-weight: 600; color: #4b5563;">Price:</span>
                                    <span style="font-weight: 700; color: #1f2937; font-size: 18px;">
                                        ₹ {{ number_format($product->price, 2) }}
                                    </span>

                                    <span style="font-weight: 600; color: #4b5563;">Category:</span>
                                    <span
                                        style="
                                    display: inline-flex;
                                    align-items: center;
                                    background: #e0e7ff;
                                    color: #3730a3;
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    font-weight: 600;
                                    font-size: 14px;
                                    width: fit-content;
                                ">
                                        {{ $product->category }}
                                    </span>

                                    <span style="font-weight: 600; color: #4b5563;">Created:</span>
                                    <span style="color: #6b7280;">
                                        {{ $product->created_at->format('M d, Y') }}
                                    </span>

                                    <span style="font-weight: 600; color: #4b5563;">Updated:</span>
                                    <span style="color: #6b7280;">
                                        {{ $product->updated_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if ($product->description)
                            <div>
                                <h3
                                    style="
                            margin: 0 0 12px 0;
                            font-size: 14px;
                            font-weight: 600;
                            color: #6b7280;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        ">
                                    Description
                                </h3>
                                <div
                                    style="
                            background: #f9fafb;
                            padding: 20px;
                            border-radius: 12px;
                            color: #4b5563;
                            line-height: 1.6;
                        ">
                                    {{ $product->description }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Stock Status -->
                    <div>
                        <h3
                            style="
                        margin: 0 0 12px 0;
                        font-size: 14px;
                        font-weight: 600;
                        color: #6b7280;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">
                            Stock Status
                        </h3>

                        <div style="background: #f9fafb; padding: 25px; border-radius: 12px; height: 100%;">
                            <!-- Stock Level Indicator -->
                            <div style="margin-bottom: 25px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span style="font-weight: 600; color: #4b5563;">Stock Level</span>
                                    <span style="font-weight: 700; color: #1f2937;">{{ $product->quantity }} units</span>
                                </div>
                                <div
                                    style="
                                height: 10px;
                                background: #e5e7eb;
                                border-radius: 5px;
                                overflow: hidden;
                            ">
                                    @php
                                        $percentage = min(100, ($product->quantity / 50) * 100); // Assuming 50 as max for visualization
                                        $color =
                                            $product->quantity <= 10
                                                ? '#ef4444'
                                                : ($product->quantity <= 30
                                                    ? '#f59e0b'
                                                    : '#10b981');
                                    @endphp
                                    <div
                                        style="
                                    height: 100%;
                                    width: {{ $percentage }}%;
                                    background: {{ $color }};
                                    border-radius: 5px;
                                    transition: width 0.5s ease;
                                ">
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                                    <span style="font-size: 12px; color: #9ca3af;">Low</span>
                                    <span style="font-size: 12px; color: #9ca3af;">Normal</span>
                                    <span style="font-size: 12px; color: #9ca3af;">High</span>
                                </div>
                            </div>

                            <!-- Stock Status -->
                            <div style="margin-bottom: 25px;">
                                <h4 style="margin: 0 0 15px 0; font-size: 16px; color: #374151; font-weight: 600;">
                                    Status
                                </h4>
                                <div
                                    style="
                                display: inline-flex;
                                align-items: center;
                                padding: 8px 16px;
                                border-radius: 20px;
                                font-weight: 600;
                                background: {{ $product->quantity <= 10 ? '#fee2e2' : '#d1fae5' }};
                                color: {{ $product->quantity <= 10 ? '#dc2626' : '#059669' }};
                            ">
                                    @if ($product->quantity <= 10)
                                        ⚠️ Low Stock - Reorder Recommended
                                    @elseif($product->quantity <= 30)
                                        📊 Normal Stock
                                    @else
                                        ✅ High Stock
                                    @endif
                                </div>
                            </div>

                            <!-- Product Value -->
                            <div>
                                <h4 style="margin: 0 0 15px 0; font-size: 16px; color: #374151; font-weight: 600;">
                                    Inventory Value
                                </h4>
                                <div
                                    style="
                                background: white;
                                padding: 20px;
                                border-radius: 12px;
                                border: 1px solid #e5e7eb;
                            ">
                                    <div style="text-align: center;">
                                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                                            Total Value
                                        </div>
                                        <div style="font-size: 28px; font-weight: 800; color: #1f2937;">
                                            ₹ {{ number_format($product->price * $product->quantity, 2) }}
                                        </div>
                                        <div style="font-size: 14px; color: #9ca3af; margin-top: 8px;">
                                            (₹{{ number_format($product->price, 2) }} × {{ $product->quantity }} units)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if (auth()->user()->role === 'admin')
                    <div
                        style="
                margin-top: 30px;
                padding-top: 25px;
                border-top: 2px solid #f3f4f6;
                display: flex;
                gap: 15px;
                justify-content: flex-end;
            ">
                        <a href="{{ route('inventory.edit', $product->id) }}"
                            style="
                    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                    color: white;
                    text-decoration: none;
                    padding: 14px 30px;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 15px;
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
                "
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.35)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.25)';">
                            ✏️ Edit Product
                        </a>

                        <form method="POST" action="{{ route('inventory.destroy', $product->id) }}" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')"
                                style="
                        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                        color: white;
                        padding: 14px 30px;
                        border-radius: 12px;
                        font-weight: 600;
                        border: none;
                        cursor: pointer;
                        font-size: 15px;
                        display: inline-flex;
                        align-items: center;
                        gap: 10px;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);
                    "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(239, 68, 68, 0.35)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(239, 68, 68, 0.25)';">
                                🗑️ Delete Product
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
