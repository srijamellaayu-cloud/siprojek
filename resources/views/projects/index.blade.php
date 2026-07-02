@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2>Daftar Proyek</h2>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Tambah Proyek</a>



    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Deadline</th>
            <th>Aksi</th>
        </tr>
        @foreach($projects as $p)
        <tr>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->deskripsi }}</td>
            <td>{{ $p->deadline }}</td>
            <td>
                <a href="{{ route('projects.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('projects.destroy', $p->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection