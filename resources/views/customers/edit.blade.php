@extends('layouts.app')

@section('content')
    <div
        style="
        max-width:700px;
        margin:40px auto;
        background:#ffffff;
        padding:35px;
        border-radius:14px;
        box-shadow:0 12px 30px rgba(0,0,0,0.12);
        font-family:'Segoe UI', sans-serif;
    ">

        <h2
            style="
        text-align:center;
        margin-bottom:25px;
        color:#1f2937;
        font-size:26px;
        font-weight:700;
    ">
            ✏️ Edit Customer
        </h2>

        {{-- ERROR --}}
        @if ($errors->any())
            <div
                style="
            background:#fee2e2;
            color:#991b1b;
            padding:12px;
            border-radius:8px;
            margin-bottom:20px;
        ">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customers.update', $customer->id) }}">
            @csrf
            @method('PUT')

            {{-- NAME --}}
            <label style="font-weight:600;">Customer Name</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                style="
                    width:100%;
                    padding:12px;
                    margin-top:6px;
                    margin-bottom:15px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                    font-size:15px;
               ">

            {{-- MOBILE --}}
            <label style="font-weight:600;">Mobile</label>
            <input type="text" name="mobile" value="{{ old('mobile', $customer->mobile) }}" required
                style="
                    width:100%;
                    padding:12px;
                    margin-top:6px;
                    margin-bottom:15px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                    font-size:15px;
               ">

            {{-- EMAIL --}}
            <label style="font-weight:600;">Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                style="
                    width:100%;
                    padding:12px;
                    margin-top:6px;
                    margin-bottom:15px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                    font-size:15px;
               ">

            {{-- GST --}}
            <label style="font-weight:600;">GST Number</label>
            <input type="text" name="gst_no" value="{{ old('gst_no', $customer->gst_no) }}"
                style="
                    width:100%;
                    padding:12px;
                    margin-top:6px;
                    margin-bottom:15px;
                    border:1px solid #d1d5db;
                    border-radius:8px;
                    font-size:15px;
               ">

            {{-- ADDRESS --}}
            <label style="font-weight:600;">Address</label>
            <textarea name="address" rows="4"
                style="
                        width:100%;
                        padding:12px;
                        margin-top:6px;
                        margin-bottom:25px;
                        border:1px solid #d1d5db;
                        border-radius:8px;
                        font-size:15px;
                  ">{{ old('address', $customer->address) }}</textarea>

            {{-- BUTTONS --}}
            <div style="display:flex; gap:15px; justify-content:center;">
                <a href="{{ route('customers.index') }}"
                    style="
                    text-decoration:none;
                    padding:12px 20px;
                    background:#9ca3af;
                    color:#fff;
                    border-radius:8px;
                    font-weight:600;
               ">
                    ⬅ Back
                </a>

                <button type="submit"
                    style="
                    padding:12px 22px;
                    background:#2563eb;
                    color:#fff;
                    border:none;
                    border-radius:8px;
                    font-size:15px;
                    font-weight:600;
                    cursor:pointer;
                ">
                    💾 Update Customer
                </button>
            </div>

        </form>

    </div>
@endsection
