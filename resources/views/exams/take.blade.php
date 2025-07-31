@extends('layouts.app')

@section('title', 'Ujian: ' . $exam->title)

@section('styles')
<style>
.question-nav {
    max-height: 400px;
    overflow-y: auto;
}
.question-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 2px;
    cursor: pointer;
    border: 2px solid #ddd;
    background: white;
}
.question-number.answered {
    background: #28a745;
    color: white;
    border-color: #28a745;
}
.question-number.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}
.timer-warning {
    animation: blink 1s infinite;
}
@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.5; }
}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $exam->title }}</h5>
                <div id="timer" class="badge bg-light text-dark fs-6">
                    <i class="fas fa-clock"></i> <span id="time-display">{{ gmdate('H:i:s', $attempt->remaining_time) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div id="questions">
                    @foreach($questions as $index => $question)
                    <div class="question-container" id="question-{{ $question->id }}" 
                         style="{{ $index == 0 ? '' : 'display: none;' }}">
                        <div class="mb-3">
                            <h6>Soal {{ $index + 1 }} dari {{ $questions->count() }}</h6>
                            <p class="lead">{{ $question->question_text }}</p>
                        </div>

                        <div class="options">
                            @foreach($question->options as $option)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" 
                                       name="question_{{ $question->id }}" 
                                       id="option_{{ $option->id }}"
                                       value="{{ $option->id }}"
                                       {{ isset($userAnswers[$question->id]) && $userAnswers[$question->id]->question_option_id == $option->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="option_{{ $option->id }}">
                                    {{ $option->option_text }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prev-btn" 
                                    {{ $index == 0 ? 'disabled' : '' }}
                                    onclick="showQuestion({{ $index - 1 }})">
                                <i class="fas fa-arrow-left"></i> Sebelumnya
                            </button>
                            
                            @if($index == $questions->count() - 1)
                                <button type="button" class="btn btn-success" onclick="finishExam()">
                                    <i class="fas fa-check"></i> Selesai
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" 
                                        onclick="showQuestion({{ $index + 1 }})">
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-list"></i> Navigasi Soal</h6>
            </div>
            <div class="card-body question-nav">
                <div class="d-flex flex-wrap">
                    @foreach($questions as $index => $question)
                    <div class="question-number {{ isset($userAnswers[$question->id]) ? 'answered' : '' }} {{ $index == 0 ? 'active' : '' }}"
                         id="nav-{{ $question->id }}" 
                         onclick="showQuestion({{ $index }})">
                        {{ $index + 1 }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-info-circle"></i> Keterangan</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="question-number answered me-2"></div>
                    <small>Sudah dijawab</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <div class="question-number active me-2"></div>
                    <small>Soal aktif</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="question-number me-2"></div>
                    <small>Belum dijawab</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Selesai -->
<div class="modal fade" id="finishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Selesai Ujian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyelesaikan ujian?</p>
                <p><strong>Soal yang sudah dijawab: </strong><span id="answered-count">0</span> dari {{ $questions->count() }}</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Pastikan Anda sudah memeriksa semua jawaban. Ujian tidak dapat diulang setelah selesai.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('exams.finish', $exam) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Ya, Selesai Ujian
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentQuestion = 0;
let totalQuestions = {{ $questions->count() }};
let examId = {{ $exam->id }};
let remainingTime = {{ $attempt->remaining_time }};
let timerInterval;

// Start timer
startTimer();

function startTimer() {
    timerInterval = setInterval(function() {
        if (remainingTime <= 0) {
            clearInterval(timerInterval);
            autoFinishExam();
            return;
        }
        
        remainingTime--;
        updateTimerDisplay();
        
        // Warning when 5 minutes left
        if (remainingTime <= 300) {
            document.getElementById('timer').classList.add('timer-warning');
        }
    }, 1000);
}

function updateTimerDisplay() {
    let hours = Math.floor(remainingTime / 3600);
    let minutes = Math.floor((remainingTime % 3600) / 60);
    let seconds = remainingTime % 60;
    
    let timeString = 
        (hours < 10 ? '0' : '') + hours + ':' +
        (minutes < 10 ? '0' : '') + minutes + ':' +
        (seconds < 10 ? '0' : '') + seconds;
    
    document.getElementById('time-display').textContent = timeString;
}

function showQuestion(index) {
    // Hide current question
    document.getElementById('question-' + getQuestionId(currentQuestion)).style.display = 'none';
    
    // Remove active class from current nav
    document.getElementById('nav-' + getQuestionId(currentQuestion)).classList.remove('active');
    
    // Show new question
    currentQuestion = index;
    document.getElementById('question-' + getQuestionId(currentQuestion)).style.display = 'block';
    
    // Add active class to new nav
    document.getElementById('nav-' + getQuestionId(currentQuestion)).classList.add('active');
    
    // Update prev/next button states
    updateNavigationButtons();
}

function getQuestionId(index) {
    let questions = @json($questions->pluck('id'));
    return questions[index];
}

function updateNavigationButtons() {
    let prevBtn = document.getElementById('prev-btn');
    if (prevBtn) {
        prevBtn.disabled = currentQuestion === 0;
    }
}

// Handle answer selection
document.addEventListener('change', function(e) {
    if (e.target.type === 'radio' && e.target.name.startsWith('question_')) {
        let questionId = e.target.name.replace('question_', '');
        let optionId = e.target.value;
        
        // Submit answer via AJAX
        submitAnswer(questionId, optionId);
        
        // Update navigation
        document.getElementById('nav-' + questionId).classList.add('answered');
        updateAnsweredCount();
    }
});

function submitAnswer(questionId, optionId) {
    fetch(`/exams/${examId}/submit-answer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            question_id: questionId,
            option_id: optionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Gagal menyimpan jawaban. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateAnsweredCount() {
    let answeredCount = document.querySelectorAll('.question-number.answered').length;
    document.getElementById('answered-count').textContent = answeredCount;
}

function finishExam() {
    updateAnsweredCount();
    let modal = new bootstrap.Modal(document.getElementById('finishModal'));
    modal.show();
}

function autoFinishExam() {
    alert('Waktu ujian telah habis. Ujian akan otomatis diselesaikan.');
    document.querySelector('#finishModal form').submit();
}

// Initialize answered count
updateAnsweredCount();

// Prevent page refresh/close during exam
window.addEventListener('beforeunload', function(e) {
    e.preventDefault();
    e.returnValue = 'Apakah Anda yakin ingin meninggalkan halaman? Ujian akan tetap berjalan.';
});
</script>
@endpush