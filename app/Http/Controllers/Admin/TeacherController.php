<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        // Fetch teachers along with their associated user data (name, email)
        $teachers = Teacher::with('user')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the input fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_code' => 'required|string|max:50|unique:teachers,employee_code',
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. Use a database transaction to create both User and Teacher safely
        DB::transaction(function () use ($request) {
            // Create the base User record (giving them a default role/password if needed)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::Make('password123'), // Default temporary password
            ]);

            // Assign role if you are using spatie/laravel-permission or similar package
            // $user->assignRole('teacher');

            // Create the Teacher profile linked to the User
            Teacher::create([
                'user_id' => $user->id,
                'employee_code' => $request->employee_code,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'joining_date' => $request->joining_date,
                'status' => true,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully.');
    }
}