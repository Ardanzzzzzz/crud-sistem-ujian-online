@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="container mt-4">
    <h2>Daftar Mahasiswa</h2>
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->nim ?? '-' }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
