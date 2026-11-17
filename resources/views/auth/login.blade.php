
@extends('layouts.app')

@section('title', 'Login | Student Management System')

@section('content')
<div class="login-layout">
    <div class="login-card">

        <div class="login-heading">
            <div class="login-icon">SMS</div>
            <h1>Welcome back!</h1>
            <p>Sign in to access your Student Management System.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="form-check mb-4">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    value="1"
                    class="form-check-input"
                >
                <label for="remember" class="form-check-label">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Sign In
            </button>
        </form>

        <div class="login-footer">
            Secure access based on your assigned role.
        </div>

    </div>
</div>
@endsection