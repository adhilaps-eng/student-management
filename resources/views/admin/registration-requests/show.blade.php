@extends('layouts.app')

@section('title', 'Applicant Details')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Heading --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <span class="registration-eyebrow">ADMINISTRATION</span>
            <h2 class="mt-2 mb-1">Applicant Details</h2>
            <p class="text-muted mb-0">
                Review the applicant's registration information.
            </p>
        </div>

        <a href="{{ route('admin.registration-requests.index') }}"
           class="btn btn-outline-secondary mt-3 mt-md-0">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Requests
        </a>
    </div>

    {{-- Applicant Information --}}
    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Personal Information</h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <small class="text-muted">Full Name</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->user?->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Email Address</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->user?->email ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Phone Number</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->phone ?? 'Not provided' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Gender</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->gender ?? 'Not provided' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Date of Birth</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->date_of_birth?->format('d M Y') ?? 'Not provided' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Applicant Type</small>
                            <div class="fw-semibold mt-1">
                                {{ ucfirst($registrationRequest->applicant_type) }}
                            </div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">Address</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->address ?? 'Not provided' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Application Information --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Application Information</h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <small class="text-muted">Application ID</small>
                            <div class="fw-semibold mt-1">
                                #{{ $registrationRequest->id }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Submitted On</small>
                            <div class="fw-semibold mt-1">
                                {{ $registrationRequest->created_at?->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Email Verification</small>
                            <div class="mt-1">
                                @if ($registrationRequest->user?->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-danger">Not Verified</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Application Status</small>
                            <div class="mt-1">
                                @if ($registrationRequest->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($registrationRequest->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($registrationRequest->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($registrationRequest->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

     {{-- Review Panel --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Application Review</h5>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Review the applicant's information and email verification
                        before making an approval decision.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($registrationRequest->status === 'pending')

                        @if ($registrationRequest->user?->email_verified_at)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Email successfully verified.
                            </div>

                            {{-- Approve Form --}}
                            <form action="{{ route('admin.registration-requests.approve', $registrationRequest) }}"
                                  method="POST"
                                  class="mb-3"
                                  onsubmit="return confirm('Approve this {{ $registrationRequest->applicant_type }} registration and activate their account?');">
                                @csrf

                                @if ($registrationRequest->applicant_type === 'teacher')
                                    <div class="mb-3">
                                        <label for="employee_code" class="form-label fw-semibold">
                                            Employee Code
                                        </label>
                                        <input type="text"
                                               name="employee_code"
                                               id="employee_code"
                                               class="form-control @error('employee_code') is-invalid @enderror"
                                               value="{{ old('employee_code', $suggestedEmployeeCode) }}"
                                               placeholder="e.g. TCH-0001">
                                        <div class="form-text">
                                            Auto-generated code is pre-filled. You may customize it if needed.
                                        </div>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-lg me-1"></i>
                                    Approve &amp; Activate Account
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Email verification is still pending. Approval is disabled until verified.
                            </div>
                        @endif

                        <hr class="my-3">

                        {{-- Reject Form --}}
                        <form action="{{ route('admin.registration-requests.reject', $registrationRequest) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to reject this application?');">
                            @csrf

                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label small text-muted">
                                    Rejection Note (Optional)
                                </label>
                                <textarea name="rejection_reason"
                                          id="rejection_reason"
                                          rows="2"
                                          class="form-control"
                                          placeholder="Optional reason for rejecting...">{{ old('rejection_reason') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-lg me-1"></i>
                                Reject Application
                            </button>
                        </form>

                    @else

                        <div class="alert alert-secondary mb-0">
                            This application has already been reviewed
                            ({{ ucfirst($registrationRequest->status) }}).
                        </div>

                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
@endsection