@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Edit Proyek</h2>

    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="js-track-changes-form">
        @csrf @method('PUT')
        <div class="form-group mb-2">
            <label>Nama Proyek</label>
            <input type="text" name="nama" class="form-control" value="{{ $project->nama }}" required>
        </div>
        <div class="form-group mb-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ $project->deskripsi }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label>Deadline</label>
            <input type="date" name="deadline" class="form-control" value="{{ $project->deadline }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
