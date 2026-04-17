@extends('layouts.app')

@section('page-title', 'Activity Log')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="margin-bottom: 10px;">📊 Activity Log</h2>
            <p style="color: #6b7280; margin-bottom: 30px;">Your account activity history</p>

            <div style="border-top: 1px solid #e5e7eb; padding-top: 25px;">
                <div style="margin-bottom: 25px;">
                    <h3 style="margin-bottom: 15px;">Login History</h3>
                    <div style="background: #f9fafb; padding: 15px; border-radius: 12px;">
                        <p style="margin-bottom: 10px;">
                            <strong>Last Login:</strong>
                            {{ $lastLoginAt ? \Carbon\Carbon::parse($lastLoginAt)->format('F d, Y h:i A') : 'Never' }}
                        </p>
                        <p>
                            <strong>Last IP Address:</strong>
                            {{ $lastLoginIp ?? 'Unknown' }}
                        </p>
                    </div>
                </div>

                <div>
                    <h3 style="margin-bottom: 15px;">Account Information</h3>
                    <div style="background: #f9fafb; padding: 15px; border-radius: 12px;">
                        <p style="margin-bottom: 10px;">
                            <strong>Account Created:</strong>
                            {{ auth()->user()->created_at->format('F d, Y h:i A') }}
                        </p>
                        <p>
                            <strong>2FA Status:</strong>
                            @if (auth()->user()->two_factor_enabled)
                                <span style="color: #10b981;">✓ Enabled</span>
                            @else
                                <span style="color: #6b7280;">✗ Disabled</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <a href="{{ route('profile.edit') }}" style="color: #3b82f6; text-decoration: none;">← Back to Profile</a>
            </div>
        </div>
    </div>
@endsection
