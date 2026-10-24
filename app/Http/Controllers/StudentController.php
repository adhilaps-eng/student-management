<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
      public function index(Request $request)
{
    $search = $request->input('search');

    $totalStudents = Student::count();

    $activeStudents = Student::where('status', true)->count();

    $inactiveStudents = Student::where('status', false)->count();

    $students = Student::query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->paginate(5)
        ->withQueryString();

    return view('students.index', [
       'students' => $students,
       'search' => $search,
       'totalStudents' => $totalStudents,
       'activeStudents' => $activeStudents,
       'inactiveStudents' => $inactiveStudents,
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
            'phone' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable',
            'admission_date' => 'required|date',
            'status' => 'required',
        ]);

        Student::create($validated);

        return redirect('/students')
            ->with('success', 'Student added successfully!');
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
        'email' => [
            'required',
           ' email',
            Rule::unique('students', 'email')->ignore($student->id),
        ],
        'phone' => 'required',
        'gender' => 'required',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable',
        'admission_date' => 'required|date',
        'status' => 'required',
     ]);

     $student->update($validated);

     return redirect('/students')
        ->with('success', 'Student updated successfully!');
   }

   public function destroy(Student $student)
   {
     $student->delete();

     return redirect('/students')
         ->with('success', 'Student deleted successfully!');
   }
}