@extends('layouts.app')
@section('title', 'Daftar Menu')
@section('content')
<h3 class="mb-4">Daftar Menu</h3>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari menu...">
    </div>
    <div class="col-md-4">
        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}" @selected(request('kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-dark w-100">Terapkan Filter</button>
    </div>
</form>

<div class="row g-3">
    @forelse($menu as $m)
        <div class="col-md-4">
            <div class="card card-menu shadow-sm h-100">
                <div class="placeholder-img d-flex align-items-center justify-content-center text-muted">
                    <i class="bi bi-cup-hot fs-2"></i>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="fw-semibold">{{ $m->nama_menu }}</div>
                    <div class="text-muted small mb-1">{{ $m->kategori->nama_kategori ?? '-' }}</div>
                    <p class="small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($m->deskripsi, 70) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Rp {{ number_format($m->harga,0,',','.') }}</span>
                        <span class="badge bg-light text-dark border">stok {{ $m->stok }}</span>
                    </div>
                    <form action="{{ route('pelanggan.keranjang.tambah', $m->id_menu) }}" method="POST" class="d-flex mt-2 gap-2">
                        @csrf
                        <input type="number" name="jumlah" value="1" min="1" max="{{ $m->stok }}" class="form-control form-control-sm" style="width:70px">
                        <button class="btn btn-sm btn-dark flex-grow-1">Tambah ke Keranjang</button>
                    </form>
                    <a href="{{ route('pelanggan.rating.create', $m->id_menu) }}" class="small mt-2 text-decoration-none">
                        <i class="bi bi-star"></i> Beri rating menu ini
                    </a>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Tidak ada menu yang cocok.</p>
    @endforelse
</div>
<div class="mt-3">{{ $menu->links() }}</div>
@endsection
