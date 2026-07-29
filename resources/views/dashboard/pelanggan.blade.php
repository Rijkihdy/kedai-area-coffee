@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')

<div class="p-5 rounded-4 mb-5 text-white shadow"
    style="background:linear-gradient(135deg,#6F4E37,#8B5E3C);">

    <div class="row align-items-center">

        <div class="col-md-8">

            <h2 class="fw-bold mb-2">
                Halo, {{ auth()->user()->nama }} 👋
            </h2>

            <p class="mb-0 opacity-75">
                Selamat datang kembali di Kedai Area Coffee.
                Temukan menu favoritmu hari ini.
            </p>

        </div>

        <div class="col-md-4 text-end d-none d-md-block">

            <i class="bi bi-cup-hot-fill"
                style="font-size:90px;opacity:.2;"></i>

        </div>

    </div>

</div>

<div class="row g-4 mb-5">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-body text-center">

                <div class="bg-warning bg-opacity-25 rounded-circle d-inline-flex justify-content-center align-items-center mb-3"
                    style="width:70px;height:70px;">

                    <i class="bi bi-cup-hot-fill fs-2 text-warning"></i>

                </div>

                <h5 class="fw-bold">Pesan Menu</h5>

                <p class="text-muted">
                    Jelajahi berbagai menu kopi dan makanan.
                </p>

                <a href="{{ route('pelanggan.menu.index') }}"
                    class="btn btn-outline-warning rounded-pill px-4">

                    Lihat Menu

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-body text-center">

                <div class="bg-success bg-opacity-25 rounded-circle d-inline-flex justify-content-center align-items-center mb-3"
                    style="width:70px;height:70px;">

                    <i class="bi bi-stars fs-2 text-success"></i>

                </div>

                <h5 class="fw-bold">
                    Rekomendasi
                </h5>

                <p class="text-muted">
                    Menu pilihan khusus sesuai riwayat pesananmu.
                </p>

                <a href="{{ route('pelanggan.rekomendasi') }}"
                    class="btn btn-outline-success rounded-pill px-4">

                    Lihat

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-body text-center">

                <div class="bg-primary bg-opacity-25 rounded-circle d-inline-flex justify-content-center align-items-center mb-3"
                    style="width:70px;height:70px;">

                    <i class="bi bi-clock-history fs-2 text-primary"></i>

                </div>

                <h5 class="fw-bold">
                    Riwayat
                </h5>

                <p class="text-muted">
                    Lihat pesanan yang pernah dilakukan.
                </p>

                <a href="{{ route('pelanggan.pesanan.riwayat') }}"
                    class="btn btn-outline-primary rounded-pill px-4">

                    Buka

                </a>

            </div>

        </div>

    </div>

</div>

<h3 class="fw-bold mb-4">

    ☕ Menu Terbaru

</h3>

<div class="row g-4">

@foreach($menuTerbaru as $menu)

<div class="col-lg-4 col-md-6">

<div class="card border-0 shadow rounded-4 overflow-hidden h-100">

    <img src="{{ $menu->gambar
        ? asset('storage/'.$menu->gambar)
        : 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600'
    }}"
    style="height:220px;object-fit:cover;">

    <div class="card-body">

        <h5 class="fw-bold">

            {{ $menu->nama_menu }}

        </h5>

        <h4 class="text-warning fw-bold">

            Rp {{ number_format($menu->harga,0,',','.') }}

        </h4>

        <a href="{{ route('pelanggan.menu.show',$menu->id_menu) }}"
            class="btn btn-dark rounded-pill w-100 mt-3">

            Lihat Detail

        </a>

    </div>

</div>

</div>

@endforeach

</div>

@endsection