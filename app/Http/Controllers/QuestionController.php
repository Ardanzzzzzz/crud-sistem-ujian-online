<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use App\Models\Attempt;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions()->with('options')->get();
        $attempt = Attempt::where('exam_id', $exam->id)->first();
        return view('admin.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        return view('admin.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|numeric',
        ]);

        $question = $exam->questions()->create([
            'question_text' => $validated['question_text'],
        ]);

        foreach ($validated['options'] as $index => $option) {
            $question->options()->create([
                'option_text' => $option['option_text'],
                'is_correct' => $index == $validated['correct_option'],
            ]);
        }

        return redirect()->route('admin.exams.questions.index', $exam)
                         ->with('success', 'Soal berhasil ditambahkan!');
    }
}
