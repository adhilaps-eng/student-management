@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="registration-page">

    <div class="registration-heading text-center mb-4">
        <span class="registration-eyebrow">EMAIL VERIFICATION</span>

        <div class="mt-3 mb-3">
            <i class="bi bi-envelope-check"
               style="font-size: 48px; color: #078f91;"></i>
        </div>

        <h1>Verify Your Email</h1>

        <p class="text-muted">
            We've sent a six-digit verification code to your email address.
        </p>
    </div>

    <div class="login-card mx-auto" style="max-width: 500px;">

        <div class="text-center mb-4">
            <p class="text-muted mb-1">Verification code sent to</p>
            <strong>{{ $email ?? 'Your registered email address' }}</strong>
        </div>

        @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- OTP Verification Form --}}
        <form method="POST" action="{{ route('register.verify-otp.store') }}">
            @csrf

            <div class="mb-3">
                <label for="otp" class="form-label">
                    Enter 6-Digit OTP
                </label>

                <input
                    type="text"
                    id="otp"
                    name="otp"
                    class="form-control text-center @error('otp') is-invalid @enderror"
                    placeholder="Enter OTP"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    value="{{ old('otp') }}"
                    style="font-size: 24px; letter-spacing: 8px; font-weight: 600;"
                    required
                    autofocus
                >

                @error('otp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Verify OTP
            </button>
        </form>

        {{-- Resend OTP Form --}}
        <div class="text-center mt-4">
            <p class="text-muted mb-2">
                Didn't receive the verification code?
            </p>

            <form method="POST" action="{{ route('register.resend-otp') }}">
                @csrf

                <button type="submit" class="btn btn-link p-0">
                    Resend OTP
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('register.student') }}"
               class="registration-login-link">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Student Registration
            </a>
        </div>

    </div>
</div>
@endsection