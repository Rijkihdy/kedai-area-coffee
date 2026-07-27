@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('content')
<h3 class="mb-4">Riwayat Transaksi</h3>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Pelanggan</th><th>Total Bayar</th><th>Metode</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                    <tr>
                        <td>{{ $t->id_transaksi }}</td>
                        <td>{{ $t->pesanan->pelanggan->nama }}</td>
                        <td>Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                        <td>{{ $t->metode_pembayaran }}</td>
                        <td>
                            <span class="badge bg-{{ $t->status_pembayaran==='lunas'?'success':'secondary' }}">{{ $t->status_pembayaran }}</span>
                        </td>
                        <td><a href="{{ route('kasir.transaksi.struk', $t->id_transaksi) }}" class="btn btn-sm btn-outline-dark">Struk</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $transaksi->links() }}</div>
@endsection
