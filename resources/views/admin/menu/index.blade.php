@extends('layouts.app')
@section('title', 'Kelola Menu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Kelola Data Menu</h3>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg"></i> Tambah Menu</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($menu as $m)
                    <tr>
                        <td>{{ $m->id_menu }}</td>
                        <td>{{ $m->nama_menu }}</td>
                        <td>{{ $m->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($m->harga,0,',','.') }}</td>
                        <td>
                            <span class="badge {{ $m->stok <= 5 ? 'bg-danger' : 'bg-success' }}">{{ $m->stok }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.menu.edit', $m->id_menu) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                            <form action="{{ route('admin.menu.destroy', $m->id_menu) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data menu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3 d-flex justify-content-end">{{ $menu->links() }}</div>
@endsection
