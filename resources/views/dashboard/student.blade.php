@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard Siswa</h2>
        <p class="lead">Selamat datang, {{ $user->name }}</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Ujian Diikuti</h5>
                        <h3>{{ $user->examAttempts()->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Ujian Selesai</h5>
                        <h3>{{ $user->examAttempts()->where('status', 'completed')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Rata-rata Skor</h5>
                        <h3>{{ number_format($user->examAttempts()->where('status', 'completed')->avg('score') ?? 0, 1) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> Menu Utama</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('exams.index') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-clipboard-list"></i><br>
                            Daftar Ujian
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="btn btn-info btn-lg w-100 mb-3">
                            <i class="fas fa-history"></i><br>
                            Riwayat Ujian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->examAttempts()->where('status', 'completed')->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Ujian Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Ujian</th>
                                <th>Tanggal</th>
                                <th>Skor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->examAttempts()->with('exam')->where('status', 'completed')->latest()->take(5)->get() as $attempt)
                            <tr>
                                <td>{{ $attempt->exam->title }}</td>
                                <td>{{ $attempt->finished_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $attempt->score >= 75 ? 'success' : ($attempt->score >= 60 ? 'warning' : 'danger') }}">
                                        {{ $attempt->score }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Selesai</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection