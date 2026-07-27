@extends('layouts.app')
@section('title', 'Dashboard Pelanggan')
@section('content')
<h3 class="mb-1">Halo, {{ auth()->user()->nama }} 👋</h3>
<p class="text-muted mb-4">Selamat datang kembali di Kedai Area Coffee.</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('pelanggan.menu.index') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100"><div class="card-body text-center">
                <i class="bi bi-cup-hot fs-1"></i>
                <div class="mt-2 fw-semibold">Pesan Menu</div>
            </div></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('pelanggan.rekomendasi') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100"><div class="card-body text-center">
                <i class="bi bi-stars fs-1"></i>
                <div class="mt-2 fw-semibold">Rekomendasi Untukmu</div>
            </div></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('pelanggan.pesanan.riwayat') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100"><div class="card-body text-center">
                <i class="bi bi-receipt fs-1"></i>
                <div class="mt-2 fw-semibold">Riwayat Pesanan</div>
            </div></div>
        </a>
    </div>
</div>

<h5 class="mb-3">Menu Terbaru</h5>
<div class="row g-3">
    @foreach($menuTerbaru as $menu)
        <div class="col-md-4">
            <div class="card card-menu shadow-sm h-100">
                <div class="placeholder-img d-flex align-items-center justify-content-center text-muted">
                    <i class="bi bi-image fs-2"></i>
                </div>
                <div class="card-body">
                    <div class="fw-semibold">{{ $menu->nama_menu }}</div>
                    <div class="text-muted small">Rp {{ number_format($menu->harga,0,',','.') }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
