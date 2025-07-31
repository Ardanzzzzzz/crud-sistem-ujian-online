@extends('layouts.app')

@section('title', 'Detail Ujian')

@section('content')
<div class="container mt-4">
    <h2>Detail Ujian</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Judul:</strong> {{ $exam->title }}</p>
            <p><strong>Durasi:</strong> {{ $exam->duration }} menit</p>
            <p><strong>Tanggal Dibuat:</strong> {{ $exam->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
