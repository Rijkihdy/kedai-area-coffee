@extends('layouts.app')
@section('title', 'Keranjang')
@section('content')
<h3 class="mb-4">Keranjang Belanja</h3>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item['menu']->nama_menu }}</td>
                        <td>{{ $item['jumlah'] }}</td>
                        <td>Rp {{ number_format($item['subtotal'],0,',','.') }}</td>
                        <td>
                            <form action="{{ route('pelanggan.keranjang.hapus', $item['menu']->id_menu) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Keranjang masih kosong. <a href="{{ route('pelanggan.menu.index') }}">Pilih menu</a></td></tr>
                @endforelse
            </tbody>
            @if(count($items))
            <tfoot>
                <tr class="table-light">
                    <td colspan="2" class="fw-bold">Total</td>
                    <td class="fw-bold">Rp {{ number_format($total,0,',','.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@if(count($items))
<form action="{{ route('pelanggan.checkout') }}" method="POST" class="mt-3">
    @csrf
    <button class="btn btn-dark btn-lg">Checkout Pesanan</button>
</form>
@endif
@endsection
