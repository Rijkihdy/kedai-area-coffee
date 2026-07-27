@extends('layouts.app')
@section('title', 'Dashboard Kasir')
@section('content')
<h3 class="mb-4">Dashboard Kasir</h3>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                Pesanan Menunggu <span class="badge bg-warning text-dark">{{ $pesananMenunggu->count() }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($pesananMenunggu as $p)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>#{{ $p->id_pesanan }}</strong> - {{ $p->pelanggan->nama }}<br>
                                <small class="text-muted">Rp {{ number_format($p->total_harga,0,',','.') }}</small>
                            </div>
                            <a href="{{ route('kasir.transaksi.create', $p->id_pesanan) }}" class="btn btn-sm btn-dark align-self-center">Proses Bayar</a>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Tidak ada pesanan menunggu.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                Sedang Diproses <span class="badge bg-info text-dark">{{ $pesananDiproses->count() }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($pesananDiproses as $p)
                    <li class="list-group-item d-flex justify-content-between">
                        <div><strong>#{{ $p->id_pesanan }}</strong> - {{ $p->pelanggan->nama }}</div>
                        <form action="{{ route('kasir.pesanan.status', $p->id_pesanan) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="selesai">
                            <button class="btn btn-sm btn-success">Tandai Selesai</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Tidak ada pesanan diproses.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('kasir.pesanan.index') }}" class="btn btn-outline-dark">Lihat Semua Pesanan</a>
    <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-outline-dark">Riwayat Transaksi</a>
</div>
@endsection
