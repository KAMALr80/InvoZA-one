<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class RegisterOtpController extends Controller
{
    /**
     * Show Register OTP page
     */
    public function show()
    {
        if (!session()->has('register_otp_user_id')) {
            return redirect()->route('register');
        }

        return view('auth.register-otp');
    }

    /**
     * 🔐 Verify Register OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find(session('register_otp_user_id'));

        if (
            !$user ||
            $user->login_otp !== $request->otp ||
            now()->gt($user->otp_expires_at)
        ) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP',
            ]);
        }

        // ✅ OTP correct → activate account
        $user->update([
            'login_otp' => null,
            'otp_expires_at' => null,
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        session()->forget('register_otp_user_id');

        return redirect()->route('login')
            ->with('status', 'Registration successful. Please login.');
    }

    /**
     * 🔁 Resend Register OTP (EMAIL + SMS)
     */
    public function resend()
    {
        if (!session()->has('register_otp_user_id')) {
            return redirect()->route('register');
        }

        $user = User::find(session('register_otp_user_id'));

        if (!$user) {
            return redirect()->route('register');
        }

        // 🔐 Generate new OTP
        $otp = rand(100000, 999999);

        $user->update([
            'login_otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        /* ================= EMAIL OTP ================= */
        Mail::raw(
            "Your ERP registration OTP is: $otp\nValid for 5 minutes.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('ERP Registration OTP');
            }
        );

        /* ================= SMS OTP ================= */
        if (!empty($user->mobile)) {
            $this->sendOtpSms($user->mobile, $otp);
        }

        return back()->with('status', 'A new OTP has been sent to your email and mobile.');
    }

    /**
     * 📲 Send OTP via Fast2SMS
     */
    private function sendOtpSms($mobile, $otp)
    {
        try {
            Http::withHeaders([
                'authorization' => env('FAST2SMS_API_KEY'),
                'accept' => 'application/json',
            ])->post('https://www.fast2sms.com/dev/bulkV2', [
                'route' => 'otp',
                'variables_values' => $otp,
                'numbers' => $mobile, // 10 digit only
            ]);
        } catch (\Exception $e) {
            // Optional: log error
            Log::error('Fast2SMS OTP Error: ' . $e->getMessage());
        }
    }
}
