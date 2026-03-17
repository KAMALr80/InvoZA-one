<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\View\View;

class OtpController extends Controller
{
    /**
     * Show OTP verification page
     */
    public function show(): View|RedirectResponse
    {
        // Check if OTP session exists
        if (!session()->has('otp_user_id') || !session()->has('otp_purpose')) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Session expired. Please login again.']);
        }

        // Check if session is still valid (within 10 minutes)
        $sentAt = session('otp_sent_at', 0);
        if (Carbon::createFromTimestamp($sentAt)->diffInMinutes(now()) > 10) {
            Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email', 'otp_expires_at']);
            return redirect()->route('login')
                ->withErrors(['otp' => 'OTP session expired. Please login again.']);
        }

        $userId = session('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);
            return redirect()->route('login')
                ->withErrors(['otp' => 'User not found. Please login again.']);
        }

        // Get remaining time for timer
        $expiresAt = session('otp_expires_at', now()->addMinutes(5)->timestamp);
        $remainingSeconds = max(0, $expiresAt - now()->timestamp);

        return view('auth.otp', [
            'purpose' => session('otp_purpose', 'login'),
            'email' => $user->email,
            'masked_email' => $this->maskEmail($user->email),
            'remaining_seconds' => $remainingSeconds,
            'attempts_remaining' => max(0, 5 - ($user->otp_attempts ?? 0)),
            'can_resend' => $this->canResendOtp($user),
            'resend_wait_seconds' => $this->getResendWaitSeconds($user)
        ]);
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        // Check session
        if (!session()->has('otp_user_id') || !session()->has('otp_purpose')) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Session expired. Please login again.']);
        }

        $userId = session('otp_user_id');
        $purpose = session('otp_purpose');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);
            return redirect()->route('login')
                ->withErrors(['otp' => 'User not found. Please login again.']);
        }

        // Check if account is locked
        if ($this->isAccountLocked($user)) {
            Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);
            return redirect()->route('login')
                ->withErrors(['otp' => 'Account temporarily locked. Please try again later.']);
        }

        // Track OTP attempts using your column
        $user->otp_attempts = ($user->otp_attempts ?? 0) + 1;
        $user->save();

        // Check max attempts (5)
        if ($user->otp_attempts > 5) {
            $this->lockAccount($user);

            Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);

            Log::warning('Account locked due to too many OTP attempts', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            return redirect()->route('login')
                ->withErrors(['otp' => 'Too many failed attempts. Account locked for 15 minutes.']);
        }

        // Verify OTP based on purpose
        $isValid = false;

        if ($purpose === 'login') {
            $isValid = $user->login_otp &&
                      trim($request->otp) === $user->login_otp &&
                      now()->lessThanOrEqualTo(Carbon::parse($user->otp_expires_at));
        } elseif ($purpose === 'registration') {
            $isValid = $user->register_otp &&
                      trim($request->otp) === $user->register_otp &&
                      now()->lessThanOrEqualTo(Carbon::parse($user->register_otp_expires_at));
        }

        if (!$isValid) {
            $remaining = 5 - $user->otp_attempts;

            Log::info('Invalid OTP attempt', [
                'user_id' => $user->id,
                'purpose' => $purpose,
                'attempts' => $user->otp_attempts,
                'ip' => $request->ip()
            ]);

            return back()->withErrors([
                'otp' => "Invalid or expired OTP. {$remaining} attempts remaining.",
            ])->withInput();
        }

        // OTP verified successfully
        $user->otp_attempts = 0;
        $user->last_otp_verified_at = now();

        Log::info('OTP verified successfully', [
            'user_id' => $user->id,
            'purpose' => $purpose,
            'ip' => $request->ip()
        ]);

        // Handle based on purpose
        if ($purpose === 'login') {
            return $this->handleLoginSuccess($user, $request);
        } elseif ($purpose === 'registration') {
            return $this->handleRegistrationSuccess($user, $request);
        }

        Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);
        return redirect()->route('login');
    }

    /**
     * Handle successful login after OTP
     */
    private function handleLoginSuccess(User $user, Request $request): RedirectResponse
    {
        // Clear OTP data
        $user->login_otp = null;
        $user->otp_expires_at = null;

        // Update login tracking using your columns
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        // Clear OTP session
        Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email', 'otp_expires_at']);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login successful! Welcome back.');
    }

    /**
     * Handle successful registration after OTP
     */
    private function handleRegistrationSuccess(User $user, Request $request): RedirectResponse
    {
        // Clear OTP data
        $user->register_otp = null;
        $user->register_otp_expires_at = null;
        $user->email_verified_at = now();
        $user->status = 'approved';
        $user->save();

        // Create employee record
        if (!Employee::where('user_id', $user->id)->exists()) {
            try {
                Employee::create([
                    'user_id' => $user->id,
                    'employee_code' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'status' => 1,
                ]);

                Log::info('Employee record created', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create employee record', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Clear OTP session
        Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email', 'otp_expires_at']);

        return redirect()->route('login')
            ->with('status', 'Registration successful! You can now login with your credentials.');
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request): RedirectResponse
    {
        if (!session()->has('otp_user_id') || !session()->has('otp_purpose')) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Session expired. Please try again.']);
        }

        $user = User::find(session('otp_user_id'));
        $purpose = session('otp_purpose');

        if (!$user) {
            Session::forget(['otp_user_id', 'otp_purpose', 'otp_sent_at', 'otp_email']);
            return redirect()->route('login')
                ->withErrors(['otp' => 'User not found. Please try again.']);
        }

        // Check cooldown using your columns
        if (!$this->canResendOtp($user)) {
            $waitSeconds = $this->getResendWaitSeconds($user);
            return back()->withErrors([
                'otp' => "Please wait {$waitSeconds} seconds before resending OTP.",
            ]);
        }

        // Generate new secure OTP
        $otp = sprintf("%06d", random_int(0, 999999));

        // Save based on purpose using your columns
        if ($purpose === 'login') {
            $user->login_otp = $otp;
            $user->otp_expires_at = now()->addMinutes(5);
        } elseif ($purpose === 'registration') {
            $user->register_otp = $otp;
            $user->register_otp_expires_at = now()->addMinutes(5);
        }

        $user->otp_last_sent_at = now();
        $user->otp_attempts = 0;
        $user->save();

        // Update session
        Session::put('otp_sent_at', now()->timestamp);
        Session::put('otp_expires_at', now()->addMinutes(5)->timestamp);

        // Send OTP Email
        $this->sendOtpEmail($user, $otp, ucfirst($purpose));

        Log::info('OTP resent', [
            'user_id' => $user->id,
            'purpose' => $purpose,
            'ip' => $request->ip()
        ]);

        return back()->with('status', 'A new OTP has been sent to your email.');
    }

    /**
     * Send OTP email
     */
    private function sendOtpEmail(User $user, string $otp, string $purpose): void
    {
        try {
            Mail::send('emails.otp', [
                'otp' => $otp,
                'purpose' => $purpose,
                'name' => $user->name,
                'email' => $user->email,
                'year' => date('Y')
            ], function ($message) use ($user, $purpose) {
                $message->to($user->email, $user->name)
                        ->subject("🔐 ERP {$purpose} OTP Verification");
            });
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'user' => $user->email,
                'error' => $e->getMessage()
            ]);

            $this->sendRawOtpEmail($user, $otp, $purpose);
        }
    }

    /**
     * Send raw OTP email as fallback
     */
    private function sendRawOtpEmail(User $user, string $otp, string $purpose): void
    {
        try {
            Mail::raw(
                "Hello {$user->name},\n\n" .
                "Your ERP {$purpose} OTP is: {$otp}\n\n" .
                "This OTP is valid for 5 minutes.\n" .
                "If you did not request this, please ignore this email.\n\n" .
                "© " . date('Y') . " ERP System",
                function ($message) use ($user, $purpose) {
                    $message->to($user->email)
                            ->subject("ERP {$purpose} OTP");
                }
            );
        } catch (\Exception $e) {
            Log::error('Fallback email failed', ['error' => $e->getMessage()]);
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
        $user->login_otp = null;
        $user->otp_expires_at = null;
        $user->register_otp = null;
        $user->register_otp_expires_at = null;
        $user->save();
    }

    /**
     * Mask email for display
     */
    private function maskEmail(string $email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));

        return $maskedName . '@' . $domain;
    }
}
