<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role mahasiswa
        $students = User::where('role', 'mahasiswa')->get();

        return view('admin.students.index', compact('students'));
    }
}
