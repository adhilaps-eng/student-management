<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;
use Illuminate\Support\Facades\RateLimiter;

class RegistrationController extends Controller
{
    // Registration selection page
    public function show(): View
    {
        return view('auth.register');
    }

    // Student registration form
    public function showStudentForm(): View
    {
        return view('auth.register-student');
    }

    // Teacher registration form
    public function showTeacherForm(): View
    {
        return view('auth.register-teacher');
    }

    // Display OTP verification page
    public function showOtpForm(Request $request): View
    {
        return view('auth.verify-otp', [
            'email' => $request->session()->get('registration.email'),
        ]);
    }

    // Process student registration
    public function storeStudent(Request $request): RedirectResponse
    {
        // Normalize email
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        // Validate form data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check existing student record
        $existingStudent = Student::where(
            'email',
            $validated['email']
        )->first();

        // Prevent linking an already connected student record
        if ($existingStudent && $existingStudent->user_id) {
            return back()
                ->withErrors([
                    'email' => 'This student record is already connected to an account. Please contact the administrator.',
                ])
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]));
        }

        // Find Student role
        $studentRole = Role::where('slug', 'student')->first();

        if (!$studentRole) {
            return back()
                ->withErrors([
                    'email' => 'Student role is not configured. Please contact the administrator.',
                ])
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]));
        }

            // 1. Create the User login account
                $user = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                    'role_id' => $studentRole->id,
                ]);

            // 2. Automatically create the linked Student profile record
                    \App\Models\Student::create([
                        'user_id' => $user->id,
                        'admission_number' => 'ADM-' . rand(10000, 99999), // Generates a unique admission ID
                        'grade' => $request->grade ?? 'UKG', // Adjust if your form collects grade/class
                    ]);

                    // 3. Log them in and redirect to dashboard
                    auth()->login($user);

                    return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to your student portal.');

        // Generate a secure six-digit OTP
        $otpCode = (string) random_int(100000, 999999);

        try {

            // Save registration information in a database transaction
            $user = DB::transaction(function () use (
                $validated,
                $existingStudent,
                $studentRole,
                $otpCode
            ) {
                // Create inactive user account
                $user = User::create([
                    'name' => $existingStudent
                        ? $existingStudent->name
                        : $validated['name'],

                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'role_id' => $studentRole->id,
                    'is_active' => false,
                ]);

                // New students require Admin approval
                if (!$existingStudent) {

                    $user->registrationRequest()->create([
                        'applicant_type' => 'student',
                        'phone' => $validated['phone'] ?? null,
                        'gender' => $validated['gender'] ?? null,
                        'date_of_birth' => $validated['date_of_birth'] ?? null,
                        'address' => $validated['address'] ?? null,
                    ]);
                }

                // Store only the hashed OTP
                $user->emailVerificationOtp()->create([
                    'otp_hash' => Hash::make($otpCode),
                    'expires_at' => now()->addMinutes(10),
                    'attempts' => 0,
                    'sent_at' => now(),
                ]);

                return $user;
            });

            // Send OTP email
            Mail::raw(
                "Hello {$user->name},\n\n"
                . "Your Student Management System verification code is: {$otpCode}\n\n"
                . "This OTP expires in 10 minutes.\n\n"
                . "Please do not share this code with anyone.",
                function ($message) use ($user) {
                    $message
                        ->to($user->email)
                        ->subject('Student Management System - Email Verification');
                }
            );

            // Store registration details in session
            $request->session()->put([
                'registration.user_id' => $user->id,
                'registration.email' => $user->email,
                'registration.type' => 'student',
            ]);

        } catch (Throwable $exception) {

            // Remove incomplete account if email sending fails
            if (isset($user) && $user->exists) {
                $user->delete();
            }

            report($exception);

            return back()
                ->withErrors([
                    'email' => 'We could not send the verification email. Please check your email settings and try again.',
                ])
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]));
        }

        return redirect()
            ->route('register.verify-otp')
            ->with('status', 'A six-digit verification code has been sent to your email address.');
    }

              // Process teacher registration
    public function storeTeacher(Request $request): RedirectResponse
    {
        // Normalize email
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        // Validate form data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find Teacher role
        $teacherRole = Role::where('slug', 'teacher')->first();

        if (!$teacherRole) {
            return back()
                ->withErrors([
                    'email' => 'Teacher role is not configured. Please contact the administrator.',
                ])
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]));
        }

        // Generate a secure six-digit OTP
        $otpCode = (string) random_int(100000, 999999);

        try {

            // Save registration information in a database transaction
            $user = DB::transaction(function () use (
                $validated,
                $teacherRole,
                $otpCode
            ) {
                // Create inactive user account
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'role_id' => $teacherRole->id,
                    'is_active' => false,
                ]);

                // New teachers require Admin approval via RegistrationRequest
                $user->registrationRequest()->create([
                    'applicant_type' => 'teacher',
                    'phone' => $validated['phone'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);

                // Store only the hashed OTP
                $user->emailVerificationOtp()->create([
                    'otp_hash' => Hash::make($otpCode),
                    'expires_at' => now()->addMinutes(10),
                    'attempts' => 0,
                    'sent_at' => now(),
                ]);

                return $user;
            });

            // Send OTP email
            Mail::raw(
                "Hello {$user->name},\n\n"
                . "Your Student Management System teacher verification code is: {$otpCode}\n\n"
                . "This OTP expires in 10 minutes.\n\n"
                . "Please do not share this code with anyone.",
                function ($message) use ($user) {
                    $message
                        ->to($user->email)
                        ->subject('Student Management System - Teacher Email Verification');
                }
            );

            // Store registration details in session
            $request->session()->put([
                'registration.user_id' => $user->id,
                'registration.email' => $user->email,
                'registration.type' => 'teacher',
            ]);

        } catch (Throwable $exception) {

            // Remove incomplete account if email sending fails
            if (isset($user) && $user->exists) {
                $user->delete();
            }

            report($exception);

            return back()
                ->withErrors([
                    'email' => 'We could not send the verification email. Please check your email settings and try again.',
                ])
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]));
        }

        return redirect()
            ->route('register.verify-otp')
            ->with('status', 'A six-digit verification code has been sent to your email address.');
    }

    

    // Verify submitted OTP
    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('registration.user_id');

        if (!$userId) {
            return redirect()->route('register.student')
                ->withErrors(['email' => 'Registration session expired. Please register again.']);
        }

        $result = DB::transaction(function () use ($userId, $validated) {

            $user = User::whereKey($userId)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                return ['error' => 'Registration account not found.'];
            }

            if ($user->email_verified_at) {
                return ['error' => 'This email has already been verified. Please sign in.'];
            }

            $otp = $user->emailVerificationOtp()
                ->lockForUpdate()
                ->first();

            if (!$otp) {
                return ['error' => 'Verification code not found. Please request a new code.'];
            }

            if ($otp->used_at) {
                return ['error' => 'This verification code has already been used.'];
            }

            if ($otp->expires_at->isPast()) {
                return ['error' => 'Your OTP has expired. Please request a new one.'];
            }

            if ($otp->attempts >= 5) {
                return ['error' => 'Too many incorrect attempts. Please request a new OTP.'];
            }

            if (!Hash::check($validated['otp'], $otp->otp_hash)) {
                $otp->increment('attempts');

                return ['error' => 'Incorrect verification code. Please try again.'];
            }

            // Identify registration type
            $registration = $user->registrationRequest;
            $applicantType = $registration?->applicant_type;

            $profile = null;
            if ($applicantType === 'student') {
                $profile = Student::where('email', $user->email)->lockForUpdate()->first();
                if ($profile && $profile->user_id && (int) $profile->user_id !== (int) $user->id) {
                    return ['error' => 'This student record is already linked to another account.'];
                }
            } 
                
            

            // New applicants must have a valid registration request
            if (!$profile) {
                if (!$registration || !in_array($registration->applicant_type, ['student', 'teacher'], true)) {
                    return ['error' => 'Registration request not found. Please contact the administrator.'];
                }
            }

            // Mark OTP as verified and used
            $otp->update([
                'used_at' => now(),
            ]);

            $user->email_verified_at = now();

            if ($profile) {
                // Link the account to the existing profile
                $profile->user()->associate($user);
                $profile->save();

                $user->is_active = (bool) ($profile->status ?? false);

                $message = $profile->status
                    ? 'Email verified successfully! Your existing account is now ready. You can sign in.'
                    : 'Email verified successfully, but your profile is inactive. Please contact the administrator.';

            } else {
                // New applicants remain inactive until Admin approval
                $user->is_active = false;

                $message = 'Email verified successfully! Your registration is pending Admin approval.';
            }

            $user->save();

            return [
                'success' => true,
                'message' => $message,
            ];
        });

        if (isset($result['error'])) {
            return back()->withErrors([
                'otp' => $result['error'],
            ]);
        }

        // Clear registration session
        $request->session()->forget([
            'registration.user_id',
            'registration.email',
            'registration.type',
        ]);

        return redirect()
            ->route('login')
            ->with('status', $result['message']);
    }

    // Resend OTP
    public function resendOtp(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('registration.user_id');
        $regType = $request->session()->get('registration.type', 'student');
        $redirectRoute = $regType === 'teacher' ? 'register.teacher' : 'register.student';

        if (!$userId) {
            return redirect()->route($redirectRoute)
                ->withErrors(['email' => 'Registration session expired. Please register again.']);
        }

        $user = User::find($userId);

        if (!$user || $user->email_verified_at) {
            return redirect()->route($redirectRoute)
                ->withErrors(['email' => 'No pending email verification was found.']);
        }

        $otp = $user->emailVerificationOtp;

        if (!$otp) {
            return back()->withErrors([
                'otp' => 'Verification code not found. Please contact the administrator.',
            ]);
        }

        // Allow at least 60 seconds between resend requests
        if ($otp->sent_at) {
            $nextAllowedAt = $otp->sent_at->copy()->addSeconds(60);

            if (now()->lt($nextAllowedAt)) {
                $waitSeconds = now()->diffInSeconds($nextAllowedAt);

                return back()->withErrors([
                    'otp' => "Please wait {$waitSeconds} seconds before requesting another OTP.",
                ]);
            }
        }

        // Maximum 5 resend requests per hour
        $rateLimitKey = 'registration-otp-resend:' . $user->id;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $waitMinutes = (int) ceil(
                RateLimiter::availableIn($rateLimitKey) / 60
            );

            return back()->withErrors([
                'otp' => "You have reached the resend limit. Try again in {$waitMinutes} minutes.",
            ]);
        }

        $otpCode = (string) random_int(100000, 999999);

        try {
            // Send new OTP
            Mail::raw(
                "Hello {$user->name},\n\n"
                . "Your new Student Management System verification code is: {$otpCode}\n\n"
                . "This OTP expires in 10 minutes.\n\n"
                . "Please do not share this code with anyone.",
                function ($message) use ($user) {
                    $message
                        ->to($user->email)
                        ->subject('Student Management System - New Verification Code');
                }
            );

            // Update OTP only after successful mail submission
            $otp->update([
                'otp_hash' => Hash::make($otpCode),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
                'sent_at' => now(),
                'used_at' => null,
            ]);

            RateLimiter::hit($rateLimitKey, 3600);

        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'otp' => 'Unable to resend the verification email. Please try again later.',
            ]);
        }

        return back()->with('status', 'A new verification code has been sent to your email address.');
    }
}