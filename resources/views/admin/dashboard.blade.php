@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
        <p class="lead">Selamat datang di panel admin</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Ujian</h5>
                        <h3>{{ $totalExams }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.exams.index') }}" class="text-white text-decoration-none">
                    <small>Kelola Ujian <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Siswa</h5>
                        <h3>{{ $totalStudents }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="text-white text-decoration-none">
                    <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Percobaan</h5>
                        <h3>{{ $totalAttempts }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-play fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.results') }}" class="text-white text-decoration-none">
                    <small>Lihat Hasil <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Rata-rata Skor</h5>
                        <h3>{{ number_format(\App\Models\ExamAttempt::where('status', 'completed')->avg('score') ?? 0, 1) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.results') }}" class="text-white text-decoration-none">
                    <small>Analisis <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-plus"></i><br>
                            Buat Ujian Baru
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.exams.index') }}" class="btn btn-info btn-lg w-100 mb-3">
                            <i class="fas fa-list"></i><br>
                            Kelola Ujian
                        </a>
                    </div>
                                        <div class="col-md-3">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-success btn-lg w-100 mb-3">
                            <i class="fas fa-user-graduate"></i><br>
                            Kelola Siswa
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
