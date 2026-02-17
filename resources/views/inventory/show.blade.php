@extends('layouts.app')

@section('page-title', 'Product Details')

@section('content')
<div style="
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    margin-left: 260px;
    margin-top: 70px;
">
    <div style="max-width: 1000px; margin: 0 auto;">

        <!-- Header -->
        <div style="
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        ">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h1 style="
                        margin: 0;
                        font-size: 28px;
                        font-weight: 800;
                        color: #1f2937;
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    ">
                        <span style="
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
        <div style="
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        ">
            <!-- Image and Basic Info Section -->
            <div style="
                display: grid;
                grid-template-columns: 300px 1fr;
                gap: 30px;
                padding: 30px;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                border-bottom: 2px solid #e5e7eb;
            ">
                <!-- Image Column -->
                <div style="
                    background: white;
                    border-radius: 16px;
                    padding: 20px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                ">
                    @if($product->image)
                        @php
                            $imageUrl = filter_var($product->image, FILTER_VALIDATE_URL)
                                ? $product->image
                                : asset('storage/'.$product->image);
                        @endphp
                        <div style="position: relative; text-align: center;">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $product->name }}"
                                 style="
                                    max-width: 100%;
                                    max-height: 250px;
                                    object-fit: contain;
                                    border-radius: 12px;
                                    cursor: pointer;
                                    transition: transform 0.3s;
                                 "
                                 onmouseover="this.style.transform='scale(1.02)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onclick="openFullImage('{{ $imageUrl }}')"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">

                            @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                <span style="
                                    position: absolute;
                                    top: 10px;
                                    right: 10px;
                                    background: #8b5cf6;
                                    color: white;
                                    padding: 4px 8px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    display: flex;
                                    align-items: center;
                                    gap: 4px;
                                ">
                                    <span>🔗</span> External URL
                                </span>
                            @else
                                <span style="
                                    position: absolute;
                                    top: 10px;
                                    right: 10px;
                                    background: #10b981;
                                    color: white;
                                    padding: 4px 8px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    display: flex;
                                    align-items: center;
                                    gap: 4px;
                                ">
                                    <span>📁</span> Local Storage
                                </span>
                            @endif
                        </div>
                    @else
                        <div style="
                            width: 100%;
                            height: 250px;
                            background: #f3f4f6;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-direction: column;
                            gap: 10px;
                            border: 2px dashed #e5e7eb;
                        ">
                            <span style="font-size: 64px;">📦</span>
                            <p style="color: #9ca3af; font-weight: 500; margin: 0;">No Image Available</p>
                            <p style="color: #d1d5db; font-size: 12px; margin: 0;">Upload an image to see it here</p>
                        </div>
                    @endif
                </div>

                <!-- Basic Info Column -->
                <div>
                    <!-- Product Header -->
                    <div style="margin-bottom: 20px;">
                        <h2 style="
                            margin: 0 0 10px 0;
                            font-size: 32px;
                            font-weight: 800;
                            color: #1f2937;
                        ">
                            {{ $product->name }}
                        </h2>
                        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                            <span style="
                                background: #e0e7ff;
                                color: #3730a3;
                                padding: 6px 16px;
                                border-radius: 30px;
                                font-weight: 600;
                                font-size: 14px;
                            ">
                                {{ $product->category ?? 'Uncategorized' }}
                            </span>
                            <span style="
                                background: #f3f4f6;
                                color: #4b5563;
                                padding: 6px 16px;
                                border-radius: 30px;
                                font-weight: 600;
                                font-size: 14px;
                                display: flex;
                                align-items: center;
                                gap: 5px;
                            ">
                                <span style="font-size: 12px;">🔢</span>
                                {{ $product->product_code }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div style="
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 15px;
                        margin-bottom: 20px;
                    ">
                        <div style="
                            background: white;
                            padding: 15px;
                            border-radius: 12px;
                            border: 1px solid #e5e7eb;
                        ">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Price</div>
                            <div style="font-size: 24px; font-weight: 800; color: #059669;">₹ {{ number_format($product->price, 2) }}</div>
                        </div>

                        <div style="
                            background: white;
                            padding: 15px;
                            border-radius: 12px;
                            border: 1px solid #e5e7eb;
                        ">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Quantity</div>
                            <div style="font-size: 24px; font-weight: 800; color: {{ $product->quantity <= 10 ? '#dc2626' : '#1f2937' }};">
                                {{ $product->quantity }}
                                @if($product->quantity <= 10)
                                    <span style="font-size: 16px; margin-left: 5px;">⚠️</span>
                                @endif
                            </div>
                        </div>

                        <div style="
                            background: white;
                            padding: 15px;
                            border-radius: 12px;
                            border: 1px solid #e5e7eb;
                        ">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Total Value</div>
                            <div style="font-size: 20px; font-weight: 800; color: #7c3aed;">
                                ₹ {{ number_format($product->price * $product->quantity, 2) }}
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status Badge -->
                    <div style="
                        display: inline-flex;
                        align-items: center;
                        padding: 10px 20px;
                        border-radius: 30px;
                        font-weight: 600;
                        background: {{ $product->quantity <= 10 ? '#fee2e2' : ($product->quantity <= 30 ? '#fef3c7' : '#d1fae5') }};
                        color: {{ $product->quantity <= 10 ? '#dc2626' : ($product->quantity <= 30 ? '#d97706' : '#059669') }};
                        gap: 8px;
                    ">
                        @if($product->quantity <= 10)
                            <span>⚠️</span> Low Stock - Reorder Recommended
                        @elseif($product->quantity <= 30)
                            <span>📊</span> Normal Stock Level
                        @else
                            <span>✅</span> High Stock Level
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div style="padding: 30px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">

                    <!-- Left Column - Product Information -->
                    <div>
                        <h3 style="
                            margin: 0 0 20px 0;
                            font-size: 18px;
                            font-weight: 700;
                            color: #1f2937;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        ">
                            <span style="
                                background: #667eea;
                                width: 4px;
                                height: 20px;
                                border-radius: 2px;
                                display: inline-block;
                            "></span>
                            Product Information
                        </h3>

                        <div style="background: #f9fafb; padding: 25px; border-radius: 16px;">
                            <div style="display: flex; flex-direction: column; gap: 15px;">
                                <div style="display: flex; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">
                                    <div style="width: 120px; font-weight: 600; color: #4b5563;">Product Code</div>
                                    <div style="color: #1f2937; font-weight: 500;">{{ $product->product_code }}</div>
                                </div>

                                <div style="display: flex; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">
                                    <div style="width: 120px; font-weight: 600; color: #4b5563;">Category</div>
                                    <div>
                                        <span style="
                                            background: #e0e7ff;
                                            color: #3730a3;
                                            padding: 4px 12px;
                                            border-radius: 20px;
                                            font-weight: 500;
                                            font-size: 14px;
                                        ">
                                            {{ $product->category ?? 'Uncategorized' }}
                                        </span>
                                    </div>
                                </div>

                                <div style="display: flex; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">
                                    <div style="width: 120px; font-weight: 600; color: #4b5563;">Price</div>
                                    <div style="font-weight: 700; color: #059669; font-size: 18px;">
                                        ₹ {{ number_format($product->price, 2) }}
                                    </div>
                                </div>

                                <div style="display: flex; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">
                                    <div style="width: 120px; font-weight: 600; color: #4b5563;">Quantity</div>
                                    <div style="font-weight: 600; color: #1f2937;">
                                        {{ $product->quantity }} units
                                        @if($product->quantity <= 10)
                                            <span style="margin-left: 10px; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 12px; font-size: 12px;">Low Stock</span>
                                        @endif
                                    </div>
                                </div>

                                <div style="display: flex; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">
                                    <div style="width: 120px; font-weight: 600; color: #4b5563;">Created</div>
                                    <div style="color: #6b7280;">
                                        {{ $product->created_at->format('F d, Y \a\t h:i A') }}
                                    </div>
                                </div>

                                <div style="display: flex;">
                                    <div style="width: 120px; font-weight: 600; color: #4b5563;">Last Updated</div>
                                    <div style="color: #6b7280;">
                                        {{ $product->updated_at->format('F d, Y \a\t h:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        @if($product->description)
                            <div style="margin-top: 25px;">
                                <h3 style="
                                    margin: 0 0 15px 0;
                                    font-size: 16px;
                                    font-weight: 700;
                                    color: #1f2937;
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                ">
                                    <span style="
                                        background: #f59e0b;
                                        width: 4px;
                                        height: 18px;
                                        border-radius: 2px;
                                        display: inline-block;
                                    "></span>
                                    Description
                                </h3>
                                <div style="
                                    background: #f9fafb;
                                    padding: 20px;
                                    border-radius: 12px;
                                    color: #4b5563;
                                    line-height: 1.8;
                                    border-left: 4px solid #f59e0b;
                                ">
                                    {{ $product->description }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Stock Analysis -->
                    <div>
                        <h3 style="
                            margin: 0 0 20px 0;
                            font-size: 18px;
                            font-weight: 700;
                            color: #1f2937;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        ">
                            <span style="
                                background: #8b5cf6;
                                width: 4px;
                                height: 20px;
                                border-radius: 2px;
                                display: inline-block;
                            "></span>
                            Stock Analysis
                        </h3>

                        <div style="background: #f9fafb; padding: 25px; border-radius: 16px;">
                            <!-- Stock Level Indicator -->
                            <div style="margin-bottom: 30px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span style="font-weight: 600; color: #4b5563;">Stock Level</span>
                                    <span style="font-weight: 700; color: #1f2937;">{{ $product->quantity }} units</span>
                                </div>
                                @php
                                    $maxStock = 100; // Maximum expected stock for visualization
                                    $percentage = min(100, ($product->quantity / $maxStock) * 100);
                                    $color = $product->quantity <= 10 ? '#ef4444' : ($product->quantity <= 30 ? '#f59e0b' : '#10b981');
                                @endphp
                                <div style="
                                    height: 12px;
                                    background: #e5e7eb;
                                    border-radius: 6px;
                                    overflow: hidden;
                                    margin-bottom: 5px;
                                ">
                                    <div style="
                                        height: 100%;
                                        width: {{ $percentage }}%;
                                        background: {{ $color }};
                                        border-radius: 6px;
                                        transition: width 0.5s ease;
                                    "></div>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="font-size: 12px; color: #9ca3af;">0</span>
                                    <span style="font-size: 12px; color: #9ca3af;">25</span>
                                    <span style="font-size: 12px; color: #9ca3af;">50</span>
                                    <span style="font-size: 12px; color: #9ca3af;">75</span>
                                    <span style="font-size: 12px; color: #9ca3af;">100+</span>
                                </div>
                            </div>

                            <!-- Stock Status Cards -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                                <div style="
                                    background: white;
                                    padding: 15px;
                                    border-radius: 12px;
                                    border: 1px solid #e5e7eb;
                                    text-align: center;
                                ">
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Status</div>
                                    <div style="
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 5px;
                                        padding: 4px 12px;
                                        border-radius: 20px;
                                        background: {{ $product->quantity <= 10 ? '#fee2e2' : '#d1fae5' }};
                                        color: {{ $product->quantity <= 10 ? '#dc2626' : '#059669' }};
                                        font-weight: 600;
                                        font-size: 14px;
                                    ">
                                        @if($product->quantity <= 10)
                                            ⚠️ Low Stock
                                        @elseif($product->quantity <= 30)
                                            📊 Normal
                                        @else
                                            ✅ High Stock
                                        @endif
                                    </div>
                                </div>

                                <div style="
                                    background: white;
                                    padding: 15px;
                                    border-radius: 12px;
                                    border: 1px solid #e5e7eb;
                                    text-align: center;
                                ">
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Reorder Point</div>
                                    <div style="font-weight: 700; color: #1f2937;">10 units</div>
                                </div>
                            </div>

                            <!-- Inventory Value Card -->
                            <div style="
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                padding: 25px;
                                border-radius: 16px;
                                color: white;
                                text-align: center;
                            ">
                                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">
                                    Total Inventory Value
                                </div>
                                <div style="font-size: 36px; font-weight: 800; margin-bottom: 5px;">
                                    ₹ {{ number_format($product->price * $product->quantity, 2) }}
                                </div>
                                <div style="font-size: 14px; opacity: 0.8;">
                                    (₹{{ number_format($product->price, 2) }} × {{ $product->quantity }} units)
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            @if(auth()->user()->role === 'admin')
                                <div style="margin-top: 25px;">
                                    <div style="display: flex; gap: 10px;">
                                        <a href="{{ route('inventory.edit', $product->id) }}"
                                           style="
                                                flex: 1;
                                                background: white;
                                                color: #4f46e5;
                                                text-decoration: none;
                                                padding: 12px;
                                                border-radius: 10px;
                                                font-weight: 600;
                                                font-size: 14px;
                                                text-align: center;
                                                border: 2px solid #e0e7ff;
                                                transition: all 0.3s;
                                           "
                                           onmouseover="this.style.background='#e0e7ff'"
                                           onmouseout="this.style.background='white'">
                                            ✏️ Quick Edit
                                        </a>
                                        <a href="{{ route('inventory.create') }}"
                                           style="
                                                flex: 1;
                                                background: white;
                                                color: #10b981;
                                                text-decoration: none;
                                                padding: 12px;
                                                border-radius: 10px;
                                                font-weight: 600;
                                                font-size: 14px;
                                                text-align: center;
                                                border: 2px solid #d1fae5;
                                                transition: all 0.3s;
                                           "
                                           onmouseover="this.style.background='#d1fae5'"
                                           onmouseout="this.style.background='white'">
                                            ➕ Add New
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            @if(auth()->user()->role === 'admin')
                <div style="
                    padding: 25px 30px;
                    border-top: 2px solid #f3f4f6;
                    background: #f9fafb;
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

<!-- Full Screen Image Modal -->
<div id="imageModal" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.9);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    cursor: pointer;
" onclick="closeFullImage()">
    <span style="
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        cursor: pointer;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transition: all 0.3s;
    " onmouseover="this.style.background='rgba(255,255,255,0.2)'"
       onmouseout="this.style.background='rgba(255,255,255,0.1)'">
        ×
    </span>
    <img id="fullImage" src="" alt="Full size image" style="
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    ">
</div>

<script>
function openFullImage(src) {
    document.getElementById('fullImage').src = src;
    document.getElementById('imageModal').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeFullImage() {
    document.getElementById('imageModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFullImage();
    }
});
</script>

<style>
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        div[style*="margin-left: 260px; margin-top: 70px;"] {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 10px !important;
        }

        div[style*="grid-template-columns: 300px 1fr;"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));"] {
            grid-template-columns: 1fr !important;
        }

        div[style*="padding: 30px;"] {
            padding: 20px !important;
        }
    }

    @media (max-width: 480px) {
        div[style*="display: flex; gap: 15px; justify-content: flex-end;"] {
            flex-direction: column !important;
        }

        a[style*="padding: 14px 30px;"],
        button[style*="padding: 14px 30px;"] {
            width: 100% !important;
            justify-content: center !important;
        }

        div[style*="grid-template-columns: 1fr 1fr;"] {
            grid-template-columns: 1fr !important;
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
