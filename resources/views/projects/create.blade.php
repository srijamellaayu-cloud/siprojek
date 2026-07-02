@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Tambah Proyek</h2>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="form-group mb-2">
            <label>Nama Proyek</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
        </div>
        <div class="form-group mb-2">
            <label>Deadline</label>
            <input type="date" name="deadline" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
