<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::latest()->paginate(10);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Menyimpan ujian sekaligus dengan satu soal dan opsi jawaban
     */
    public function storeWithQuestions(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.0.question_text' => 'required|string',
            'questions.0.options' => 'required|array|min:2',
            'questions.0.correct_option' => 'required|numeric',
        ]);

        // Simpan ujian
        $exam = Exam::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'is_active' => $request->has('is_active'),
        ]);

        // Simpan soal dan opsinya
        foreach ($request->questions as $questionData) {
            $question = Question::create([
                'exam_id' => $exam->id,
                'question_text' => $questionData['question_text'],
            ]);

            foreach ($questionData['options'] as $index => $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => ($index == $questionData['correct_option']),
                ]);
            }
        }

        return redirect()->route('admin.exams.index')
            ->with('success', 'Ujian dan soal berhasil ditambahkan!');
    }

    public function show(Exam $exam)
    {
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
        ]);

        $exam->update([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Ujian berhasil diperbarui');
    }

    public function destroy(Exam $exam)
    {
        // Jika sudah ada attempt, jangan dihapus
        if ($exam->attempts()->count() > 0) {
            return redirect()->route('admin.exams.index')
                ->with('error', 'Ujian tidak bisa dihapus karena sudah ada percobaan.');
        }

        $exam->delete();
        return redirect()->route('admin.exams.index')
            ->with('success', 'Ujian berhasil dihapus');
    }
}
