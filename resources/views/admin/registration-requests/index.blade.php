@extends('layouts.app')

@section('title', 'Registration Requests')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <span class="registration-eyebrow">ADMINISTRATION</span>
            <h2 class="mt-2 mb-1">Registration Requests</h2>
            <p class="text-muted mb-0">
                Review and manage student and teacher applications.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <span class="badge bg-warning text-dark fs-6">
                Pending: {{ $registrationRequests->total() }}
            </span>
        </div>
    </div>

    {{-- Registration Requests Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Applicant Name</th>
                            <th>Email Address</th>
                            <th>Applicant Type</th>
                            <th>Email Verification</th>
                            <th>Application Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($registrationRequests as $request)

                            <tr>
                                <td>
                                    {{ $registrationRequests->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $request->user?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $request->user?->email ?? 'N/A' }}
                                </td>

                                <td>
                                    @if ($request->applicant_type === 'student')
                                        <span class="badge bg-info text-dark">
                                            Student
                                        </span>
                                    @elseif ($request->applicant_type === 'teacher')
                                        <span class="badge bg-primary">
                                            Teacher
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($request->applicant_type) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($request->user?->email_verified_at)
                                        <span class="badge bg-success">
                                            Verified
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Not Verified
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a
                                        href="{{ route('admin.registration-requests.show', $request) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        View Details
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted"
                                       style="font-size: 40px;"></i>

                                    <h5 class="mt-3">No Pending Requests</h5>

                                    <p class="text-muted mb-0">
                                        There are currently no registration applications awaiting approval.
                                    </p>
                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($registrationRequests->hasPages())
                <div class="d-flex justify-content-end mt-4">
                    {{ $registrationRequests->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection