@extends('layouts.app')

@section('page-title', 'Change Password')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="margin-bottom: 10px;">🔑 Change Password</h2>
            <p style="color: #6b7280; margin-bottom: 30px;">Update your account password</p>

            @if (session('status') === 'password-updated')
                <div
                    style="background: #d1fae5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; color: #065f46;">
                    ✅ Password updated successfully!
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; color: #991b1b;">
                    @foreach ($errors->all() as $error)
                        <div>❌ {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update-password') }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Current
                        Password</label>
                    <input type="password" name="current_password" required
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">New
                        Password</label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px;">
                    <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">Password must be at least 8 characters.</p>
                </div>

                <div style="margin-bottom: 25px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Confirm
                        New Password</label>
                    <input type="password" name="password_confirmation" required
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px;">
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit"
                        style="flex: 1; padding: 12px; background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        Update Password
                    </button>
                    <a href="{{ route('profile.edit') }}"
                        style="flex: 1; padding: 12px; background: #f3f4f6; color: #374151; text-align: center; text-decoration: none; border-radius: 10px; font-weight: 600;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
