
@extends('layouts.app')

@section('title', 'Dashboard | Student Management System')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role?->slug;
@endphp

<div class="dashboard-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="dashboard-title">Dashboard</h1>
        <p class="dashboard-subtitle mb-0">
            Welcome back, {{ $user->name }}. Here's your workspace.
        </p>
    </div>

    <span class="dashboard-role-badge">
        <i class="bi bi-shield-check me-1"></i>
        {{ $user->role?->name ?? 'User' }}
    </span>
</div>

@if (in_array($role, ['super-admin', 'admin'], true))

    <div class="dashboard-welcome-card mb-4">
        <div>
            <span class="dashboard-eyebrow">STUDENT MANAGEMENT SYSTEM</span>
            <h2 class="mt-2">Manage your school in one place</h2>
            <p class="mb-3">
                Manage student records and access the features available to your role.
            </p>

            <a href="{{ url('/students') }}" class="btn btn-primary">
                <i class="bi bi-people-fill me-2"></i>
                View Students
            </a>
        </div>
        <div class="dashboard-welcome-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
    </div>

    <h5 class="dashboard-section-title mb-3">Quick Access</h5>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-4">
            <a href="{{ url('/students') }}" class="dashboard-module-card">
                <div class="dashboard-module-icon blue">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h6>Student Management</h6>
                    <p>View, search and manage student records.</p>
                    <span>Open Students <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="dashboard-module-card">
                <div class="dashboard-module-icon purple">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="flex-grow-1">
                    <h6>Teacher Management</h6>
                    <p>Teacher profiles and account management.</p>
                    <span class="module-status">Planned module</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="dashboard-module-card">
                <div class="dashboard-module-icon green">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h6>Classes & Academic Years</h6>
                    <p>Organize class sections and academic years.</p>
                    <span class="module-status">Planned module</span>
                </div>
            </div>
        </div>
    </div>

@elseif ($role === 'teacher')

    <div class="dashboard-role-card">
        <div class="dashboard-module-icon purple mb-3">
            <i class="bi bi-person-workspace"></i>
        </div>
        <h4>Teacher Dashboard</h4>
        <p>
            Your assigned classes, student attendance and academic activities
            will be available here as those modules are implemented.
        </p>
    </div>

@elseif ($role === 'student')

    <div class="dashboard-role-card">
        <div class="dashboard-module-icon blue mb-3">
            <i class="bi bi-person-fill"></i>
        </div>
        <h4>Student Dashboard</h4>
        <p>
            Your class, attendance, examination results and academic
            information will be available here as those modules are implemented.
        </p>
    </div>

@else

    <div class="alert alert-warning">
        Your account does not have a recognized role. Please contact the administrator.
    </div>

@endif
@endsection

