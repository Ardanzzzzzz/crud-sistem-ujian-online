<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;

class StatisticsController extends Controller
{
    public function index()
    {
        $averageScore = ExamAttempt::where('status', 'completed')->avg('score') ?? 0;
        $totalAttempts = ExamAttempt::count();
        $highScores = ExamAttempt::where('score', '>=', 80)->count();

        return view('admin.statistics.index', compact('averageScore', 'totalAttempts', 'highScores'));
    }
}