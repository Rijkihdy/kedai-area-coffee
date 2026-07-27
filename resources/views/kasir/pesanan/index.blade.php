@extends('layouts.app')
@section('title', 'Kelola Pesanan')
@section('content')
<h3 class="mb-4">Kelola Pesanan</h3>

<div class="btn-group mb-3">
    <a href="{{ route('kasir.pesanan.index') }}" class="btn btn-sm btn-outline-dark {{ !$status ? 'active' : '' }}">Semua</a>
    <a href="{{ route('kasir.pesanan.index', ['status'=>'menunggu']) }}" class="btn btn-sm btn-outline-dark {{ $status=='menunggu'?'active':'' }}">Menunggu</a>
    <a href="{{ route('kasir.pesanan.index', ['status'=>'diproses']) }}" class="btn btn-sm btn-outline-dark {{ $status=='diproses'?'active':'' }}">Diproses</a>
    <a href="{{ route('kasir.pesanan.index', ['status'=>'selesai']) }}" class="btn btn-sm btn-outline-dark {{ $status=='selesai'?'active':'' }}">Selesai</a>
    <a href="{{ route('kasir.pesanan.index', ['status'=>'dibatalkan']) }}" class="btn btn-sm btn-outline-dark {{ $status=='dibatalkan'?'active':'' }}">Dibatalkan</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Pelanggan</th><th>Menu</th><th>Total</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                    <tr>
                        <td>{{ $p->id_pesanan }}</td>
                        <td>{{ $p->pelanggan->nama }}</td>
                        <td>
                            <small>{{ $p->detail->map(fn($d) => $d->menu->nama_menu.' x'.$d->jumlah)->join(', ') }}</small>
                        </td>
                        <td>Rp {{ number_format($p->total_harga,0,',','.') }}</td>
                        <td>
                            <form action="{{ route('kasir.pesanan.status', $p->id_pesanan) }}" method="POST" class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @foreach(['menunggu','diproses','selesai','dibatalkan'] as $s)
                                        <option value="{{ $s }}" @selected($p->status===$s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            @if(!$p->transaksi)
                                <a href="{{ route('kasir.transaksi.create', $p->id_pesanan) }}" class="btn btn-sm btn-dark">Bayar</a>
                            @else
                                <a href="{{ route('kasir.transaksi.struk', $p->transaksi->id_transaksi) }}" class="btn btn-sm btn-outline-dark">Struk</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $pesanan->links() }}</div>
@endsection
