@extends('layouts.app')

@section('page-title', 'Security Settings')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="margin: 0;">🔐 Security Settings</h2>
                <a href="{{ route('profile.edit') }}" style="color: #6b7280; text-decoration: none;">← Back to Profile</a>
            </div>

            <div style="border-top: 1px solid #e5e7eb; padding-top: 25px;">
                <!-- 2FA Section -->
                <div
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 25px;">
                    <div>
                        <h3 style="margin-bottom: 5px;">Two-Factor Authentication</h3>
                        <p style="color: #6b7280; font-size: 14px;">
                            @if ($twoFactorEnabled)
                                <span style="color: #10b981;">✓ Enabled</span> - Your account is protected
                            @else
                                <span style="color: #ef4444;">✗ Disabled</span> - Your account is not protected
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('2fa.setup') }}"
                        style="text-decoration: none; padding: 10px 24px; background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; border-radius: 12px; font-weight: 600;">
                        @if ($twoFactorEnabled)
                            Manage 2FA
                        @else
                            Enable 2FA
                        @endif
                    </a>
                </div>

                @if ($twoFactorEnabled && $remainingRecoveryCodes > 0)
                    <div style="margin-bottom: 25px; padding: 15px; background: #fef3c7; border-radius: 12px;">
                        <p style="font-size: 13px; color: #92400e; margin: 0;">
                            <i class="fas fa-info-circle"></i> You have <strong>{{ $remainingRecoveryCodes }}</strong>
                            recovery codes remaining.
                            <a href="{{ route('2fa.recovery.generate') }}"
                                style="color: #d97706; font-weight: 600;">Generate new codes</a>
                        </p>
                    </div>
                @endif

                <hr style="margin: 25px 0; border-color: #e5e7eb;">

                <!-- Change Password Section -->
                <div
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 25px;">
                    <div>
                        <h3 style="margin-bottom: 5px;">Change Password</h3>
                        <p style="color: #6b7280; font-size: 14px;">Update your account password</p>
                    </div>
                    <a href="{{ route('profile.change-password') }}"
                        style="text-decoration: none; padding: 10px 24px; background: #f3f4f6; color: #374151; border-radius: 12px; font-weight: 600; border: 1px solid #e5e7eb;">
                        Change Password →
                    </a>
                </div>

                <hr style="margin: 25px 0; border-color: #e5e7eb;">

                <!-- Activity Log Section -->
                <div
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 25px;">
                    <div>
                        <h3 style="margin-bottom: 5px;">Activity Log</h3>
                        <p style="color: #6b7280; font-size: 14px;">View your account activity history</p>
                    </div>
                    <a href="{{ route('profile.activity') }}"
                        style="text-decoration: none; padding: 10px 24px; background: #f3f4f6; color: #374151; border-radius: 12px; font-weight: 600; border: 1px solid #e5e7eb;">
                        View Log →
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
