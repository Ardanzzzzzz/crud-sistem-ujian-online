@extends('layouts.app')

@section('title', 'Edit Ujian dan Soal')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-edit"></i> Edit Ujian</h2>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- ===== UJIAN ===== --}}
    <div class="card mb-4">
        <div class="card-header">Informasi Ujian</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Judul Ujian *</label>
                <input type="text" name="title" value="{{ $exam->title }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="3" class="form-control">{{ $exam->description }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Durasi (menit) *</label>
                <input type="number" name="duration" class="form-control" value="{{ $exam->duration }}" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $exam->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Aktifkan ujian</label>
            </div>
        </div>
    </div>

    {{-- ===== SOAL ===== --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Soal</span>
            <button type="button" class="btn btn-sm btn-success" onclick="addQuestion()">
                <i class="fas fa-plus"></i> Tambah Soal
            </button>
        </div>
        <div class="card-body" id="questions-wrapper">
            @foreach($exam->questions as $qIndex => $question)
                <div class="border rounded p-3 mb-4" id="question-{{ $qIndex }}">
                    <div class="d-flex justify-content-between">
                        <h5>Soal #{{ $qIndex + 1 }}</h5>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion({{ $qIndex }})">
                            Hapus
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Soal *</label>
                        <textarea name="questions[{{ $qIndex }}][question_text]" class="form-control" required>{{ $question->question_text }}</textarea>
                    </div>
                    <div class="row">
                        @foreach($question->options as $oIndex => $option)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan {{ chr(65+$oIndex) }}</label>
                            <input type="text" name="questions[{{ $qIndex }}][options][{{ $oIndex }}][option_text]" value="{{ $option->option_text }}" class="form-control" required>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="radio" name="questions[{{ $qIndex }}][correct_option]" value="{{ $oIndex }}" {{ $option->is_correct ? 'checked' : '' }}>
                                <label class="form-check-label">Jawaban Benar</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Update Ujian
    </button>
</form>
@endsection

@push('scripts')
<script>
    let questionIndex = {{ count($exam->questions) }};

    function addQuestion() {
        const wrapper = document.getElementById('questions-wrapper');

        const questionHTML = `
            <div class="border rounded p-3 mb-4" id="question-${questionIndex}">
                <div class="d-flex justify-content-between">
                    <h5>Soal #${questionIndex + 1}</h5>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(${questionIndex})">
                        Hapus
                    </button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Soal *</label>
                    <textarea name="questions[${questionIndex}][question_text]" class="form-control" required></textarea>
                </div>
                <div class="row">
                    ${[0,1,2,3].map(i => `
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilihan ${String.fromCharCode(65 + i)}</label>
                            <input type="text" name="questions[${questionIndex}][options][${i}][option_text]" class="form-control" required>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="radio" name="questions[${questionIndex}][correct_option]" value="${i}" ${i === 0 ? 'checked' : ''}>
                                <label class="form-check-label">Jawaban Benar</label>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', questionHTML);
        questionIndex++;
    }

    function removeQuestion(index) {
        const div = document.getElementById(`question-${index}`);
        if (div) div.remove();
    }
</script>
@endpush
