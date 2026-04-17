@extends('layouts.app')

@section('page-title', 'Edit Profile')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="margin: 0;">👤 Edit Profile</h2>
                <a href="{{ route('profile.security') }}" style="color: #3b82f6; text-decoration: none;">🔐 Security
                    Settings</a>
            </div>

            @if (session('status') === 'profile-updated')
                <div
                    style="background: #d1fae5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; color: #065f46;">
                    ✅ Profile updated successfully!
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

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label
                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Mobile
                        (Optional)</label>
                    <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 10px;">
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit"
                        style="flex: 1; padding: 12px; background: linear-gradient(135deg, #3b82f6, #1e40af); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        Save Changes
                    </button>
                    <a href="{{ route('dashboard') }}"
                        style="flex: 1; padding: 12px; background: #f3f4f6; color: #374151; text-align: center; text-decoration: none; border-radius: 10px; font-weight: 600;">
                        Cancel
                    </a>
                </div>
            </form>

            <hr style="margin: 30px 0; border-color: #e5e7eb;">

            <div>
                <h3 style="margin-bottom: 15px;">Account Information</h3>
                <div style="background: #f9fafb; padding: 15px; border-radius: 12px;">
                    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Member since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                    <p><strong>Last login:</strong>
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
