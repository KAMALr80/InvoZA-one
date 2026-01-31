<x-guest-layout>
    <div
        style="
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg,#e0f2fe,#f8fafc);
            font-family:Segoe UI, sans-serif;
        ">

        <div
            style="
                width:420px;
                background:#ffffff;
                padding:30px;
                border-radius:12px;
                box-shadow:0 20px 40px rgba(0,0,0,0.1);
            ">

            <h2 style="text-align:center;margin-bottom:8px;font-size:22px;">
                📝 ERP Register
            </h2>

            <p style="text-align:center;font-size:13px;color:#6b7280;margin-bottom:22px;">
                Create your account. OTP verification required.
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div style="margin-bottom:14px;">
                    <label style="font-size:13px;color:#374151;">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:5px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                        ">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div style="margin-bottom:14px;">
                    <label style="font-size:13px;color:#374151;">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:5px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                        ">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Mobile -->
                <div style="margin-bottom:14px;">
                    <label style="font-size:13px;color:#374151;">Mobile Number</label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}"
                        placeholder="10 digit mobile number" required maxlength="10"
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:5px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                        ">
                    <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                </div>

                <!-- Password -->
                <div style="margin-bottom:14px;">
                    <label style="font-size:13px;color:#374151;">Password</label>
                    <input type="password" name="password" required
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:5px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                        ">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div style="margin-bottom:18px;">
                    <label style="font-size:13px;color:#374151;">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:5px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                        ">
                </div>

                <!-- Register Button -->
                <button type="submit"
                    style="
                        width:100%;
                        padding:11px;
                        background:#111827;
                        color:#ffffff;
                        border:none;
                        border-radius:6px;
                        font-size:15px;
                        cursor:pointer;
                    ">
                    Register & Get OTP
                </button>
            </form>

            <!-- Divider -->
            <div style="text-align:center;margin:20px 0;color:#9ca3af;font-size:12px;">
                OR
            </div>

            <!-- Login -->
            <a href="{{ route('login') }}"
                style="
                    display:block;
                    text-align:center;
                    padding:10px;
                    border-radius:6px;
                    background:#2563eb;
                    color:#ffffff;
                    text-decoration:none;
                    font-size:14px;
                ">
                🔐 Back to Login
            </a>

        </div>
    </div>
</x-guest-layout>
