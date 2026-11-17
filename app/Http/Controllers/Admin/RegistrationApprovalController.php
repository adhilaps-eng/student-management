<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationRequest;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RegistrationApprovalController extends Controller
{
    // Display pending registration requests
    public function index(): View
    {
        $registrationRequests = RegistrationRequest::with(
            'user:id,name,email,email_verified_at'
        )
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view(
            'admin.registration-requests.index',
            compact('registrationRequests')
        );
    }

    // Display individual registration request
    public function show(RegistrationRequest $registrationRequest): View
    {
        $registrationRequest->load(
            'user:id,name,email,email_verified_at'
        );

        $suggestedEmployeeCode = $registrationRequest->applicant_type === 'teacher'
            ? Teacher::generateEmployeeCode()
            : null;

        return view(
            'admin.registration-requests.show',
            compact('registrationRequest', 'suggestedEmployeeCode')
        );
    }

    // Approve registration request
    public function approve(Request $request, RegistrationRequest $registrationRequest): RedirectResponse
    {
        if ($registrationRequest->status !== 'pending') {
            return redirect()
                ->route('admin.registration-requests.show', $registrationRequest)
                ->with('error', 'This application has already been reviewed.');
        }

        $user = $registrationRequest->user;

        if (! $user) {
            return redirect()
                ->route('admin.registration-requests.index')
                ->with('error', 'Associated user account could not be found.');
        }

        if (! $user->email_verified_at) {
            return redirect()
                ->route('admin.registration-requests.show', $registrationRequest)
                ->with('error', 'Cannot approve an application before the applicant verifies their email address.');
        }

        if ($registrationRequest->applicant_type === 'teacher') {
            $request->validate([
                'employee_code' => ['nullable', 'string', 'max:50', 'unique:teachers,employee_code'],
            ]);
        }

        DB::transaction(function () use ($request, $registrationRequest, $user) {
            // 1. Activate User account
            $user->update([
                'is_active' => true,
            ]);

            // 2. Create or link profile based on applicant type
            if ($registrationRequest->applicant_type === 'student') {
                $existingStudent = Student::where('user_id', $user->id)
                    ->orWhere(function ($query) use ($user) {
                        $query->whereNull('user_id')
                            ->where('email', $user->email);
                    })
                    ->first();

                if ($existingStudent) {
                    $existingStudent->update([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'phone' => $registrationRequest->phone ?? $existingStudent->phone,
                        'gender' => $registrationRequest->gender ?? $existingStudent->gender,
                        'date_of_birth' => $registrationRequest->date_of_birth ?? $existingStudent->date_of_birth,
                        'address' => $registrationRequest->address ?? $existingStudent->address,
                        'status' => true,
                    ]);
                } else {
                    Student::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $registrationRequest->phone,
                        'gender' => $registrationRequest->gender,
                        'date_of_birth' => $registrationRequest->date_of_birth,
                        'address' => $registrationRequest->address,
                        'admission_date' => now()->toDateString(),
                        'status' => true,
                    ]);
                }
            } elseif ($registrationRequest->applicant_type === 'teacher') {
                $employeeCode = filled($request->input('employee_code'))
                    ? trim($request->input('employee_code'))
                    : Teacher::generateEmployeeCode();

                Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_code' => $employeeCode,
                        'phone' => $registrationRequest->phone,
                        'gender' => $registrationRequest->gender,
                        'date_of_birth' => $registrationRequest->date_of_birth,
                        'address' => $registrationRequest->address,
                        'joining_date' => now()->toDateString(),
                        'status' => true,
                    ]
                );
            }

            // 3. Record approval metadata
            $registrationRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.registration-requests.index')
            ->with('success', ucfirst($registrationRequest->applicant_type) . ' application approved and account activated.');
    }

    // Reject registration request
    public function reject(Request $request, RegistrationRequest $registrationRequest): RedirectResponse
    {
        if ($registrationRequest->status !== 'pending') {
            return redirect()
                ->route('admin.registration-requests.show', $registrationRequest)
                ->with('error', 'This application has already been reviewed.');
        }

        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $registrationRequest) {
            if ($registrationRequest->user) {
                $registrationRequest->user->update([
                    'is_active' => false,
                ]);
            }

            $updateData = [
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ];

            if (Schema::hasColumn('registration_requests', 'rejection_reason')) {
                $updateData['rejection_reason'] = $request->input('rejection_reason');
            }

            $registrationRequest->update($updateData);
        });

        return redirect()
            ->route('admin.registration-requests.index')
            ->with('success', 'Registration application has been rejected.');
    }
}