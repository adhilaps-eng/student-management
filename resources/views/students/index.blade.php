@extends('layouts.app')

@section('title', 'Student List')

@section('content')
@php
    $user = auth()->user();
    $roleSlug = strtolower((string) ($role ?? $user?->role?->slug ?? ''));
    $roleId = (int) ($user?->role_id ?? 0);

    $isAdmin = in_array($roleSlug, ['super-admin', 'admin'], true) || in_array($roleId, [1, 2], true);
    $isTeacher = $roleSlug === 'teacher' || $roleId === 3;
    $canManageStudents = $isAdmin || $isTeacher;
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h3 class="mb-0">
        {{ $canManageStudents ? 'Student Management' : 'Student List' }}
    </h3>

    <div class="d-flex align-items-center gap-2">
        @if ($isAdmin)
            <a href="{{ url('/admin/teachers/create') }}" class="btn btn-success text-nowrap">
                <i class="bi bi-plus-lg me-1"></i> Add Teacher
            </a>
        @endif

        @if ($isAdmin || $isTeacher)
            <a href="{{ url('/students/create') }}" class="btn btn-primary text-nowrap">
                <i class="bi bi-plus-lg me-1"></i> Add Student
            </a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Students</h6>
                <h3 class="mb-0">{{ $totalStudentsCount ?? ($totalStudents ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Active Students</h6>
                <h3 class="text-success mb-0">{{ $activeStudentsCount ?? ($activeStudents ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Inactive Students</h6>
                <h3 class="text-secondary mb-0">{{ $inactiveStudentsCount ?? ($inactiveStudents ?? 0) }}</h3>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ url('/students') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-md-6 col-lg-5">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name or email..."
                value="{{ $search ?? '' }}"
            >
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                Search
            </button>
        </div>

        <div class="col-auto">
            <a href="{{ url('/students') }}" class="btn btn-outline-secondary">
                Clear
            </a>
        </div>
    </div>
</form>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Student List</h5>
        @unless ($canManageStudents)
            <span class="badge bg-light text-dark border">Read-Only View</span>
        @endunless
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Status</th>
                        @if ($canManageStudents)
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $index => $student)
                        <tr>
                            <td class="ps-3">{{ $students->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone ?? '—' }}</td>
                            <td>{{ $student->gender ?? '—' }}</td>
                            <td>
                                @if ($student->status)
                                    <span class="badge bg-success-subtle text-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            @if ($canManageStudents)
                                <td class="text-center">
                                    <a href="{{ url('/students/' . $student->id . '/edit') }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ url('/students/' . $student->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canManageStudents ? 7 : 6 }}" class="text-center py-5 text-muted">
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <span class="text-muted small">
                Total students: {{ $students->total() }}
            </span>

            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection