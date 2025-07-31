<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\UserAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('is_active', true)
                    ->withCount('questions')
                    ->get();
        
        return view('exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        if (!$exam->is_active) {
            abort(404);
        }

        $user = Auth::user();
        $hasAttempt = ExamAttempt::where('user_id', $user->id)
                                 ->where('exam_id', $exam->id)
                                 ->exists();

        return view('exams.show', compact('exam', 'hasAttempt'));
    }

    public function start(Exam $exam)
    {
        $user = Auth::user();
        
        // Check if user already has an attempt
        $existingAttempt = ExamAttempt::where('user_id', $user->id)
                                     ->where('exam_id', $exam->id)
                                     ->first();

        if ($existingAttempt) {
            if ($existingAttempt->status === 'completed') {
                return redirect()->route('exams.result', $exam);
            }
            
            if ($existingAttempt->isExpired()) {
                $existingAttempt->update(['status' => 'expired']);
                return redirect()->route('exams.show', $exam)
                               ->with('error', 'Your previous attempt has expired.');
            }
            
            // Continue existing attempt
            $attempt = $existingAttempt;
        } else {
            // Create new attempt
            $attempt = ExamAttempt::create([
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'started_at' => Carbon::now(),
                'total_questions' => $exam->questions()->count(),
                'status' => 'in_progress',
            ]);
        }

        $questions = $exam->questions()->with('options')->get();
        $userAnswers = $attempt->userAnswers()->get()->keyBy('question_id');

        return view('exams.take', compact('exam', 'attempt', 'questions', 'userAnswers'));
    }

    public function submitAnswer(Request $request, Exam $exam)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'required|exists:question_options,id',
        ]);

        $user = Auth::user();
        $attempt = ExamAttempt::where('user_id', $user->id)
                              ->where('exam_id', $exam->id)
                              ->where('status', 'in_progress')
                              ->first();

        if (!$attempt || $attempt->isExpired()) {
            return response()->json(['error' => 'Exam session expired'], 400);
        }

        // Remove existing answer for this question
        UserAnswer::where('exam_attempt_id', $attempt->id)
                  ->where('question_id', $request->question_id)
                  ->delete();

        // Save new answer
        UserAnswer::create([
            'exam_attempt_id' => $attempt->id,
            'question_id' => $request->question_id,
            'question_option_id' => $request->option_id,
        ]);

        return response()->json(['success' => true]);
    }

    public function finish(Exam $exam)
    {
        $user = Auth::user();
        $attempt = ExamAttempt::where('user_id', $user->id)
                              ->where('exam_id', $exam->id)
                              ->where('status', 'in_progress')
                              ->first();

        if (!$attempt) {
            return redirect()->route('exams.show', $exam);
        }

        // Calculate score
        $correctAnswers = 0;
        $userAnswers = $attempt->userAnswers()->with('questionOption')->get();

        foreach ($userAnswers as $userAnswer) {
            if ($userAnswer->questionOption && $userAnswer->questionOption->is_correct) {
                $correctAnswers++;
            }
        }

        $totalQuestions = $attempt->total_questions;
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        $attempt->update([
            'finished_at' => Carbon::now(),
            'correct_answers' => $correctAnswers,
            'score' => $score,
            'status' => 'completed',
        ]);

        return redirect()->route('exams.result', $exam);
    }

    public function result(Exam $exam)
    {
        $user = Auth::user();
        $attempt = ExamAttempt::where('user_id', $user->id)
                              ->where('exam_id', $exam->id)
                              ->where('status', 'completed')
                              ->first();

        if (!$attempt) {
            return redirect()->route('exams.show', $exam);
        }

        $userAnswers = $attempt->userAnswers()
                              ->with(['question', 'questionOption'])
                              ->get();

        return view('exams.result', compact('exam', 'attempt', 'userAnswers'));
    }
}