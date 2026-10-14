<?php

namespace App\Http\Controllers;

class StudentController extends Controller
{
    public function index()
    {
         $students = [
            'Rahul',
            'Anu',
            'Arjun',
            'Meera'
         ];
        return view('students.index', [
        'students' => $students
       ] );
    }
}