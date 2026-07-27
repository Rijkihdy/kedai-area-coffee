@extends('layouts.app')
@section('title', 'Rekomendasi Menu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="bi bi-stars"></i> Rekomendasi Untukmu</h3>
    <form action="{{ route('pelanggan.rekomendasi.hitung-ulang') }}" method="POST">
        @csrf
        <button class="btn btn-sm btn-outline-dark">Hitung Ulang Rekomendasi</button>
    </form>
</div>

@if($rekomendasi->count())
    <p class="text-muted">Rekomendasi berikut dihasilkan menggunakan metode <strong>Item-Based Collaborative Filtering</strong> berdasarkan riwayat pemesananmu.</p>
    <div class="row g-3">
        @foreach($rekomendasi as $r)
            <div class="col-md-4">
                <div class="card card-menu shadow-sm h-100">
                    <div class="placeholder-img d-flex align-items-center justify-content-center text-muted">
                        <i class="bi bi-stars fs-2"></i>
                    </div>
                    <div class="card-body">
                        <div class="fw-semibold">{{ $r->menu->nama_menu }}</div>
                        <div class="text-muted small mb-1">{{ $r->menu->kategori->nama_kategori ?? '-' }}</div>
                        <div class="fw-bold">Rp {{ number_format($r->menu->harga,0,',','.') }}</div>
                        <span class="badge bg-dark mt-2">Skor rekomendasi: {{ number_format($r->skor_rekomendasi, 3) }}</span>
                        <form action="{{ route('pelanggan.keranjang.tambah', $r->menu->id_menu) }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="jumlah" value="1">
                            <button class="btn btn-sm btn-dark w-100">Tambah ke Keranjang</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted">Kamu belum punya riwayat pesanan yang cukup, jadi berikut menu paling populer di Kedai Area Coffee:</p>
    <div class="row g-3">
        @foreach($populer as $m)
            <div class="col-md-4">
                <div class="card card-menu shadow-sm h-100">
                    <div class="placeholder-img d-flex align-items-center justify-content-center text-muted">
                        <i class="bi bi-fire fs-2"></i>
                    </div>
                    <div class="card-body">
                        <div class="fw-semibold">{{ $m['nama_menu'] }}</div>
                        <div class="fw-bold">Rp {{ number_format($m['harga'],0,',','.') }}</div>
                        @if(isset($m['total_terjual']))
                            <span class="badge bg-secondary mt-2">{{ $m['total_terjual'] }}x terjual</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
