<x-guest-layout>
    <div
        style="
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f8fafc;
            font-family:Segoe UI, sans-serif;
        ">

        <div
            style="
                width:380px;
                background:#ffffff;
                padding:30px;
                border-radius:12px;
                box-shadow:0 15px 35px rgba(0,0,0,0.1);
                text-align:center;
            ">

            <h3 style="margin-bottom:8px;">
                📲 Verify Registration OTP
            </h3>

            <p style="font-size:13px;color:#6b7280;margin-bottom:20px;">
                OTP has been sent to your registered email / mobile
            </p>

            <form method="POST" action="{{ route('register.otp.verify') }}">
                @csrf

                <input type="text" name="otp" placeholder="Enter 6 digit OTP" required maxlength="6"
                    style="
                        width:100%;
                        padding:10px;
                        margin-bottom:12px;
                        border-radius:6px;
                        border:1px solid #d1d5db;
                        text-align:center;
                        font-size:16px;
                        letter-spacing:4px;
                    ">

                @error('otp')
                    <div style="color:red;font-size:13px;margin-bottom:10px;">
                        {{ $message }}
                    </div>
                @enderror

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
                    Verify OTP
                </button>
            </form>

            @if (session('status'))
                <div style="color:green;font-size:13px;margin-top:10px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.otp.resend') }}" style="margin-top:15px;">
                @csrf

                <button type="submit"
                    style="
            background:none;
            border:none;
            color:#2563eb;
            font-size:13px;
            cursor:pointer;
        ">
                    🔁 Resend OTP
                </button>
            </form>


        </div>
    </div>
</x-guest-layout>
