@extends('layouts.app')

@section('title', 'Buat Ujian dan Tambah Soal')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-plus"></i> Buat Ujian dan Soal</h2>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('admin.exams.storeWithQuestions') }}" method="POST">
    @csrf

    {{-- ===== FORM UJIAN ===== --}}
    <div class="card mb-4">
        <div class="card-header">Informasi Ujian</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Judul Ujian *</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Durasi (menit) *</label>
                <input type="number" name="duration" class="form-control" required min="1">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                <label class="form-check-label">Aktifkan ujian</label>
            </div>
        </div>
    </div>

    {{-- ===== FORM SOAL DINAMIS ===== --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Soal</span>
            <button type="button" class="btn btn-sm btn-success" onclick="addQuestion()">
                <i class="fas fa-plus"></i> Tambah Soal
            </button>
        </div>
        <div class="card-body" id="questions-wrapper">
            <!-- Soal-soal akan ditambahkan di sini -->
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Ujian dan Semua Soal
    </button>
</form>
@endsection

@push('scripts')
<script>
    let questionIndex = 0;

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
        const questionDiv = document.getElementById(`question-${index}`);
        if (questionDiv) {
            questionDiv.remove();
        }
    }

    // Tambahkan satu soal default saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function () {
        addQuestion();
    });
</script>
@endpush
