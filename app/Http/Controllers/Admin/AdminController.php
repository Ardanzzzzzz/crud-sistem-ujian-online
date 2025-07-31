<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ExamAttempt;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalExams = Exam::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAttempts = ExamAttempt::count();
        $recentAttempts = ExamAttempt::with(['user', 'exam'])
                                    ->latest()
                                    ->take(10)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalExams',
            'totalStudents', 
            'totalAttempts',
            'recentAttempts'
        ));
    }

    public function manageExams()
    {
        $exams = Exam::withCount(['questions', 'examAttempts'])->get();
        return view('admin.exams.index', compact('exams'));
    }

    public function createExam()
    {
        return view('admin.exams.create');
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
        ]);

        Exam::create($request->all());

        return redirect()->route('admin.exams.index')
                        ->with('success', 'Exam created successfully');
    }

    public function showExam(Exam $exam)
    {
        $exam->load(['questions.options', 'examAttempts.user']);
        return view('admin.exams.show', compact('exam'));
    }

    public function editExam(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $exam->update($request->all());

        return redirect()->route('admin.exams.show', $exam)
                        ->with('success', 'Exam updated successfully');
    }

    public function destroyExam(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')
                        ->with('success', 'Exam deleted successfully');
    }

    public function manageQuestions(Exam $exam)
    {
        $questions = $exam->questions()->with('options')->get();
        return view('admin.questions.index', compact('exam', 'questions'));
    }

    public function createQuestion(Exam $exam)
    {
        return view('admin.questions.create', compact('exam'));
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2|max:5',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'question_text' => $request->question_text,
        ]);

        foreach ($request->options as $index => $optionText) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $optionText,
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        $exam->updateTotalQuestions();

        return redirect()->route('admin.questions.index', $exam)
                        ->with('success', 'Question created successfully');
    }

    public function editQuestion(Exam $exam, Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('exam', 'question'));
    }

    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2|max:5',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question->update([
            'question_text' => $request->question_text,
        ]);

        // Delete existing options
        $question->options()->delete();

        // Create new options
        foreach ($request->options as $index => $optionText) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $optionText,
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('admin.questions.index', $exam)
                        ->with('success', 'Question updated successfully');
    }

    public function destroyQuestion(Exam $exam, Question $question)
    {
        $question->delete();
        $exam->updateTotalQuestions();

        return redirect()->route('admin.questions.index', $exam)
                        ->with('success', 'Question deleted successfully');
    }

    public function viewResults(Exam $exam = null)
    {
        $query = ExamAttempt::with(['user', 'exam'])
                           ->where('status', 'completed');

        if ($exam) {
            $query->where('exam_id', $exam->id);
        }

        $results = $query->latest()->paginate(20);
        $exams = Exam::all();

        return view('admin.results', compact('results', 'exams', 'exam'));
    }
}