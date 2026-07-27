@extends('layouts.app')
@section('title', 'Detail Pesanan #'.$pesanan->id_pesanan)
@section('content')
<h3 class="mb-4">Detail Pesanan #{{ $pesanan->id_pesanan }}</h3>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <p class="mb-1"><strong>Pelanggan:</strong> {{ $pesanan->pelanggan->nama }}</p>
        <p class="mb-1"><strong>Tanggal:</strong> {{ \Illuminate\Support\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y H:i') }}</p>
        <p class="mb-1"><strong>Status:</strong> {{ $pesanan->status }}</p>
        <p class="mb-0"><strong>Pembayaran:</strong> {{ $pesanan->transaksi->status_pembayaran ?? 'belum bayar' }}</p>
    </div>
</div>

<div class="card shadow-sm">
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($pesanan->detail as $d)
                <tr>
                    <td>{{ $d->menu->nama_menu }}</td>
                    <td>{{ $d->jumlah }}</td>
                    <td>Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-light"><td colspan="2" class="fw-bold">Total</td><td class="fw-bold">Rp {{ number_format($pesanan->total_harga,0,',','.') }}</td></tr>
        </tfoot>
    </table>
</div>

<a href="{{ route('pelanggan.pesanan.riwayat') }}" class="btn btn-outline-secondary mt-3">Kembali ke Riwayat</a>
@endsection
