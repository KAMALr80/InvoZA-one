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
                text-align:center;
            ">

            <h2 style="font-size:22px;margin-bottom:12px;">
                📧 Verify Your Email
            </h2>

            <p style="font-size:13px;color:#6b7280;margin-bottom:20px;">
                We’ve sent a verification link to your email address.
                Please verify your email before continuing.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div
                    style="
                        margin-bottom:15px;
                        font-size:13px;
                        color:#065f46;
                        background:#d1fae5;
                        padding:10px;
                        border-radius:6px;
                    ">
                    ✅ A new verification link has been sent to your email.
                </div>
            @endif

            <!-- Resend -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    style="
                        width:100%;
                        padding:10px;
                        background:#2563eb;
                        color:#ffffff;
                        border:none;
                        border-radius:6px;
                        font-size:15px;
                        cursor:pointer;
                        margin-bottom:15px;
                    ">
                    🔁 Resend Verification Email
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="
                        background:none;
                        border:none;
                        font-size:13px;
                        color:#6b7280;
                        cursor:pointer;
                        text-decoration:underline;
                    ">
                    Log Out
                </button>
            </form>

        </div>
    </div>
</x-guest-layout>
