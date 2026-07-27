@extends('layouts.app')
@section('title', 'Dashboard Pemilik')
@section('content')
<h3 class="mb-4">Dashboard Pemilik Cafe</h3>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Total Penjualan (Lunas)</div>
            <div class="fs-3 fw-bold">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Jumlah Transaksi</div>
            <div class="fs-3 fw-bold">{{ $totalTransaksi }}</div>
        </div></div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header">5 Menu Terlaris</div>
    <ul class="list-group list-group-flush">
        @forelse($menuTerlaris as $m)
            <li class="list-group-item d-flex justify-content-between">
                {{ $m->nama_menu }} <span class="badge bg-dark">{{ (int) $m->total }} terjual</span>
            </li>
        @empty
            <li class="list-group-item text-muted">Belum ada data penjualan.</li>
        @endforelse
    </ul>
</div>

<a href="{{ route('pemilik.laporan') }}" class="btn btn-dark">Lihat Laporan Penjualan Lengkap</a>
@endsection
