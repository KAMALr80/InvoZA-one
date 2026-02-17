@extends('layouts.app')

@section('page-title', 'Inventory Management')

@section('content')
    <div
        style="
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    ">

        <!-- Main Container -->
        <div style="max-width: 1400px; margin: 0 auto;">

            <!-- Header Card -->
            <div
                style="
                background: white;
                border-radius: 20px;
                padding: 30px;
                margin-bottom: 30px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
            ">
                <div
                    style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 20px;
                ">
                    <div>
                        <h1
                            style="
                            margin: 0;
                            font-size: 32px;
                            font-weight: 800;
                            color: #1f2937;
                            display: flex;
                            align-items: center;
                            gap: 15px;
                        ">
                            <span
                                style="
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                width: 60px;
                                height: 60px;
                                border-radius: 15px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 28px;
                                color: white;
                                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                            ">
                                📦
                            </span>
                            Inventory Management
                        </h1>
                        <p
                            style="
                            margin: 10px 0 0 0;
                            color: #6b7280;
                            font-size: 16px;
                        ">
                            Manage your products, track stock levels, and monitor inventory
                        </p>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('inventory.create') }}"
                            style="
                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                            color: white;
                            text-decoration: none;
                            padding: 15px 30px;
                            border-radius: 12px;
                            font-weight: 600;
                            font-size: 16px;
                            display: inline-flex;
                            align-items: center;
                            gap: 10px;
                            transition: all 0.3s ease;
                            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
                            border: none;
                            cursor: pointer;
                        "
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.35)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.25)';">
                            <span style="font-size: 20px;">+</span>
                            Add New Product
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            <div
                style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            ">
                <div
                    style="
                    background: white;
                    border-radius: 15px;
                    padding: 25px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    border-left: 5px solid #667eea;
                ">
                    <div
                        style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <div>
                            <p
                                style="
                                margin: 0;
                                color: #6b7280;
                                font-size: 14px;
                                font-weight: 600;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            ">
                                Total Products
                            </p>
                            <h3 style="margin: 10px 0 0 0; font-size: 36px; font-weight: 800; color: #1f2937;">
                                {{ $products->count() }}
                            </h3>
                        </div>
                        <div
                            style="
                            background: linear-gradient(135deg, #c7d2fe 0%, #a5b4fc 100%);
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                        ">
                            📊
                        </div>
                    </div>
                </div>

                <div
                    style="
                    background: white;
                    border-radius: 15px;
                    padding: 25px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    border-left: 5px solid #10b981;
                ">
                    <div
                        style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <div>
                            <p
                                style="
                                margin: 0;
                                color: #6b7280;
                                font-size: 14px;
                                font-weight: 600;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            ">
                                Low Stock
                            </p>
                            <h3
                                style="
                                margin: 10px 0 0 0;
                                font-size: 36px;
                                font-weight: 800;
                                color: #1f2937;
                            ">
                                {{ $lowStockCount }}
                            </h3>
                        </div>
                        <div
                            style="
                            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                        ">
                            ⚠️
                        </div>
                    </div>
                </div>

                <div
                    style="
                    background: white;
                    border-radius: 15px;
                    padding: 25px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    border-left: 5px solid #f59e0b;
                ">
                    <div
                        style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <div>
                            <p
                                style="
                                margin: 0;
                                color: #6b7280;
                                font-size: 14px;
                                font-weight: 600;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            ">
                                Total Value
                            </p>
                            <h3
                                style="
                                margin: 10px 0 0 0;
                                font-size: 36px;
                                font-weight: 800;
                                color: #1f2937;
                            ">
                                ₹ {{ number_format($totalValue, 2) }}
                            </h3>
                        </div>
                        <div
                            style="
                            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                            width: 60px;
                            height: 60px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 24px;
                        ">
                            💰
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div
                style="
                background: white;
                border-radius: 20px;
                padding: 25px;
                margin-bottom: 30px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                border: 1px solid #e5e7eb;
            ">
                <div
                    style="
                    display: flex;
                    gap: 15px;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: space-between;
                ">
                    <!-- Left side: Filters -->
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <!-- Category Filter -->
                        <select id="categoryFilter"
                            style="
                                padding: 12px 18px;
                                border: 2px solid #e5e7eb;
                                border-radius: 12px;
                                font-size: 14px;
                                color: #1f2937;
                                background: #f9fafb;
                                cursor: pointer;
                                outline: none;
                                min-width: 180px;
                                font-weight: 500;
                            "
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='none';">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>

                        <!-- Stock Filter -->
                        <select id="stockFilter"
                            style="
                                padding: 12px 18px;
                                border: 2px solid #e5e7eb;
                                border-radius: 12px;
                                font-size: 14px;
                                color: #1f2937;
                                background: #f9fafb;
                                cursor: pointer;
                                outline: none;
                                min-width: 160px;
                                font-weight: 500;
                            "
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='none';">
                            <option value="">All Stock</option>
                            <option value="low">Low Stock (≤10)</option>
                         </select>
                    </div>

                    <!-- Right side: Search Box -->
                    <div style="position: relative; min-width: 300px;">
                        <input type="text" id="searchInput" placeholder="Search products..."
                            style="
                                width: 80%;
                                padding: 12px 45px 12px 18px;
                                border: 2px solid #e5e7eb;
                                border-radius: 12px;
                                font-size: 14px;
                                color: #1f2937;
                                background: #f9fafb;
                                transition: all 0.3s ease;
                                outline: none;
                                font-weight: 500;
                            "
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='none';">
                        <span
                            style="
                                position: absolute;
                                right: 15px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #9ca3af;
                                font-size: 16px;
                            ">
                            🔍
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div
                style="
                background: white;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
            ">
                <!-- Table Header with custom controls -->
                <div
                    style="
                    padding: 20px 30px;
                    border-bottom: 1px solid #e5e7eb;
                    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 15px;
                ">
                    <div>
                        <h3
                            style="
                            margin: 0;
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
                                width: 8px;
                                height: 30px;
                                border-radius: 4px;
                                display: inline-block;
                            "></span>
                            Product Inventory
                        </h3>
                    </div>

                    <!-- Entries per page selector -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 14px; color: #4b5563; font-weight: 500;">Show:</span>
                        <select id="entriesPerPage"
                            style="
                                padding: 8px 12px;
                                border: 2px solid #e5e7eb;
                                border-radius: 8px;
                                font-size: 14px;
                                color: #1f2937;
                                background: white;
                                cursor: pointer;
                                outline: none;
                                font-weight: 500;
                                min-width: 80px;
                            "
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                        <span style="font-size: 14px; color: #4b5563; font-weight: 500;">entries</span>
                    </div>
                </div>

                <!-- Table Container -->
                <div style="overflow-x: auto;">
                    <table id="inventoryTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 30px;">
                                    <input type="checkbox" id="selectAll"
                                        style="width: 18px; height: 18px; cursor: pointer;">
                                </th>
                                <th style="width: 70px;">Image</th>  <!-- New Image Column -->
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $p)
                                <tr>
                                    <!-- Checkbox -->
                                    <td>
                                        <input type="checkbox" class="product-checkbox" value="{{ $p->product_code }}"
                                            style="width: 18px; height: 18px; cursor: pointer;">
                                    </td>

                                    <!-- Image Column -->
                                    <td>
                                        @if($p->image)
                                            @php
                                                $imageUrl = filter_var($p->image, FILTER_VALIDATE_URL)
                                                    ? $p->image
                                                    : asset('storage/'.$p->image);
                                            @endphp
                                            <div style="position: relative;">
                                                <img src="{{ $imageUrl }}"
                                                     alt="{{ $p->name }}"
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px; border: 2px solid #e5e7eb; transition: transform 0.3s; cursor: pointer;"
                                                     onmouseover="this.style.transform='scale(1.1)'"
                                                     onmouseout="this.style.transform='scale(1)'"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.opacity='0.5';">
                                                @if(filter_var($p->image, FILTER_VALIDATE_URL))
                                                    <span style="position: absolute; bottom: -2px; right: -2px; background: #8b5cf6; color: white; border-radius: 50%; width: 16px; height: 16px; font-size: 10px; display: flex; align-items: center; justify-content: center; border: 2px solid white;" title="External URL">🔗</span>
                                                @endif
                                            </div>
                                        @else
                                            <div style="
                                                width: 50px;
                                                height: 50px;
                                                background: #f3f4f6;
                                                border-radius: 10px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-size: 24px;
                                                color: #9ca3af;
                                                border: 2px dashed #e5e7eb;
                                            ">
                                                📦
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Product Code -->
                                    <td>
                                        <div style="font-weight: 600; color: #374151; font-size: 14px;">
                                            {{ $p->product_code }}
                                        </div>
                                    </td>

                                    <!-- Product Name -->
                                    <td>
                                        <div style="font-weight: 700; color: #1f2937; font-size: 15px; margin-bottom: 4px;">
                                            {{ $p->name }}
                                        </div>
                                        @if ($p->description)
                                            <div style="font-size: 12px; color: #6b7280; line-height: 1.4; max-width: 200px;">
                                                {{ Str::limit($p->description, 40) }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Quantity -->
                                    <td>
                                        <div
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                background: {{ $p->quantity <= 10 ? '#fee2e2' : '#d1fae5' }};
                                                color: {{ $p->quantity <= 10 ? '#dc2626' : '#059669' }};
                                                font-weight: 700;
                                                padding: 6px 12px;
                                                border-radius: 20px;
                                                font-size: 14px;
                                                min-width: 60px;
                                                text-align: center;
                                            ">
                                            {{ $p->quantity }}
                                            @if ($p->quantity <= 10)
                                                <span style="margin-left: 5px; font-size: 12px;">⚠️</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Price -->
                                    <td>
                                        <div style="font-weight: 700; color: #1f2937; font-size: 15px;">
                                            ₹ {{ number_format($p->price, 2) }}
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <div
                                            style="
                                                display: inline-flex;
                                                align-items: center;
                                                background: #e0e7ff;
                                                color: #3730a3;
                                                padding: 6px 12px;
                                                border-radius: 20px;
                                                font-weight: 600;
                                                font-size: 13px;
                                            ">
                                            {{ $p->category ?? 'Uncategorized' }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            <a href="{{ route('inventory.show', $p->id) }}"
                                                style="
                                                    display: inline-flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    width: 36px;
                                                    height: 36px;
                                                    background: #f0f9ff;
                                                    color: #0369a1;
                                                    border-radius: 8px;
                                                    text-decoration: none;
                                                    font-size: 16px;
                                                    transition: all 0.2s;
                                                "
                                                onmouseover="this.style.background='#e0f2fe'; this.style.transform='translateY(-1px)';"
                                                onmouseout="this.style.background='#f0f9ff'; this.style.transform='translateY(0)';"
                                                title="View Details">
                                                👁️
                                            </a>

                                            @if (auth()->user()->role === 'admin')
                                                <a href="{{ route('inventory.edit', $p->id) }}"
                                                    style="
                                                        display: inline-flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        width: 36px;
                                                        height: 36px;
                                                        background: #dbeafe;
                                                        color: #1d4ed8;
                                                        border-radius: 8px;
                                                        text-decoration: none;
                                                        font-size: 16px;
                                                        transition: all 0.2s;
                                                    "
                                                    onmouseover="this.style.background='#bfdbfe'; this.style.transform='translateY(-1px)';"
                                                    onmouseout="this.style.background='#dbeafe'; this.style.transform='translateY(0)';"
                                                    title="Edit">
                                                    ✏️
                                                </a>

                                                <form method="POST" action="{{ route('inventory.destroy', $p->id) }}"
                                                    style="display: inline; margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this product?')"
                                                        style="
                                                            display: inline-flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            width: 36px;
                                                            height: 36px;
                                                            background: #fee2e2;
                                                            color: #dc2626;
                                                            border-radius: 8px;
                                                            border: none;
                                                            cursor: pointer;
                                                            font-size: 16px;
                                                            transition: all 0.2s;
                                                        "
                                                        onmouseover="this.style.background='#fecaca'; this.style.transform='translateY(-1px)';"
                                                        onmouseout="this.style.background='#fee2e2'; this.style.transform='translateY(0)';"
                                                        title="Delete">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"  <!-- Updated from 7 to 8 -->
                                        style="text-align: center; padding: 60px 20px; color: #6b7280; font-size: 16px;">
                                        <div
                                            style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                            <div
                                                style="
                                                    width: 80px;
                                                    height: 80px;
                                                    background: #f3f4f6;
                                                    border-radius: 16px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    font-size: 32px;
                                                    color: #9ca3af;
                                                ">
                                                📦
                                            </div>
                                            <div>
                                                <h4
                                                    style="margin: 0 0 8px 0; color: #374151; font-weight: 600; font-size: 18px;">
                                                    No Products Found
                                                </h4>
                                                <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                                    Try adjusting your search or add a new product
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (auth()->user()->role === 'admin' && $products->count() > 0)
                    <div
                        style="padding: 25px 30px; border-top: 1px solid #e5e7eb; background: #f9fafb; text-align: right;">
                        <button type="button" onclick="submitBarcodeForm()"
                            style="
                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
                                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
                            "
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.35)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.25)';">
                            <span style="font-size: 18px;">🧾</span>
                            Generate Barcode PDF
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Hidden form for barcode -->
    <form id="barcodeForm" method="POST" action="{{ route('inventory.barcode.preview') }}" target="_blank">
        @csrf
        <input type="hidden" name="product_ids" id="productIdsInput">
    </form>

    <!-- Image Preview Modal -->
    <div id="imageModal" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    " onclick="this.style.display='none'">
        <img id="modalImage" src="" alt="Full size image" style="
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        ">
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <style>
        /* Custom DataTable Styles */
        .dataTables_wrapper {
            padding: 0 20px;
        }

        .dataTables_info {
            padding: 15px 0;
            color: #6b7280;
            font-size: 14px;
        }

        .dataTables_paginate {
            padding: 15px 0;
        }

        .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #4b5563;
            font-weight: 600;
            background: white;
        }

        .dataTables_paginate .paginate_button.current {
            background: #4f46e5;
            color: white !important;
            border-color: #4f46e5;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            color: #1f2937 !important;
            border-color: #d1d5db;
        }

        .dataTables_paginate .paginate_button.disabled {
            background: #f3f4f6;
            color: #9ca3af !important;
            cursor: not-allowed;
        }

        /* Table styling */
        #inventoryTable thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 2px solid #e5e7eb;
            padding: 15px;
            font-weight: 700;
            color: #4b5563;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
        }

        #inventoryTable tbody td {
            padding: 15px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        #inventoryTable tbody tr:hover {
            background-color: #f9fafb !important;
        }

        /* DataTable buttons styling */
        .dt-buttons button {
            background: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            margin: 5px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
        }

        .dt-buttons button:hover {
            background: #4338ca !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        }

        /* Image hover effect */
        .image-cell {
            position: relative;
        }

        .image-cell img {
            transition: all 0.3s ease;
        }

        .image-cell img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Search and filter section responsive */
        @media (max-width: 768px) {
            .search-filter-section {
                flex-direction: column;
            }

            #searchInput {
                min-width: 100%;
                margin-top: 10px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#inventoryTable').DataTable({
                dom: 'Bfrtipl',
                buttons: [{
                        extend: 'excel',
                        text: '📊 Excel',
                        className: 'btn-excel',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6] // Exclude checkbox (0) and image (1)
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '📄 PDF',
                        className: 'btn-pdf',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6] // Exclude checkbox (0) and image (1)
                        }
                    },
                    {
                        extend: 'print',
                        text: '🖨️ Print',
                        className: 'btn-print',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6] // Exclude checkbox (0) and image (1)
                        }
                    }
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                order: [
                    [2, 'asc'] // Sort by product code (index 2)
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search in table...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    {
                        targets: 0, // Checkbox column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 1, // Image column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 7, // Actions column
                        orderable: false,
                        searchable: false
                    }
                ],
                initComplete: function() {
                    // Style DataTable elements
                    $('.dataTables_filter input').css({
                        'border': '2px solid #e5e7eb',
                        'border-radius': '8px',
                        'padding': '8px 12px',
                        'font-size': '14px'
                    });

                    $('.dataTables_length select').css({
                        'border': '2px solid #e5e7eb',
                        'border-radius': '8px',
                        'padding': '6px 10px',
                        'font-size': '14px'
                    });

                    // Hide default search box and length menu
                    $('.dataTables_filter').hide();
                    $('.dataTables_length').hide();

                    // Set initial value for entries per page dropdown
                    $('#entriesPerPage').val('10');
                }
            });

            // Custom Search Box
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Category Filter
            $('#categoryFilter').on('change', function() {
                var category = $(this).val();
                table.column(6).search(category).draw(); // Category is now index 6
            });

            // Stock Filter
            $('#stockFilter').on('change', function() {
                var stockFilter = $(this).val();

                // Remove previous filter if exists
                $.fn.dataTable.ext.search = [];

                if (stockFilter !== '') {
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        var quantityCell = data[4]; // Quantity is now index 4
                        var quantityMatch = quantityCell.match(/\d+/);
                        var quantity = quantityMatch ? parseInt(quantityMatch[0]) : 0;

                        if (stockFilter === 'low' && quantity <= 10) {
                            return true;
                        }
                        if (stockFilter === 'normal' && quantity > 10) {
                            return true;
                        }
                        return false;
                    });
                }

                table.draw();
            });

            // Entries per page selector - FIXED
            $('#entriesPerPage').on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue === '-1') {
                    // Show all entries
                    table.page.len(-1).draw();
                } else {
                    // Show specific number of entries
                    var pageLength = parseInt(selectedValue);
                    table.page.len(pageLength).draw();
                }
            });

            // Update entries dropdown when DataTable changes page length
            table.on('length.dt', function(e, settings, len) {
                $('#entriesPerPage').val(len === -1 ? '-1' : len.toString());
            });

            // Select All Checkbox
            $('#selectAll').on('change', function() {
                $('.product-checkbox').prop('checked', this.checked);
            });

            // Individual checkbox handling
            $(document).on('change', '.product-checkbox', function() {
                var allChecked = $('.product-checkbox:checked').length === $('.product-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Image click for full size preview
            $(document).on('click', '#inventoryTable tbody img', function(e) {
                e.stopPropagation();
                var imgSrc = $(this).attr('src');
                $('#modalImage').attr('src', imgSrc);
                $('#imageModal').css('display', 'flex');
            });

            // Close modal with ESC key
            $(document).keydown(function(e) {
                if (e.key === "Escape") {
                    $('#imageModal').hide();
                }
            });

            // Barcode Form Submission
            window.submitBarcodeForm = function() {
                // Get selected checkboxes
                var selectedCodes = [];

                $('.product-checkbox:checked').each(function() {
                    var code = $(this).val();
                    if (code && code.trim() !== '') {
                        selectedCodes.push(code.trim());
                    }
                });

                // Check if any product selected
                if (selectedCodes.length === 0) {
                    alert('⚠️ Please select at least one product');
                    return false;
                }

                // Create comma-separated string
                var productCodesString = selectedCodes.join(',');

                // Set value in hidden input
                $('#productIdsInput').val(productCodesString);

                // Show loading message
                alert('🔄 Opening barcode preview for ' + selectedCodes.length +
                    ' product(s)...\nPreview will open in new tab.');

                // Submit form (will open in new tab)
                $('#barcodeForm').submit();

                // Reset checkboxes
                setTimeout(function() {
                    $('.product-checkbox').prop('checked', false);
                    $('#selectAll').prop('checked', false);
                }, 1000);

                return true;
            };
        });
    </script>
@endpush
