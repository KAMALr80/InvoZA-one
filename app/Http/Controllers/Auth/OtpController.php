<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
class OtpController extends Controller
{
    /**
     * Show OTP verification page
     */
    public function show()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    /**
     * Verify login OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('otp_user_id');
        $user   = User::find($userId);

        if (
            !$user ||
            !$user->login_otp ||
            !$user->otp_expires_at ||
            trim($request->otp) !== (string) $user->login_otp ||
            now()->gt($user->otp_expires_at)
        ) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP',
            ]);
        }

        // ✅ OTP correct → login
        Auth::login($user);
        $request->session()->regenerate();

        // 🔥 Clear OTP
        $user->login_otp = null;
        $user->otp_expires_at = null;
        $user->save();

        Session::forget('otp_user_id');

        return redirect()->route('dashboard');
    }

    /**
     * Resend OTP (Email only)
     */
    public function resend()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        // ⏱ Cooldown: 60 seconds
        if ($user->otp_expires_at) {
            $lastSentAt = Carbon::parse($user->otp_expires_at)->subMinutes(5);
            if (now()->diffInSeconds($lastSentAt) < 60) {
                return back()->withErrors([
                    'otp' => 'Please wait 1 minute before resending OTP.',
                ]);
            }
        }

        $otp = rand(100000, 999999);

        // ✅ Force save OTP
        $user->login_otp = (string) $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // 📧 Send OTP email
        Mail::raw(
            "Your ERP login OTP is: {$otp}\n\nValid for 5 minutes.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('ERP Login OTP');
            }
        );

        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
