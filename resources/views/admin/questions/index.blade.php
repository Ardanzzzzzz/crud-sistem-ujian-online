@extends('layouts.app')

@section('title', 'Kelola Soal: ' . $exam->title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="fas fa-question-circle"></i> Kelola Soal</h2>
                <p class="text-muted mb-0">{{ $exam->title }}</p>
            </div>
            <div>
                <a href="{{ route('admin.exams.questions.create', $exam) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </a>
                <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Exam Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Total Soal:</strong> {{ $questions->count() }}
                    </div>
                    <div class="col-md-3">
                        <strong>Durasi:</strong> {{ $exam->duration }} menit
                    </div>
                    <div class="col-md-3">
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ $exam->is_active ? 'success' : 'danger' }}">
                            {{ $exam->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <strong>Estimasi:</strong> {{ $questions->count() * 1.5 }} menit
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Questions List -->
<div class="row">
    <div class="col-12">
        @if($questions->count() > 0)
            @foreach($questions as $index => $question)
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Soal {{ $index + 1 }}</h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.exams.questions.edit', [$exam, $question]) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.exams.questions.destroy', [$exam, $question]) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>{{ $question->question_text }}</strong></p>
                    
                    <div class="row">
                        @foreach($question->options as $optionIndex => $option)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                @if($option->is_correct)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="far fa-circle text-muted me-2"></i>
                                @endif
                                <span class="{{ $option->is_correct ? 'fw-bold text-success' : '' }}">
                                    {{ chr(65 + $optionIndex) }}. {{ $option->option_text }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-question-circle fa-3x mb-3"></i>
                    <p>Belum ada soal untuk ujian ini</p>
                    <a href="{{ route('admin.exams.questions.create', $exam) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Soal Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-percentage fa-3x text-primary mb-2"></i>
                            <h4>{{ $attempt->score }}</h4>
                            <p class="text-muted">Skor</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                            <h4>{{ $attempt->correct_answers }}</h4>
                            <p class="text-muted">Jawaban Benar</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                            <h4>{{ $attempt->total_questions - $attempt->correct_answers }}</h4>
                            <p class="text-muted">Jawaban Salah</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-clock fa-3x text-info mb-2"></i>
                            <h4>{{ $attempt->started_at->diffInMinutes($attempt->finished_at) }}</h4>
                            <p class="text-muted">Menit</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        @if($attempt->score >= 80)
                            <div class="alert alert-success">
                                <i class="fas fa-star"></i> <strong>Excellent!</strong> Hasil yang sangat baik.
                            </div>
                        @elseif($attempt->score >= 70)
                            <div class="alert alert-info">
                                <i class="fas fa-thumbs-up"></i> <strong>Good!</strong> Hasil yang baik.
                            </div>
                        @elseif($attempt->score >= 60)
                            <div class="alert alert-warning">
                                <i class="fas fa-hand-paper"></i> <strong>Fair!</strong> Masih perlu ditingkatkan.
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <i class="fas fa-frown"></i> <strong>Needs Improvement!</strong> Silakan belajar lebih giat.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Jawaban -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list-alt"></i> Detail Jawaban</h5>
            </div>
            <div class="card-body">
                @foreach($exam->questions()->with('options')->get() as $index => $question)
                    @php
                        $userAnswer = $userAnswers->where('question_id', $question->id)->first();
                        $correctOption = $question->options->where('is_correct', true)->first();
                        $isCorrect = $userAnswer && $userAnswer->questionOption->is_correct;
                    @endphp
                    
                    <div class="card mb-3 {{ $isCorrect ? 'border-success' : 'border-danger' }}">
                        <div class="card-header {{ $isCorrect ? 'bg-light-success' : 'bg-light-danger' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Soal {{ $index + 1 }}</h6>
                                @if($isCorrect)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Benar</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Salah</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ $question->question_text }}</strong></p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Pilihan Jawaban:</h6>
                                    @foreach($question->options as $optionIndex => $option)
                                        <div class="mb-1">
                                            @if($userAnswer && $userAnswer->question_option_id == $option->id)
                                                <span class="badge bg-primary">Dipilih</span>
                                            @endif
                                            @if($option->is_correct)
                                                <span class="badge bg-success">Benar</span>
                                            @endif
                                            <span class="{{ $option->is_correct ? 'text-success fw-bold' : '' }}">
                                                {{ chr(65 + $optionIndex) }}. {{ $option->option_text }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <h6>Status:</h6>
                                    @if($userAnswer)
                                        <p>Jawaban Anda: <strong>{{ chr(65 + $question->options->search(function($option) use ($userAnswer) { return $option->id == $userAnswer->question_option_id; })) }}. {{ $userAnswer->questionOption->option_text }}</strong></p>
                                    @else
                                        <p class="text-muted">Tidak dijawab</p>
                                    @endif
                                    <p>Jawaban Benar: <strong>{{ chr(65 + $question->options->search(function($option) { return $option->is_correct; })) }}. {{ $correctOption->option_text }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="text-center mt-4 mb-4">
            <a href="{{ route('exams.index') }}" class="btn btn-primary">
                <i class="fas fa-list"></i> Kembali ke Daftar Ujian
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-light-success {
    background-color: #d4edda !important;
}
.bg-light-danger {
    background-color: #f8d7da !important;
}
</style>
@endpush