@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')
<h3 class="mb-4">Laporan Penjualan</h3>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <label class="form-label small">Dari</label>
        <input type="date" name="dari" value="{{ $dari }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label small">Sampai</label>
        <input type="date" name="sampai" value="{{ $sampai }}" class="form-control">
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-dark w-100">Terapkan</button>
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-outline-dark w-100 no-print" onclick="window.print()" type="button">Cetak</button>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Total Penjualan Periode Ini</div>
            <div class="fs-3 fw-bold">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Jumlah Transaksi</div>
            <div class="fs-3 fw-bold">{{ $jumlahTransaksi }}</div>
        </div></div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header">Menu Terlaris pada Periode Ini</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Menu</th><th>Total Terjual</th><th>Total Omzet</th></tr></thead>
        <tbody>
            @forelse($menuTerlaris as $m)
                <tr>
                    <td>{{ $m->nama_menu }}</td>
                    <td>{{ (int) $m->total_terjual }}</td>
                    <td>Rp {{ number_format($m->total_omzet,0,',','.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card shadow-sm">
    <div class="card-header">Detail Transaksi</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th></tr></thead>
            <tbody>
                @forelse($transaksi as $t)
                    <tr>
                        <td>{{ $t->id_transaksi }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($t->pesanan->tanggal_pesanan)->format('d M Y H:i') }}</td>
                        <td>{{ $t->pesanan->pelanggan->nama }}</td>
                        <td>Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>@media print { .navbar, .no-print { display:none !important; } }</style>
@endsection
