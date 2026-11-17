
@extends('layouts.app')

@section('title', 'Register | Student Management System')

@section('content')

<div class="registration-page">

    <div class="registration-heading text-center mb-5">
        <span class="registration-eyebrow">JOIN OUR PORTAL</span>

        <h1 class="mt-3">Create Your Account</h1>

        <p class="text-muted">
            Select your account type to continue with registration.
        </p>
    </div>

    <div class="row justify-content-center g-4">

        <!-- Student Registration -->
        <div class="col-md-6 col-lg-5">
            <a href="{{ route('register.student') }}"
               class="registration-option-link">

                <div class="registration-option-card h-100">

                    <div class="registration-option-icon student-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <h4>Student Registration</h4>

                    <p>
                        Register as a student to access your profile,
                        class details, attendance and academic results.
                    </p>

                    <ul>
                        <li>Access your student profile</li>
                        <li>View class and academic details</li>
                        <li>Check attendance and results</li>
                    </ul>

                    <div class="registration-note">
                        <i class="bi bi-info-circle me-2"></i>
                        Existing students can connect their records
                        after email verification.
                    </div>

                    <div class="registration-card-action mt-4">
                        Continue as Student
                        <i class="bi bi-arrow-right ms-2"></i>
                    </div>

                </div>
            </a>
        </div>

        <!-- Teacher Registration -->
        <div class="col-md-6 col-lg-5">
            <a href="{{ route('register.teacher') }}"
               class="registration-option-link">

                <div class="registration-option-card h-100">

                    <div class="registration-option-icon teacher-icon">
                        <i class="bi bi-person-workspace"></i>
                    </div>

                    <h4>Teacher Registration</h4>

                    <p>
                        Register as a teacher to access your profile,
                        assigned classes and academic activities.
                    </p>

                    <ul>
                        <li>Access your teacher profile</li>
                        <li>View assigned classes</li>
                        <li>Manage academic activities</li>
                    </ul>

                    <div class="registration-note">
                        <i class="bi bi-info-circle me-2"></i>
                        Teacher accounts require Admin approval
                        after email verification.
                    </div>

                    <div class="registration-card-action mt-4">
                        Continue as Teacher
                        <i class="bi bi-arrow-right ms-2"></i>
                    </div>

                </div>
            </a>
        </div>

    </div>

    <div class="text-center mt-5">
        <p class="text-muted">
            Already have an account?
            <a href="{{ route('login') }}" class="registration-login-link">
                Login here
            </a>
        </p>
    </div>

</div>

@endsection

