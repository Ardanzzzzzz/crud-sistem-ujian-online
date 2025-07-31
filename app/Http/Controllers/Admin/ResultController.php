<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;

class ResultController extends Controller
{
    public function index()
    {
        $results = ExamAttempt::with(['user', 'exam'])->latest()->paginate(10);
        return view('admin.results.index', compact('results'));
    }
}
