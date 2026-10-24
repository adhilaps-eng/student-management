
@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Edit Student</h2>
        <p class="text-muted mb-0">Update student information</p>
    </div>

    <a href="/students" class="btn btn-outline-secondary">
        Back to Student List
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form method="POST" action="/students/{{ $student->id }}">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $student->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $student->email) }}"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" id="phone" name="phone"
                           value="{{ old('phone', $student->phone) }}"
                           class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                    <select id="gender" name="gender"
                            class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           value="{{ old('date_of_birth', $student->date_of_birth) }}"
                           class="form-control @error('date_of_birth') is-invalid @enderror">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
                    <input type="date" id="admission_date" name="admission_date"
                           value="{{ old('admission_date', $student->admission_date) }}"
                           class="form-control @error('admission_date') is-invalid @enderror">
                    @error('admission_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" rows="3"
                              class="form-control @error('address') is-invalid @enderror">{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="status" name="status"
                            class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', (string) $student->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', (string) $student->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Update Student
                </button>

                <a href="/students" class="btn btn-light border">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
