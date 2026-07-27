@extends('layouts.app')
@section('title', 'Proses Pembayaran')
@section('content')
<h3 class="mb-4">Proses Pembayaran - Pesanan #{{ $pesanan->id_pesanan }}</h3>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <p class="mb-1"><strong>Pelanggan:</strong> {{ $pesanan->pelanggan->nama }}</p>
        <table class="table mt-2">
            <thead><tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($pesanan->detail as $d)
                    <tr><td>{{ $d->menu->nama_menu }}</td><td>{{ $d->jumlah }}</td><td>Rp {{ number_format($d->subtotal,0,',','.') }}</td></tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light"><td colspan="2" class="fw-bold">Total</td><td class="fw-bold">Rp {{ number_format($pesanan->total_harga,0,',','.') }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('kasir.transaksi.store', $pesanan->id_pesanan) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="form-select" required>
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="e-wallet">E-Wallet</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status Pembayaran</label>
                <select name="status_pembayaran" class="form-select" required>
                    <option value="lunas">Lunas</option>
                    <option value="belum_lunas">Belum Lunas</option>
                </select>
            </div>
            <button class="btn btn-dark">Simpan Transaksi</button>
        </form>
    </div>
</div>
@endsection
