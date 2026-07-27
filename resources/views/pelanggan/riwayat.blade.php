@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('content')
<h3 class="mb-4">Riwayat Pesanan</h3>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Tanggal</th><th>Total</th><th>Status Pesanan</th><th>Pembayaran</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                    <tr>
                        <td>{{ $p->id_pesanan }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($p->tanggal_pesanan)->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($p->total_harga,0,',','.') }}</td>
                        <td>
                            <span class="badge bg-{{ match($p->status){'selesai'=>'success','diproses'=>'info','dibatalkan'=>'danger',default=>'warning text-dark'} }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td>
                            @if($p->transaksi)
                                <span class="badge bg-{{ $p->transaksi->status_pembayaran === 'lunas' ? 'success' : 'secondary' }}">
                                    {{ $p->transaksi->status_pembayaran }}
                                </span>
                            @else
                                <span class="badge bg-secondary">belum bayar</span>
                            @endif
                        </td>
                        <td><a href="{{ route('pelanggan.pesanan.show', $p->id_pesanan) }}" class="btn btn-sm btn-outline-dark">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $pesanan->links() }}</div>
@endsection
