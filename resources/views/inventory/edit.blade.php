@extends('layouts.app')

@section('page-title', 'Edit Product')

@section('content')
    <div
        style="
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    margin-left: 260px;
    margin-top: 70px;
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
        max-width: 1000px;
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
            position: relative;
            overflow: hidden;
        ">
                    <!-- Background pattern -->
                    <div
                        style="
                position: absolute;
                right: -40px;
                top: -40px;
                width: 200px;
                height: 200px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                transform: rotate(45deg);
            ">
                    </div>
                    <div
                        style="
                position: absolute;
                left: -60px;
                bottom: -60px;
                width: 180px;
                height: 180px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
            ">
                    </div>

                    <div
                        style="
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 10px;
                position: relative;
                z-index: 1;
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
                                Update product information and image
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
                                Category
                            </div>
                            <div
                                style="
                        font-weight: 600;
                        color: #1f2937;
                        font-size: 14px;
                    ">
                                {{ $product->category ?? 'Uncategorized' }}
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
                <form method="POST" action="{{ route('inventory.update', $product->id) }}" enctype="multipart/form-data"
                    style="padding: 40px;">
                    @csrf
                    @method('PUT')

                    <!-- Two Column Layout -->
                    <div
                        style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                margin-bottom: 30px;
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

                            <!-- Product Code (Read Only) -->
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
                                width: 24px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #3b82f6;
                            ">
                                        🔢
                                    </span>
                                    Product Code
                                </label>
                                <input type="text" value="{{ $product->product_code }}" readonly
                                    style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #6b7280;
                                    background: #f3f4f6;
                                    cursor: not-allowed;
                               ">
                            </div>

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
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                    style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid {{ $errors->has('name') ? '#ef4444' : '#e5e7eb' }};
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                               "
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                                    onblur="this.style.borderColor='{{ $errors->has('name') ? '#ef4444' : '#e5e7eb' }}'; this.style.boxShadow='none';">
                                @error('name')
                                    <p style="color: #ef4444; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category Field with ALL Categories -->
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

                                <!-- Category Dropdown with ALL Categories -->
                                <select id="category_select" name="category" onchange="toggleCategoryInput(this)"
                                    style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid {{ $errors->has('category') ? '#ef4444' : '#e5e7eb' }};
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                                    cursor: pointer;
                                    margin-bottom: 10px;
                               "
                                    onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.1)';"
                                    onblur="this.style.borderColor='{{ $errors->has('category') ? '#ef4444' : '#e5e7eb' }}'; this.style.boxShadow='none';">
                                    <option value="">Select Category</option>

                                    <!-- Original Categories -->
                                    <option value="Electronics"
                                        {{ old('category', $product->category) == 'Electronics' ? 'selected' : '' }}>
                                        📱 Electronics
                                    </option>
                                    <option value="Clothing"
                                        {{ old('category', $product->category) == 'Clothing' ? 'selected' : '' }}>
                                        👕 Clothing
                                    </option>
                                    <option value="Home & Kitchen"
                                        {{ old('category', $product->category) == 'Home & Kitchen' ? 'selected' : '' }}>
                                        🏠 Home & Kitchen
                                    </option>
                                    <option value="Books"
                                        {{ old('category', $product->category) == 'Books' ? 'selected' : '' }}>
                                        📚 Books
                                    </option>
                                    <option value="Sports"
                                        {{ old('category', $product->category) == 'Sports' ? 'selected' : '' }}>
                                        ⚽ Sports
                                    </option>
                                    <option value="Beauty"
                                        {{ old('category', $product->category) == 'Beauty' ? 'selected' : '' }}>
                                        💄 Beauty
                                    </option>
                                    <option value="Toys"
                                        {{ old('category', $product->category) == 'Toys' ? 'selected' : '' }}>
                                        🧸 Toys
                                    </option>

                                    <!-- NEW CATEGORIES ADDED HERE -->
                                    <option value="Stationery"
                                        {{ old('category', $product->category) == 'Stationery' ? 'selected' : '' }}>
                                        ✏️ Stationery
                                    </option>
                                    <option value="Household"
                                        {{ old('category', $product->category) == 'Household' ? 'selected' : '' }}>
                                        🧹 Household
                                    </option>
                                    <option value="Misc"
                                        {{ old('category', $product->category) == 'Misc' ? 'selected' : '' }}>
                                        📌 Misc
                                    </option>
                                    <option value="Grocery"
                                        {{ old('category', $product->category) == 'Grocery' ? 'selected' : '' }}>
                                        🛒 Grocery
                                    </option>

                                    <!-- Other (Custom) -->
                                    <option value="Other"
                                        {{ !in_array(old('category', $product->category), ['', 'Electronics', 'Clothing', 'Home & Kitchen', 'Books', 'Sports', 'Beauty', 'Toys', 'Stationery', 'Household', 'Misc', 'Grocery']) ? 'selected' : '' }}>
                                        ✨ Other (Custom)
                                    </option>
                                </select>

                                <!-- Custom Category Input (Hidden by default) -->
                                <div id="custom_category_container" style="display: none; margin-top: 10px;">
                                    <input type="text" id="custom_category" name="custom_category"
                                        value="{{ !in_array(old('category', $product->category), ['', 'Electronics', 'Clothing', 'Home & Kitchen', 'Books', 'Sports', 'Beauty', 'Toys', 'Stationery', 'Household', 'Misc', 'Grocery']) ? old('category', $product->category) : '' }}"
                                        placeholder="Enter custom category name"
                                        style="
                                                width: 100%;
                                                padding: 16px 20px;
                                                border: 2px solid #8b5cf6;
                                                border-radius: 12px;
                                                font-size: 16px;
                                                color: #1f2937;
                                                background: #f5f3ff;
                                                transition: all 0.3s ease;
                                           "
                                        onfocus="this.style.borderColor='#6d28d9'; this.style.boxShadow='0 0 0 3px rgba(109, 40, 217, 0.1)';"
                                        onblur="this.style.borderColor='#8b5cf6'; this.style.boxShadow='none';">
                                    <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                        <span style="color: #8b5cf6;">✨</span> Enter your custom category name
                                    </p>
                                </div>

                                @error('category')
                                    <p style="color: #ef4444; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description Field -->
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
                                width: 24px;
                                height: 24px;
                                border-radius: 6px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 12px;
                                color: #f59e0b;
                            ">
                                        📝
                                    </span>
                                    Description
                                </label>
                                <textarea name="description" rows="4" placeholder="Enter product description"
                                    style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    color: #1f2937;
                                    background: white;
                                    transition: all 0.3s ease;
                                    resize: vertical;
                                    font-family: inherit;
                                  "
                                    onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
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
                            background: #8b5cf6;
                            width: 6px;
                            height: 20px;
                            border-radius: 3px;
                            display: inline-block;
                        "></span>
                                Stock & Pricing
                            </h3>

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
                                    Quantity *
                                </label>
                                <div style="position: relative;">
                                    <input type="number" name="quantity"
                                        value="{{ old('quantity', $product->quantity) }}" min="0" required
                                        style="
                                        width: 100%;
                                        padding: 16px 20px 16px 50px;
                                        border: 2px solid {{ $errors->has('quantity') ? '#ef4444' : '#e5e7eb' }};
                                        border-radius: 12px;
                                        font-size: 16px;
                                        color: #1f2937;
                                        background: white;
                                        transition: all 0.3s ease;
                                   "
                                        onfocus="this.style.borderColor='#db2777'; this.style.boxShadow='0 0 0 3px rgba(219, 39, 119, 0.1)';"
                                        onblur="this.style.borderColor='{{ $errors->has('quantity') ? '#ef4444' : '#e5e7eb' }}'; this.style.boxShadow='none';">
                                    <div
                                        style="
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
                                @error('quantity')
                                    <p style="color: #ef4444; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
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
                                    Price (₹) *
                                </label>
                                <div style="position: relative;">
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                        step="0.01" min="0" required
                                        style="
                                        width: 100%;
                                        padding: 16px 20px 16px 50px;
                                        border: 2px solid {{ $errors->has('price') ? '#ef4444' : '#e5e7eb' }};
                                        border-radius: 12px;
                                        font-size: 16px;
                                        color: #1f2937;
                                        background: white;
                                        transition: all 0.3s ease;
                                   "
                                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                                        onblur="this.style.borderColor='{{ $errors->has('price') ? '#ef4444' : '#e5e7eb' }}'; this.style.boxShadow='none';">
                                    <div
                                        style="
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
                                @error('price')
                                    <p style="color: #ef4444; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section - Full Width -->
                    <div
                        style="
                margin-bottom: 30px;
                background: #f9fafb;
                border-radius: 16px;
                padding: 30px;
                border: 2px dashed #e5e7eb;
            ">
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
                        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
                        width: 40px;
                        height: 40px;
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 20px;
                        color: white;
                    ">
                                🖼️
                            </span>
                            Product Image
                        </h3>

                        <!-- Current Image Preview -->
                        @if ($product->image)
                            <div
                                style="
                    margin-bottom: 25px;
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    border: 1px solid #e5e7eb;
                ">
                                <p
                                    style="font-weight: 600; color: #374151; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                                    <span
                                        style="background: #8b5cf6; width: 4px; height: 18px; border-radius: 2px; display: inline-block;"></span>
                                    Current Image
                                </p>
                                <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                                    @php
                                        $imageUrl = filter_var($product->image, FILTER_VALIDATE_URL)
                                            ? $product->image
                                            : asset('storage/' . $product->image);
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 2px solid #e5e7eb;"
                                        onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">

                                    <label
                                        style="display: flex; align-items: center; gap: 8px; cursor: pointer; background: #fee2e2; padding: 10px 20px; border-radius: 8px;">
                                        <input type="checkbox" name="remove_image" value="1"
                                            style="width: 18px; height: 18px;">
                                        <span style="color: #dc2626; font-weight: 500;">Remove current image</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Image Type Toggle -->
                        <div
                            style="
                    display: flex;
                    gap: 30px;
                    margin-bottom: 25px;
                    background: white;
                    padding: 15px 20px;
                    border-radius: 12px;
                    border: 1px solid #e5e7eb;
                ">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="radio" name="image_type" value="upload" checked
                                    onclick="toggleImageInput('upload')" style="width: 18px; height: 18px;">
                                <span
                                    style="font-weight: 500; color: #374151; display: flex; align-items: center; gap: 5px;">
                                    <span style="font-size: 18px;">📤</span> Upload New File
                                </span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="radio" name="image_type" value="url" onclick="toggleImageInput('url')"
                                    style="width: 18px; height: 18px;">
                                <span
                                    style="font-weight: 500; color: #374151; display: flex; align-items: center; gap: 5px;">
                                    <span style="font-size: 18px;">🔗</span> Image URL
                                </span>
                            </label>
                        </div>

                        <!-- File Upload Input -->
                        <div id="upload-input" style="display: block;">
                            <div style="
                        background: white;
                        border-radius: 12px;
                        padding: 25px;
                        text-align: center;
                        border: 2px dashed #cbd5e1;
                        transition: all 0.3s;
                        cursor: pointer;
                    "
                                onclick="document.getElementById('fileInput').click()"
                                onmouseover="this.style.borderColor='#8b5cf6'; this.style.background='#f5f3ff'"
                                onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='white'">
                                <div style="font-size: 40px; margin-bottom: 10px;">📸</div>
                                <p style="font-weight: 600; color: #374151; margin: 0 0 5px 0;">Click to upload new image
                                </p>
                                <p style="font-size: 13px; color: #6b7280; margin: 0;">JPG, PNG, GIF (Max: 2MB)</p>
                                <input type="file" name="image" id="fileInput" accept="image/*"
                                    style="display: none;" onchange="previewImage(this)">
                            </div>
                        </div>

                        <!-- URL Input -->
                        <div id="url-input" style="display: none;">
                            <div style="position: relative;">
                                <input type="url" name="image_url" id="imageUrl"
                                    placeholder="https://example.com/image.jpg"
                                    style="
                                    width: 100%;
                                    padding: 16px 20px;
                                    border: 2px solid #e5e7eb;
                                    border-radius: 12px;
                                    font-size: 16px;
                                    transition: all 0.3s;
                               "
                                    onfocus="this.style.borderColor='#8b5cf6'; this.style.boxShadow='0 0 0 3px rgba(139, 92, 246, 0.1)';"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                                    oninput="previewUrl(this.value)">
                                <p style="font-size: 13px; color: #6b7280; margin-top: 8px;">
                                    <span
                                        style="background: #e0e7ff; padding: 2px 8px; border-radius: 4px; font-size: 12px;">🔗</span>
                                    Enter direct image URL (must start with http:// or https://)
                                </p>
                            </div>
                        </div>

                        <!-- New Image Preview -->
                        <div id="image-preview"
                            style="
                    margin-top: 25px;
                    display: none;
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    border: 1px solid #e5e7eb;
                ">
                            <p
                                style="font-weight: 600; color: #374151; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                                <span
                                    style="background: #8b5cf6; width: 4px; height: 18px; border-radius: 2px; display: inline-block;"></span>
                                New Image Preview
                            </p>
                            <div style="text-align: center;">
                                <img id="preview-img" src="" alt="Preview"
                                    style="
                                max-width: 100%;
                                max-height: 250px;
                                border-radius: 12px;
                                border: 2px solid #e5e7eb;
                                padding: 5px;
                                background: #f9fafb;
                             "
                                    onerror="this.onerror=null; this.src=''; document.getElementById('image-preview').style.display='none';">
                            </div>
                        </div>

                        @error('image')
                            <p style="color: #ef4444; font-size: 13px; margin-top: 10px;">{{ $message }}</p>
                        @enderror
                        @error('image_url')
                            <p style="color: #ef4444; font-size: 13px; margin-top: 10px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden input to handle custom category -->
                    <input type="hidden" name="use_custom_category" id="use_custom_category" value="0">

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

    <!-- JavaScript for Category and Image Handling -->
    <script>
        // ========== CATEGORY HANDLING ==========
        function toggleCategoryInput(selectElement) {
            const customContainer = document.getElementById('custom_category_container');
            const customInput = document.getElementById('custom_category');
            const useCustomHidden = document.getElementById('use_custom_category');

            if (selectElement.value === 'Other') {
                // Show custom input
                customContainer.style.display = 'block';
                useCustomHidden.value = '1';

                // If coming from old input, keep the value
                @if (old('custom_category') ||
                        !in_array(old('category', $product->category), [
                            '',
                            'Electronics',
                            'Clothing',
                            'Home & Kitchen',
                            'Books',
                            'Sports',
                            'Beauty',
                            'Toys',
                            'Stationery',
                            'Household',
                            'Misc',
                            'Grocery',
                        ]))
                    customInput.value =
                        '{{ old('custom_category', !in_array($product->category, ['', 'Electronics', 'Clothing', 'Home & Kitchen', 'Books', 'Sports', 'Beauty', 'Toys', 'Stationery', 'Household', 'Misc', 'Grocery']) ? $product->category : '') }}';
                @endif
            } else {
                // Hide custom input
                customContainer.style.display = 'none';
                useCustomHidden.value = '0';
                customInput.value = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_select');

            // Updated predefined categories list with ALL categories
            const predefinedCategories = [
                '',
                'Electronics',
                'Clothing',
                'Home & Kitchen',
                'Books',
                'Sports',
                'Beauty',
                'Toys',
                'Stationery',
                'Household',
                'Misc',
                'Grocery'
            ];

            const currentCategory = '{{ old('category', $product->category) }}';

            if (!predefinedCategories.includes(currentCategory) && currentCategory !== '') {
                // Set select to "Other"
                categorySelect.value = 'Other';
                // Show custom input with current value
                toggleCategoryInput(categorySelect);
            } else {
                // Normal initialization
                toggleCategoryInput(categorySelect);
            }

            // ========== IMAGE HANDLING ==========
            @if (old('image_type') === 'url' && old('image_url'))
                toggleImageInput('url');
                document.querySelector('input[name="image_type"][value="url"]').checked = true;
                document.getElementById('imageUrl').value = '{{ old('image_url') }}';
                previewUrl('{{ old('image_url') }}');
            @endif
        });

        // ========== IMAGE HANDLING FUNCTIONS ==========
        function toggleImageInput(type) {
            const uploadInput = document.getElementById('upload-input');
            const urlInput = document.getElementById('url-input');
            const preview = document.getElementById('image-preview');

            if (type === 'upload') {
                uploadInput.style.display = 'block';
                urlInput.style.display = 'none';
            } else {
                uploadInput.style.display = 'none';
                urlInput.style.display = 'block';
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewUrl(url) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
                previewImg.src = url;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
                previewImg.src = '';
            }
        }

        // Form submit handling to ensure custom category is sent correctly
        document.querySelector('form').addEventListener('submit', function(e) {
            const categorySelect = document.getElementById('category_select');
            const customInput = document.getElementById('custom_category');
            const useCustomHidden = document.getElementById('use_custom_category');

            if (categorySelect.value === 'Other' && customInput.value.trim() !== '') {
                // Set the category select value to custom input value
                // Create a hidden input to override the select
                const hiddenCategory = document.createElement('input');
                hiddenCategory.type = 'hidden';
                hiddenCategory.name = 'category';
                hiddenCategory.value = customInput.value.trim();

                // Disable the original select so it doesn't get submitted
                categorySelect.disabled = true;

                // Add the hidden input
                this.appendChild(hiddenCategory);
            }
        });
    </script>

    <!-- Responsive adjustments -->
    <style>
        @media (max-width: 768px) {
            div[style*="margin-left: 260px; margin-top: 70px;"] {
                margin-left: 0 !important;
                margin-top: 0 !important;
                padding: 10px !important;
            }

            div[style*="max-width: 1000px;"] {
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

            div[style*="display: flex; gap: 30px; margin-bottom: 25px;"] {
                flex-direction: column !important;
                gap: 10px !important;
            }
        }

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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #8b5cf6;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
    </style>
@endsection
