<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        return view('students.index', [
            'students' => $students
        ]);
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable',
            'gender' => 'nullable',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable',
            'admission_date' => 'nullable|date',
            'status' => 'required',
        ]);

        Student::create($validated);

        return redirect('/students');
    }

    public function edit(Student $student)
    {
        return view('students.edit', [
            'student' => $student
        ]);
    }


    public function update(Request $request, Student $student)
    {
     $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'nullable',
        'gender' => 'nullable',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable',
        'admission_date' => 'nullable|date',
        'status' => 'required',
     ]);

     $student->update($validated);

     return redirect('/students');
   }

   public function destroy(Student $student)
   {
     $student->delete();

     return redirect('/students');
   }
}