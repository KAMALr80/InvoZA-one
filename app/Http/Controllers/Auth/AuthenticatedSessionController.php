<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

use App\Helpers\GeoHelper;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(Request $request): RedirectResponse
    {
        /* ================= STEP 1: Email + Password ================= */
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ]);
        }

        /* ================= STEP 2: Staff approval ================= */
        if ($user->role === 'staff' && $user->status !== 'approved') {
            return back()->withErrors([
                'email' => 'Your account is not approved yet.',
            ]);
        }

        /* ================= STEP 3: Location data ================= */
        $userLat = $request->lat;
        $userLng = $request->lng;

        $officeLat     = env('OFFICE_LAT');
        $officeLng     = env('OFFICE_LNG');
        $allowedRadius = env('ALLOWED_RADIUS_KM', 1);

        /**
         * CASE A: Location OFF → EMAIL OTP REQUIRED
         */
        if (!$userLat || !$userLng) {
            $this->triggerLoginEmailOtp($user);
            return redirect()->route('otp.verify');
        }

        /* ================= STEP 4: Distance check ================= */
        $distance = GeoHelper::distanceKm(
            $officeLat,
            $officeLng,
            $userLat,
            $userLng
        );

        /**
         * CASE B: Outside office radius → EMAIL OTP REQUIRED
         */
        if ($distance > $allowedRadius) {
            $this->triggerLoginEmailOtp($user);
            return redirect()->route('otp.verify');
        }

        /**
         * CASE C: Inside office radius → DIRECT LOGIN
         */
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Generate & send LOGIN OTP via EMAIL
     * (FORCE SAVE — very important)
     */
    private function triggerLoginEmailOtp(User $user): void
    {
        $otp = rand(100000, 999999);

        // 🔒 FORCE DB SAVE (NO update())
        $user->login_otp       = (string) $otp;
        $user->otp_expires_at  = now()->addMinutes(5);
        $user->save();

        // 📧 Send OTP via EMAIL
        Mail::raw(
            "Your ERP login OTP is: {$otp}\n\nThis OTP is valid for 5 minutes.\nIf you did not try to login, please ignore this email.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('ERP Login OTP');
            }
        );

        // 🧠 Store user id for OTP verification
        Session::put('otp_user_id', $user->id);
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
