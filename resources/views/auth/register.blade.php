@extends('layouts.app')

@section('content')
    <div style="max-width:420px;background:#fff;padding:25px;border-radius:8px;margin:auto">

        <h2 style="margin-bottom:20px;">📝 Staff Registration</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div style="margin-bottom:15px;">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:8px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;padding:8px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Password</label>
                <input type="password" name="password" required style="width:100%;padding:8px;">
            </div>

            <div style="margin-bottom:20px;">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required style="width:100%;padding:8px;">
            </div>

            <button type="submit"
                style="background:#111827;color:#fff;padding:10px 16px;border:none;border-radius:6px;width:100%;">
                Register
            </button>

            <p style="margin-top:15px;text-align:center;">
                Already registered?
                <a href="{{ route('login') }}">Login</a>
            </p>

        </form>
    </div>
@endsection
