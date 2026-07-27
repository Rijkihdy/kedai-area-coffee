@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('content')
<h3 class="mb-4">Kelola Kategori Menu</h3>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header">Tambah Kategori</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.kategori.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required>
                    </div>
                    <button class="btn btn-dark w-100">Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <ul class="list-group list-group-flush">
                @forelse($kategori as $k)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <form method="POST" action="{{ route('admin.kategori.update', $k->id_kategori) }}" class="d-flex flex-grow-1 me-2">
                            @csrf @method('PUT')
                            <input type="text" name="nama_kategori" value="{{ $k->nama_kategori }}" class="form-control form-control-sm me-2">
                            <button class="btn btn-sm btn-outline-secondary me-2">Simpan</button>
                        </form>
                        <span class="badge bg-secondary me-2">{{ $k->menu_count }} menu</span>
                        <form method="POST" action="{{ route('admin.kategori.destroy', $k->id_kategori) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Belum ada kategori.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
