<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\ResultController;

// Redirect root to login
Route::get('/', fn () => redirect('/login'));


// ==================================
// Auth Routes
// ==================================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');
});


// ==================================
// Student Routes (Peserta Ujian)
// ==================================
Route::middleware('auth')->prefix('exams')->name('exams.')->group(function () {
    Route::get('/', [ExamController::class, 'index'])->name('index');
    Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
    Route::post('/{exam}/start', [ExamController::class, 'start'])->name('start');
    Route::post('/{exam}/submit-answer', [ExamController::class, 'submitAnswer'])->name('submit');
    Route::post('/{exam}/finish', [ExamController::class, 'finish'])->name('finish');
    Route::get('/{exam}/result', [ExamController::class, 'result'])->name('result');
});


// ==================================
// Admin Routes
// ==================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Siswa
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    // Statistik Ujian
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    // Hasil Ujian
    Route::get('/results/{exam?}', [AdminController::class, 'viewResults'])->name('results');

    // Jika ingin versi ResultController:
    // Route::get('/results', [ResultController::class, 'index'])->name('results');

    // ==================================
    // CRUD Ujian + Soal
    // ==================================
    Route::prefix('exams')->name('exams.')->group(function () {

        // CRUD Ujian
        Route::get('/', [AdminExamController::class, 'index'])->name('index');
        Route::get('/create', [AdminExamController::class, 'create'])->name('create');
        Route::post('/', [AdminExamController::class, 'store'])->name('store');
        Route::get('/{exam}', [AdminExamController::class, 'show'])->name('show');
        Route::get('/{exam}/edit', [AdminExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}', [AdminExamController::class, 'update'])->name('update');
        Route::delete('/{exam}', [AdminExamController::class, 'destroy'])->name('destroy');

        // Buat Ujian + Soal Sekaligus
        Route::get('/create-with-questions', [AdminExamController::class, 'createWithQuestions'])->name('createWithQuestions');
        Route::post('/store-with-questions', [AdminExamController::class, 'storeWithQuestions'])->name('storeWithQuestions');

        // CRUD Soal dalam Ujian
        Route::prefix('{exam}/questions')->name('questions.')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('index');
            Route::get('/create', [QuestionController::class, 'create'])->name('create');
            Route::post('/', [QuestionController::class, 'store'])->name('store');
            Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        });
    });
});
