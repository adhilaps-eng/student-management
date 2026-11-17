<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Admin\RegistrationApprovalController;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [LoginController::class, 'show'])
    ->name('login');

// Student Registration
Route::get('/register/student', [RegistrationController::class, 'showStudentForm'])
    ->name('register.student');

 Route::post('/register/student', [RegistrationController::class, 'storeStudent'])
       ->name('register.student.store');   

// Teacher Registration
Route::get('/register/teacher', [RegistrationController::class, 'showTeacherForm'])
    ->name('register.teacher');

Route::post('/register/teacher', [RegistrationController::class, 'storeTeacher'])
    ->name('register.teacher.store');        

Route::get('/register/verify-otp', [RegistrationController::class, 'showOtpForm'])
    ->name('register.verify-otp'); 
   

// Verify submitted OTP
Route::post('/register/verify-otp', [RegistrationController::class, 'verifyOtp'])
    ->name('register.verify-otp.store');

// Resend OTP
Route::post('/register/resend-otp', [RegistrationController::class, 'resendOtp'])
    ->name('register.resend-otp');    

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

Route::get('/register', [RegistrationController::class, 'show'])
    ->name('register');    

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'role:super-admin,admin,teacher,student'])
    ->name('dashboard');

// Only logged-in users can access student pages.
Route::middleware(['auth', 'role:super-admin,admin'])->group(function () {
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/create', [StudentController::class, 'create']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::get('/students/{student}/edit', [StudentController::class, 'edit']);
    Route::put('/students/{student}', [StudentController::class, 'update']);
    Route::delete('/students/{student}', [StudentController::class, 'destroy']);
});

// Admin Registration Approval
    Route::prefix('admin')
      ->name('admin.')
      ->middleware(['auth', 'role:super-admin,admin'])
      ->group(function () {

        // View all registration requests
        Route::get('/registration-requests', [RegistrationApprovalController::class, 'index'])
            ->name('registration-requests.index');

        // View individual registration request
        Route::get('/registration-requests/{registrationRequest}', [RegistrationApprovalController::class, 'show'])
            ->name('registration-requests.show');

        // Approve registration
        Route::post('/registration-requests/{registrationRequest}/approve', [RegistrationApprovalController::class, 'approve'])
            ->name('registration-requests.approve');

        // Reject registration
        Route::post('/registration-requests/{registrationRequest}/reject', [RegistrationApprovalController::class, 'reject'])
            ->name('registration-requests.reject');
    });