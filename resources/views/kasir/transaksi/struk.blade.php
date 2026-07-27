@extends('layouts.app')
@section('title', 'Struk Transaksi')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm" id="struk">
            <div class="card-body">
                <h5 class="text-center mb-0">Kedai Area Coffee</h5>
                <p class="text-center text-muted small mb-3">Struk Pembayaran</p>
                <hr>
                <p class="small mb-1"><strong>No. Transaksi:</strong> {{ $transaksi->id_transaksi }}</p>
                <p class="small mb-1"><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y H:i') }}</p>
                <p class="small mb-3"><strong>Pelanggan:</strong> {{ $transaksi->pesanan->pelanggan->nama }}</p>
                <table class="table table-sm">
                    <tbody>
                        @foreach($transaksi->pesanan->detail as $d)
                            <tr>
                                <td>{{ $d->menu->nama_menu }} x{{ $d->jumlah }}</td>
                                <td class="text-end">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold"><td>Total</td><td class="text-end">Rp {{ number_format($transaksi->total_bayar,0,',','.') }}</td></tr>
                    </tfoot>
                </table>
                <p class="small mb-1"><strong>Metode:</strong> {{ $transaksi->metode_pembayaran }}</p>
                <p class="small mb-0"><strong>Status:</strong> {{ $transaksi->status_pembayaran }}</p>
                <hr>
                <p class="text-center small text-muted mb-0">Terima kasih telah berbelanja!</p>
            </div>
        </div>
        <div class="text-center mt-3 no-print">
            <button class="btn btn-dark" onclick="window.print()">Cetak Struk</button>
            <a href="{{ route('kasir.pesanan.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
</div>
<style>@media print { .navbar, .no-print { display:none !important; } }</style>
@endsection
