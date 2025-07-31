@extends('layouts.app')

@section('title', 'Hasil Ujian')

@section('content')
<div class="container mt-4">
    <h2>Daftar Hasil Ujian</h2>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama Peserta</th>
                <th>Ujian</th>
                <th>Nilai</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $result)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $result->user->name }}</td>
                    <td>{{ $result->exam->title }}</td>
                    <td>{{ $result->score }}</td>
                    <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada hasil ujian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
