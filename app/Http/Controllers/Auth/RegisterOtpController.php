<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class RegisterOtpController extends Controller
{
    /**
     * Show Register OTP page
     */
    public function show()
    {
        // Check if using new session key (otp_user_id) or old one (register_otp_user_id)
        if (session()->has('otp_user_id') && session('otp_purpose') === 'registration') {
            $user = User::find(session('otp_user_id'));
            return view('auth.register-otp', [
                'email' => $user ? $user->email : '',
                'purpose' => 'registration'
            ]);
        }

        // Backward compatibility for old session key
        if (!session()->has('register_otp_user_id')) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Registration session expired. Please register again.']);
        }

        $user = User::find(session('register_otp_user_id'));

        return view('auth.register-otp', [
            'email' => $user ? $user->email : '',
            'purpose' => 'registration'
        ]);
    }

    /**
     * 🔐 Verify Register OTP
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        // Check for new session key first, then old one
        $userId = null;
        $usingNewSession = false;

        if (session()->has('otp_user_id') && session('otp_purpose') === 'registration') {
            $userId = session('otp_user_id');
            $usingNewSession = true;
        } elseif (session()->has('register_otp_user_id')) {
            $userId = session('register_otp_user_id');
        } else {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'register_otp_user_id', 'otp_purpose']);
            return redirect()->route('register')
                ->withErrors(['otp' => 'User not found. Please register again.']);
        }

        // Check if account is locked
        if ($this->isAccountLocked($user)) {
            Session::forget(['otp_user_id', 'register_otp_user_id', 'otp_purpose']);
            return redirect()->route('register')
                ->withErrors(['otp' => 'Account temporarily locked. Please try again later.']);
        }

        // Track OTP attempts
        $user->otp_attempts = ($user->otp_attempts ?? 0) + 1;
        $user->save();

        // Check max attempts (5)
        if ($user->otp_attempts > 5) {
            $this->lockAccount($user);

            Session::forget(['otp_user_id', 'register_otp_user_id', 'otp_purpose']);

            Log::warning('Account locked due to too many registration OTP attempts', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            return redirect()->route('register')
                ->withErrors(['otp' => 'Too many failed attempts. Account locked for 15 minutes.']);
        }

        // Verify OTP (check both login_otp and register_otp for backward compatibility)
        $isValid = false;

        // First check register_otp (new field)
        if ($user->register_otp && trim($request->otp) === $user->register_otp &&
            now()->lessThanOrEqualTo(Carbon::parse($user->register_otp_expires_at))) {
            $isValid = true;
        }
        // Then check login_otp (old field for backward compatibility)
        elseif ($user->login_otp && trim($request->otp) === $user->login_otp &&
                now()->lessThanOrEqualTo(Carbon::parse($user->otp_expires_at))) {
            $isValid = true;
        }

        if (!$isValid) {
            $remaining = 5 - $user->otp_attempts;

            Log::info('Invalid registration OTP attempt', [
                'user_id' => $user->id,
                'attempts' => $user->otp_attempts,
                'ip' => $request->ip()
            ]);

            return back()->withErrors([
                'otp' => "Invalid or expired OTP. {$remaining} attempts remaining.",
            ])->withInput();
        }

        // ✅ OTP correct → activate account
        $user->register_otp = null;
        $user->register_otp_expires_at = null;
        $user->login_otp = null; // Clear old field too
        $user->otp_expires_at = null;
        $user->otp_attempts = 0;
        $user->status = 'approved';
        $user->email_verified_at = now();
        $user->save();

        // Create employee record if not exists
        $this->createEmployeeRecord($user);

        // Clear all session keys
        Session::forget(['otp_user_id', 'register_otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);

        Log::info('Registration completed successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return redirect()->route('login')
            ->with('status', 'Registration successful! You can now login with your credentials.');
    }

    /**
     * 🔁 Resend Register OTP
     */
    public function resend(Request $request): RedirectResponse
    {
        // Check for new session key first, then old one
        $userId = null;
        $usingNewSession = false;

        if (session()->has('otp_user_id') && session('otp_purpose') === 'registration') {
            $userId = session('otp_user_id');
            $usingNewSession = true;
        } elseif (session()->has('register_otp_user_id')) {
            $userId = session('register_otp_user_id');
        } else {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'register_otp_user_id', 'otp_purpose']);
            return redirect()->route('register')
                ->withErrors(['otp' => 'User not found. Please register again.']);
        }

        // Check cooldown (60 seconds)
        if ($user->otp_last_sent_at && !$this->canResendOtp($user)) {
            $waitSeconds = $this->getResendWaitSeconds($user);
            return back()->withErrors([
                'otp' => "Please wait {$waitSeconds} seconds before resending OTP.",
            ]);
        }

        // Generate secure OTP
        $otp = sprintf("%06d", random_int(0, 999999));

        // Save OTP in both fields for backward compatibility
        $user->register_otp = $otp;
        $user->register_otp_expires_at = now()->addMinutes(5);
        $user->login_otp = $otp; // Keep old field updated too
        $user->otp_expires_at = now()->addMinutes(5);
        $user->otp_last_sent_at = now();
        $user->otp_attempts = 0;
        $user->save();

        // Update session with new timestamp
        if ($usingNewSession) {
            Session::put('otp_sent_at', now()->timestamp);
            Session::put('otp_expires_at', now()->addMinutes(5)->timestamp);
        }

        /* ================= EMAIL OTP ================= */
        $this->sendOtpEmail($user, $otp);

        /* ================= SMS OTP (Optional) ================= */
        if (!empty($user->mobile) && env('FAST2SMS_API_KEY')) {
            $this->sendOtpSms($user->mobile, $otp);
        }

        Log::info('Registration OTP resent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        $message = 'A new OTP has been sent to your email';
        if (!empty($user->mobile) && env('FAST2SMS_API_KEY')) {
            $message .= ' and mobile';
        }
        $message .= '.';

        return back()->with('status', $message);
    }

    /**
     * Send OTP via beautiful HTML email
     */
    private function sendOtpEmail(User $user, string $otp): void
    {
        try {
            Mail::send('emails.otp', [
                'otp' => $otp,
                'purpose' => 'Registration',
                'name' => $user->name,
                'email' => $user->email,
                'year' => date('Y')
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('🔐 ERP Registration OTP Verification')
                        ->priority(1);
            });

            Log::info('Registration OTP email sent', ['user' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration OTP email', [
                'user' => $user->email,
                'error' => $e->getMessage()
            ]);

            // Fallback to raw email
            $this->sendRawOtpEmail($user, $otp);
        }
    }

    /**
     * Send raw OTP email as fallback
     */
    private function sendRawOtpEmail(User $user, string $otp): void
    {
        try {
            Mail::raw(
                "Hello {$user->name},\n\n" .
                "Your ERP Registration OTP is: {$otp}\n\n" .
                "This OTP is valid for 5 minutes.\n" .
                "If you did not request this, please ignore this email.\n\n" .
                "© " . date('Y') . " ERP System",
                function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject("ERP Registration OTP");
                }
            );
        } catch (\Exception $e) {
            Log::error('Fallback registration email failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * 📲 Send OTP via Fast2SMS
     */
    private function sendOtpSms($mobile, $otp): void
    {
        try {
            $response = Http::withHeaders([
                'authorization' => env('FAST2SMS_API_KEY'),
                'accept' => 'application/json',
            ])->post('https://www.fast2sms.com/dev/bulkV2', [
                'route' => 'otp',
                'variables_values' => $otp,
                'numbers' => $mobile, // 10 digit only
            ]);

            if ($response->successful()) {
                Log::info('SMS OTP sent', ['mobile' => substr($mobile, 0, 4) . '****']);
            } else {
                Log::warning('SMS OTP failed', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Fast2SMS OTP Error: ' . $e->getMessage());
        }
    }

    /**
     * Create employee record for the user
     */
    private function createEmployeeRecord(User $user): void
    {
        try {
            if (!\App\Models\Employee::where('user_id', $user->id)->exists()) {
                \App\Models\Employee::create([
                    'user_id' => $user->id,
                    'employee_code' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'status' => 1,
                ]);

                Log::info('Employee record created for user', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create employee record', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if user can resend OTP (60-second cooldown)
     */
    private function canResendOtp(User $user): bool
    {
        if (!$user->otp_last_sent_at) {
            return true;
        }

        return Carbon::parse($user->otp_last_sent_at)->diffInSeconds(now()) >= 60;
    }

    /**
     * Get remaining seconds for resend cooldown
     */
    private function getResendWaitSeconds(User $user): int
    {
        if (!$user->otp_last_sent_at) {
            return 0;
        }

        $elapsed = Carbon::parse($user->otp_last_sent_at)->diffInSeconds(now());
        return max(0, 60 - $elapsed);
    }

    /**
     * Check if account is locked
     */
    private function isAccountLocked(User $user): bool
    {
        if ($user->locked_until && now()->lessThan($user->locked_until)) {
            return true;
        }

        // Reset lock if expired
        if ($user->locked_until && now()->greaterThan($user->locked_until)) {
            $user->locked_until = null;
            $user->login_attempts = 0;
            $user->otp_attempts = 0;
            $user->save();
        }

        return false;
    }

    /**
     * Lock account for 15 minutes
     */
    private function lockAccount(User $user): void
    {
        $user->locked_until = now()->addMinutes(15);
        $user->register_otp = null;
        $user->register_otp_expires_at = null;
        $user->login_otp = null;
        $user->otp_expires_at = null;
        $user->save();
    }
}
