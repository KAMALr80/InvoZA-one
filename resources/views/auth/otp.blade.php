<x-guest-layout>
    <div style="max-width:380px;margin:auto;padding:30px;text-align:center;">
        <h3>🔐 Verify Login OTP</h3>

        <p style="font-size:13px;color:#6b7280;margin-bottom:15px;">
            OTP sent to your email
        </p>

        <form method="POST" action="{{ route('otp.verify.post') }}">
            @csrf

            <input type="text" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required
                placeholder="Enter 6 digit OTP" style="width:100%;padding:10px;text-align:center;letter-spacing:4px;">


            @error('otp')
                <div style="color:red;font-size:13px;margin-top:8px;">
                    {{ $message }}
                </div>
            @enderror

            <button style="width:100%;padding:10px;margin-top:15px;">
                Verify OTP
            </button>
        </form>

        @if (session('status'))
            <div style="color:green;font-size:13px;margin-top:10px;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="color:red;font-size:13px;margin-top:8px;">
                {{ $errors->first('otp') }}
            </div>
        @endif


        <form method="POST" action="{{ route('otp.resend') }}" style="margin-top:12px;">
            @csrf
            <button type="submit" onclick="this.disabled=true;this.innerText='Sending...';this.form.submit();"
                style="background:none;border:none;color:#2563eb;">
                🔁 Resend OTP
            </button>

        </form>
    </div>
</x-guest-layout>
