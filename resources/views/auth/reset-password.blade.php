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

            <h2 style="text-align:center;margin-bottom:12px;font-size:22px;">
                🔐 Reset Password
            </h2>

            <p style="text-align:center;font-size:13px;color:#6b7280;margin-bottom:22px;">
                Create a new password for your account
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div style="margin-bottom:15px;">
                    <label style="font-size:14px;color:#374151;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:6px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                            outline:none;
                        ">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- New Password -->
                <div style="margin-bottom:15px;">
                    <label style="font-size:14px;color:#374151;">New Password</label>
                    <input type="password" name="password" required
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:6px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                            outline:none;
                        ">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div style="margin-bottom:20px;">
                    <label style="font-size:14px;color:#374151;">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        style="
                            width:100%;
                            padding:10px;
                            margin-top:6px;
                            border-radius:6px;
                            border:1px solid #d1d5db;
                            outline:none;
                        ">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Reset Button -->
                <button type="submit"
                    style="
                        width:100%;
                        padding:10px;
                        background:#111827;
                        color:#ffffff;
                        border:none;
                        border-radius:6px;
                        font-size:15px;
                        cursor:pointer;
                    ">
                    🔁 Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div style="text-align:center;margin-top:18px;">
                <a href="{{ route('login') }}" style="font-size:13px;color:#2563eb;text-decoration:none;">
                    ← Back to Login
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
