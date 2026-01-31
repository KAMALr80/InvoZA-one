<x-guest-layout>
    <div
        style="
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg,#c7d2fe,#e0f2fe,#f8fafc);
            font-family:'Segoe UI',Tahoma,sans-serif;
        ">

        <div
            style="
                width:420px;
                background:rgba(255,255,255,0.95);
                padding:35px 30px;
                border-radius:16px;
                box-shadow:0 25px 60px rgba(0,0,0,0.15);
                backdrop-filter:blur(6px);
            ">

            <!-- Title -->
            <h2
                style="
                    text-align:center;
                    margin-bottom:8px;
                    font-size:24px;
                    font-weight:600;
                    color:#111827;
                ">
                🔐 ERP Login
            </h2>

            <p style="text-align:center;font-size:13px;color:#6b7280;margin-bottom:25px;">
                Sign in to access your dashboard
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div style="margin-bottom:18px;">
                    <label style="font-size:13px;color:#374151;font-weight:500;">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        style="
                            width:100%;
                            padding:11px 12px;
                            margin-top:6px;
                            border-radius:8px;
                            border:1px solid #d1d5db;
                            outline:none;
                            font-size:14px;
                        "
                        onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div style="margin-bottom:18px;">
                    <label style="font-size:13px;color:#374151;font-weight:500;">
                        Password
                    </label>
                    <input type="password" name="password" required
                        style="
                            width:100%;
                            padding:11px 12px;
                            margin-top:6px;
                            border-radius:8px;
                            border:1px solid #d1d5db;
                            outline:none;
                            font-size:14px;
                        "
                        onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#d1d5db'">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember + Forgot -->
                <div
                    style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        margin-bottom:20px;
                    ">
                    <label style="display:flex;align-items:center;font-size:13px;color:#4b5563;">
                        <input type="checkbox" name="remember" style="margin-right:6px;">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            style="font-size:13px;color:#2563eb;text-decoration:none;">
                            Forgot?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit"
                    style="
                        width:100%;
                        padding:12px;
                        background:linear-gradient(135deg,#2563eb,#1d4ed8);
                        color:#ffffff;
                        border:none;
                        border-radius:8px;
                        font-size:15px;
                        font-weight:500;
                        cursor:pointer;
                    "
                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Login
                </button>
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
            </form>

            <!-- Divider -->
            <div
                style="
                    display:flex;
                    align-items:center;
                    margin:22px 0;
                    color:#9ca3af;
                    font-size:12px;
                ">
                <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                <span style="margin:0 10px;">OR</span>
                <div style="flex:1;height:1px;background:#e5e7eb;"></div>
            </div>

            <!-- Register -->
            <a href="{{ route('register') }}"
                style="
                    display:block;
                    text-align:center;
                    padding:11px;
                    border-radius:8px;
                    background:#111827;
                    color:#ffffff;
                    text-decoration:none;
                    font-size:14px;
                "
                onmouseover="this.style.background='#1f2937'" onmouseout="this.style.background='#111827'">
                📝 Create New Account
            </a>

        </div>
    </div>
</x-guest-layout>

<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
            },
            function() {
                alert('Location allow karna zaruri hai for secure login');
            }
        );
    }
</script>
