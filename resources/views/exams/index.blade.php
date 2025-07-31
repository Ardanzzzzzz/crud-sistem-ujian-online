@extends('layouts.app')

@section('title', 'Daftar Ujian')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-clipboard-list"></i> Daftar Ujian</h2>
        <p class="lead">Pilih ujian yang ingin Anda ikuti</p>
    </div>
</div>

<div class="row">
    @forelse($exams as $exam)
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $exam->title }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $exam->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> {{ $exam->duration }} menit
                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">
                            <i class="fas fa-question-circle"></i> {{ $exam->questions_count }} soal
                        </small>
                    </div>
                </div>

                @php
                    $userAttempt = Auth::user()->examAttempts()->where('exam_id', $exam->id)->first();
                @endphp

                @if($userAttempt && $userAttempt->status === 'completed')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Ujian sudah selesai
                        <br><small>Skor: {{ $userAttempt->score }}</small>
                    </div>
                    <a href="{{ route('exams.result', $exam) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Lihat Hasil
                    </a>
                @elseif($userAttempt && $userAttempt->status === 'in_progress')
                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i> Ujian sedang berlangsung
                    </div>
                    <a href="{{ route('exams.start', $exam) }}" class="btn btn-warning">
                        <i class="fas fa-play"></i> Lanjutkan Ujian
                    </a>
                @else
                    <a href="{{ route('exams.show', $exam) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Belum ada ujian yang tersedia
        </div>
    </div>
    @endforelse
</div>
@endsection