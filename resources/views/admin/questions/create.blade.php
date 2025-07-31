@extends('layouts.app')

@section('title', 'Tambah Soal')

@section('content')
<div class="container mt-4">
    <h2>Tambah Soal untuk Ujian: {{ $exam->title }}</h2>

    <form method="POST" action="{{ route('admin.exams.questions.store', $exam) }}">
        @csrf
        <div class="mb-3">
            <label for="question_text" class="form-label">Teks Soal</label>
            <textarea name="question_text" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Tambahkan 4 pilihan -->
        @for ($i = 0; $i < 4; $i++)
        <div class="mb-3">
            <label class="form-label">Pilihan {{ chr(65 + $i) }}</label>
            <input type="text" name="options[{{ $i }}][option_text]" class="form-control" required>
            <div class="form-check mt-1">
                <input class="form-check-input" type="radio" name="correct_option" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }}>
                <label class="form-check-label">Jawaban Benar</label>
            </div>
        </div>
        @endfor

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.exams.questions.index', $exam) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
