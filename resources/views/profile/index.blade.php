@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="margin: 0;">👤 My Profile</h2>
                <a href="{{ route('profile.edit') }}"
                    style="background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; padding: 10px 20px; border-radius: 12px; text-decoration: none; font-weight: 600;">
                    ✏️ Edit Profile
                </a>
            </div>

            <div style="border-top: 1px solid #e5e7eb; padding-top: 25px;">
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <div style="flex: 1; min-width: 200px;">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Full
                                Name</label>
                            <div style="font-size: 16px; font-weight: 600; color: #1f2937;">{{ $user->name }}</div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Email
                                Address</label>
                            <div style="font-size: 16px; color: #1f2937;">{{ $user->email }}</div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Mobile
                                Number</label>
                            <div style="font-size: 16px; color: #1f2937;">{{ $user->mobile ?? 'Not provided' }}</div>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Role</label>
                            <div style="font-size: 16px; font-weight: 600;">
                                <span
                                    style="background: #eef2ff; padding: 4px 12px; border-radius: 20px;">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Member
                                Since</label>
                            <div style="font-size: 16px; color: #1f2937;">{{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 12px; color: #6b7280; margin-bottom: 5px;">Last
                                Login</label>
                            <div style="font-size: 16px; color: #1f2937;">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid #e5e7eb; margin-top: 20px; padding-top: 20px;">
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="{{ route('profile.security') }}"
                        style="color: #3b82f6; text-decoration: none; font-weight: 600;">🔐 Security Settings</a>
                    <a href="{{ route('profile.change-password') }}"
                        style="color: #3b82f6; text-decoration: none; font-weight: 600;">🔑 Change Password</a>
                    <a href="{{ route('profile.activity') }}"
                        style="color: #3b82f6; text-decoration: none; font-weight: 600;">📊 Activity Log</a>
                </div>
            </div>
        </div>
    </div>
@endsection
