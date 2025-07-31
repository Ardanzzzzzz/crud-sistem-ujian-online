@extends('layouts.app')

@section('title', $exam->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-clipboard-list"></i> {{ $exam->title }}</h4>
            </div>
            <div class="card-body">
                <p class="lead">{{ $exam->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                            <h5 class="mt-2">Durasi</h5>
                            <p>{{ $exam->duration }} menit</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-question-circle fa-2x text-info"></i>
                            <h5 class="mt-2">Jumlah Soal</h5>
                            <p>{{ $exam->total_questions }} soal</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-award fa-2x text-warning"></i>
                            <h5 class="mt-2">Tipe</h5>
                            <p>Pilihan Ganda</p>
                        </div>
                    </div>
                </div>

                @if($hasAttempt)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Anda sudah pernah mengikuti ujian ini.
                    </div>
                @else
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Petunjuk Ujian:</h6>
                        <ul class="mb-0">
                            <li>Pastikan koneksi internet Anda stabil</li>
                            <li>Ujian akan dimulai setelah Anda klik tombol "Mulai Ujian"</li>
                            <li>Anda memiliki waktu {{ $exam->duration }} menit untuk menyelesaikan {{ $exam->total_questions }} soal</li>
                            <li>Ujian akan otomatis berakhir ketika waktu habis</li>
                            <li>Pastikan Anda sudah siap sebelum memulai ujian</li>
                        </ul>
                    </div>

                    <form action="{{ route('exams.start', $exam) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin memulai ujian? Ujian akan langsung dimulai setelah Anda klik OK.')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-play"></i> Mulai Ujian
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar"></i> Statistik Ujian</h6>
            </div>
            <div class="card-body">
                @php
                    $totalAttempts = $exam->examAttempts()->where('status', 'completed')->count();
                    $averageScore = $exam->examAttempts()->where('status', 'completed')->avg('score');
                @endphp
                
                <div class="mb-3">
                    <small class="text-muted">Total Peserta</small>
                    <h5>{{ $totalAttempts }}</h5>
                </div>
                
                @if($totalAttempts > 0)
                <div class="mb-3">
                    <small class="text-muted">Rata-rata Skor</small>
                    <h5>{{ number_format($averageScore, 1) }}</h5>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-arrow-left"></i> Navigasi</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('exams.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ujian
                </a>
            </div>
        </div>
    </div>
</div>
@endsection