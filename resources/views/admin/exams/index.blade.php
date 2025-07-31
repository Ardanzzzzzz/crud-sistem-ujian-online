@extends('layouts.app')

@section('title', 'Kelola Ujian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clipboard-list"></i> Kelola Ujian</h2>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Ujian Baru
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($exams->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Durasi</th>
                                    <th>Total Soal</th>
                                    <th>Total Percobaan</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $exam)
                                <tr>
                                    <td>
                                        <strong>{{ $exam->title }}</strong>
                                        @if($exam->description)
                                            <br><small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $exam->duration }} menit</td>
                                    <td>
                                        <span class="badge bg-info">{{ $exam->questions_count }} soal</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $exam->exam_attempts_count }} percobaan</span>
                                    </td>
                                    <td>
                                        @if($exam->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $exam->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.exams.questions.index', $exam) }}" class="btn btn-sm btn-warning" title="Kelola Soal">
                                                <i class="fas fa-question-circle"></i>
                                            </a>
                                            <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Yakin ingin menghapus ujian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>Belum ada ujian yang dibuat</p>
                        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Buat Ujian Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection