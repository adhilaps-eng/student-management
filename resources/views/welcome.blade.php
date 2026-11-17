@extends('layouts.app')

@section('title', 'Welcome | Student Management System')

@section('content')


<section class="landing-hero rounded-4 p-4 p-md-5 mb-5">
    <div class="row align-items-center g-4 g-lg-5">

        <!-- Left column: Heading and button -->
        <div class="col-12 col-lg-6">
            <span class="landing-eyebrow">
                STUDENT MANAGEMENT SYSTEM
            </span>

            <h1 class="landing-title mt-3">
                A smarter way to manage students and learning.
            </h1>

            <p class="landing-description mt-3">
                Manage student records, teacher information, classes
                and academic years in one organized application.
            </p>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="#features" class="btn btn-outline-primary btn-lg">
                    Explore Features
                    <i class="bi bi-arrow-down-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Right column: Image -->
        <div class="col-12 col-lg-6">
            <div class="landing-image-wrap">

                <img
                    src="https://images.pexels.com/photos/8199659/pexels-photo-8199659.jpeg?auto=compress&cs=tinysrgb&w=1200"
                    alt="Students studying together in a library"
                    class="landing-hero-image"
                >

                <div class="landing-image-overlay">
                    <div class="landing-image-caption-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div>
                        <h5>Learning starts here</h5>
                        <p>Building knowledge. Inspiring futures.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<section id="features" class="mb-5">

    <div class="text-center mb-4">
        <h2 class="section-title">Features of Our System</h2>
        <p class="text-muted">
            A central place for managing important academic information.
        </p>
    </div>

    <div class="row g-4">

        <div class="col-md-6 col-xl-3">
            <div class="feature-card h-100">
                <div class="feature-number">01</div>
                <h5>Student Management</h5>
                <p class="text-muted mb-0">
                    Add, view, search and update student records.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="feature-card h-100">
                <div class="feature-number">02</div>
                <h5>Teacher Management</h5>
                <p class="text-muted mb-0">
                    Maintain teacher details and login accounts.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="feature-card h-100">
                <div class="feature-number">03</div>
                <h5>Class Management</h5>
                <p class="text-muted mb-0">
                    Organize classes, sections and academic years.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="feature-card h-100">
                <div class="feature-number">04</div>
                <h5>Secure Access</h5>
                <p class="text-muted mb-0">
                    Separate access for Super Admin, Admin,
                    Teachers and Students.
                </p>
            </div>
        </div>

    </div>

</section>

<section class="landing-bottom rounded-4 p-4 p-md-5 text-center">
    <h2 class="section-title">Ready to access the system?</h2>

    <p class="text-muted mt-3">
        Sign in to access the dashboard and the features available
        for your account.
    </p>

    
</section>

<footer class="text-center text-muted py-4">
    <small>
        &copy; {{ date('Y') }} Student Management System
    </small>
</footer>

@endsection