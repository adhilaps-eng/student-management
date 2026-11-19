@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $roleSlug = strtolower((string) ($role ?? $user?->role?->slug ?? ''));
    $roleId = $user?->role_id;

    $isAdmin = in_array($roleSlug, ['super-admin', 'admin'], true) || in_array((int) $roleId, [1, 2], true);
    $isTeacher = $roleSlug === 'teacher' || (int) $roleId === 3;
    $isStudent = $roleSlug === 'student' || (int) $roleId === 4;

    $studentRecord = $data['student'] ?? \App\Models\Student::where('user_id', $user->id)->first();
    $teacherRecord = $data['teacher'] ?? \App\Models\Teacher::where('user_id', $user->id)->first();
    $totalStudentsCount = $data['totalStudents'] ?? \App\Models\Student::count();
    $totalTeachersCount = $data['totalTeachers'] ?? \App\Models\Teacher::count();
    $pendingRequestsCount = $data['pendingRequests'] ?? \App\Models\RegistrationRequest::where('status', 'pending')->count();
@endphp

<div class="mb-4">
    {{-- Unified Dashboard Header (Same Route for All Roles) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary mb-2">
                    Unified Dashboard · {{ strtoupper($roleSlug ?: ($user?->role?->name ?? 'USER')) }}
                </span>
                <h3 class="mb-1">Welcome back, {{ $user->name }}!</h3>
                <p class="text-muted mb-0">
                    @if ($isAdmin)
                        Full system overview and access to all administrative features.
                    @elseif ($isTeacher)
                        Faculty workspace — view student lists and manage student records.
                    @else
                        Student workspace — view your profile details and browse the student list.
                    @endif
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @if ($isAdmin)
                    <a href="{{ url('/admin/teachers/create') }}" class="btn btn-success">
                        <i class="bi bi-person-plus-fill me-1"></i> Add Teacher
                    </a>
                @endif

                @if ($isAdmin || $isTeacher)
                    <a href="{{ url('/students/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add Student
                    </a>
                @endif

                <a href="{{ url('/students') }}" class="btn btn-outline-primary">
                    <i class="bi bi-people me-1"></i>
                    {{ $isStudent ? 'View Student List' : 'Manage Students' }}
                </a>
            </div>
        </div>
    </div>

    {{-- 1. ADMIN / SUPER ADMIN VIEW --}}
    @if ($isAdmin)
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Students</h6>
                        <h3 class="mb-2">{{ $totalStudentsCount }}</h3>
                        <a href="{{ url('/students') }}" class="small text-decoration-none">Go to Students →</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Teachers</h6>
                        <h3 class="mb-2">{{ $totalTeachersCount }}</h3>
                        <a href="{{ route('admin.teachers.index') }}" class="small text-decoration-none">Go to Teachers →</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Pending Registrations</h6>
                        <h3 class="mb-2">{{ $pendingRequestsCount }}</h3>
                        <a href="{{ route('admin.registration-requests.index') }}" class="small text-decoration-none">Review Requests →</a>
                    </div>
                </div>
            </div>
        </div>

    {{-- 2. TEACHER VIEW --}}
    @elseif ($isTeacher)
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Employee Code</h6>
                        <h5 class="mb-0">{{ $teacherRecord->employee_code ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Student CRUD Access</h6>
                        <h5 class="text-success mb-0">View, Add, Edit & Delete</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Student Directory</h6>
                        <a href="{{ url('/students') }}" class="btn btn-sm btn-primary mt-1">
                            Open Student Management
                        </a>
                    </div>
                </div>
            </div>
        </div>

    {{-- 3. STUDENT VIEW --}}
    @else
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Student Directory</h6>
                        <p class="text-muted small mb-2">View the student list (read-only).</p>
                        <a href="{{ url('/students') }}" class="btn btn-sm btn-outline-primary">
                            View Student List
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Account Status</h6>
                        @if ($studentRecord?->status)
                            <span class="badge bg-success-subtle text-success">Active</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Standard Account</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Student Profile</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="small text-muted">Name</div>
                        <div class="fw-semibold">{{ $studentRecord->name ?? $user->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold">{{ $studentRecord->email ?? $user->email }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Phone</div>
                        <div class="fw-semibold">{{ $studentRecord->phone ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Gender</div>
                        <div class="fw-semibold">{{ $studentRecord->gender ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Date of Birth</div>
                        <div class="fw-semibold">{{ $studentRecord->date_of_birth ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Address</div>
                        <div class="fw-semibold">{{ $studentRecord->address ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection