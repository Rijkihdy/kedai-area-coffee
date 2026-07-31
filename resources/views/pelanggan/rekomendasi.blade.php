@extends('layouts.app')

@section('title', 'Rekomendasi Menu')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            <i class="bi bi-stars text-warning me-2"></i>
            Rekomendasi Menu
        </h2>

        <p class="text-muted mb-0">
            Menu pilihan khusus untuk Anda.
        </p>

    </div>

    <form action="{{ route('pelanggan.rekomendasi.hitung-ulang') }}" method="POST">

        @csrf

        <button class="btn btn-primary">

            <i class="bi bi-arrow-repeat me-2"></i>

            Hitung Ulang

        </button>

    </form>

</div>

@if($rekomendasi->count())

<div class="alert alert-light border rounded-4 shadow-sm mb-4">

    <i class="bi bi-info-circle-fill text-primary me-2"></i>

    Rekomendasi dihasilkan menggunakan
    <strong>Item-Based Collaborative Filtering</strong>
    berdasarkan riwayat pemesanan Anda.

</div>

<div class="row g-4">

@foreach($rekomendasi as $r)

<div class="col-lg-4 col-md-6">

    <div class="card border-0 shadow-sm rounded-4 h-100 menu-card">

      <div class="menu-image">

    @if(!empty($r->menu->gambar))

        <img
            src="{{ asset('storage/'.$r->menu->gambar) }}"
            alt="{{ $r->menu->nama_menu }}"
            class="w-100 h-100 object-fit-cover rounded-top-4">

    @else

        @php
            $kategori = strtolower($r->menu->kategori->nama_kategori ?? '');

            if(str_contains($kategori,'coffee')){
                $image='https://images.unsplash.com/featured/?coffee';
            }elseif(str_contains($kategori,'tea')){
                $image='https://images.unsplash.com/featured/?tea';
            }elseif(str_contains($kategori,'dessert')){
                $image='https://images.unsplash.com/featured/?cake';
            }elseif(str_contains($kategori,'snack')){
                $image='https://images.unsplash.com/featured/?snack';
            }else{
                $image='https://images.unsplash.com/featured/?cafe-drink';
            }
        @endphp

        <img
            src="{{ $image }}"
            alt="{{ $r->menu->nama_menu }}"
            class="w-100 h-100 object-fit-cover rounded-top-4">

    @endif

</div>
        <div class="card-body d-flex flex-column">

            <span class="badge bg-light text-secondary border align-self-start mb-2">

                {{ $r->menu->kategori->nama_kategori ?? '-' }}

            </span>

            <h5 class="fw-bold">

                {{ $r->menu->nama_menu }}

            </h5>

            <div class="text-success fw-bold fs-5 mb-3">

                Rp {{ number_format($r->menu->harga,0,',','.') }}

            </div>

            <div class="mb-3">

                <span class="badge bg-primary">

                    Skor {{ number_format($r->skor_rekomendasi,3) }}

                </span>

            </div>

            <div class="mt-auto">

                <form action="{{ route('pelanggan.keranjang.tambah',$r->menu->id_menu) }}" method="POST">

                    @csrf

                    <input type="hidden" name="jumlah" value="1">

                    <button class="btn btn-primary w-100">

                        <i class="bi bi-cart-plus me-2"></i>

                        Tambah ke Keranjang

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endforeach

</div>

@else

<div class="alert alert-warning rounded-4 shadow-sm mb-4">

    <i class="bi bi-fire me-2"></i>

    Belum ada cukup riwayat pesanan.
    Berikut menu paling populer di Kedai Area Coffee.

</div>

<div class="row g-4">

@foreach($populer as $m)

<div class="col-lg-4 col-md-6">

    <div class="card border-0 shadow-sm rounded-4 h-100 menu-card">

        <div class="menu-image">

            <i class="bi bi-fire"></i>

        </div>

        <div class="card-body d-flex flex-column">

            <h5 class="fw-bold">

                {{ $m['nama_menu'] }}

            </h5>

            <div class="text-success fw-bold fs-5 mb-3">

                Rp {{ number_format($m['harga'],0,',','.') }}

            </div>

            @if(isset($m['total_terjual']))

                <span class="badge bg-secondary mb-3">

                    🔥 {{ $m['total_terjual'] }}x Terjual

                </span>

            @endif

            <div class="mt-auto">

                <button class="btn btn-outline-secondary w-100" disabled>

                    Menu Populer

                </button>

            </div>

        </div>

    </div>

</div>

@endforeach

</div>

@endif

@endsection