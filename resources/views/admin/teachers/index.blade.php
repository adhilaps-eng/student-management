@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header section with Add Teacher button matching the student management layout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Teacher Management</h2>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-success">
            <i class="bi bi-person-plus-fill me-1"></i> Add Teacher
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Employee Code</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->employee_code }}</td>
                                <td>{{ $teacher->user->name ?? 'N/A' }}</td>
                                <td>{{ $teacher->user->email ?? 'N/A' }}</td>
                                <td>{{ $teacher->phone ?? 'N/A' }}</td>
                                <td>{{ $teacher->joining_date ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection