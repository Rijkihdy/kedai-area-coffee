@extends('layouts.app')

@section('title', 'Detail Menu')

@section('content')

<div class="container py-4">

    {{-- =========================================================
         INFORMASI DETAIL MENU
    ========================================================== --}}
    <div class="row g-4">

        {{-- Foto Menu --}}
        <div class="col-lg-5">

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

                <div class="bg-light" style="height: 380px;">

                    @if($menu->foto)

                        <img
                            src="{{ asset('storage/'.$menu->foto) }}"
                            alt="{{ $menu->nama_menu }}"
                            class="w-100 h-100 object-fit-cover"
                        >

                    @else

                        <img
                            src="https://images.unsplash.com/featured/?coffee,cafe"
                            alt="{{ $menu->nama_menu }}"
                            class="w-100 h-100 object-fit-cover"
                        >

                    @endif

                </div>

            </div>

        </div>


        {{-- Informasi Menu --}}
        <div class="col-lg-7 d-flex flex-column">

            {{-- Kategori --}}
            <span class="badge bg-dark align-self-start mb-3 px-3 py-2">

                {{ $menu->kategori->nama_kategori ?? 'Menu' }}

            </span>


            {{-- Nama Menu --}}
            <h2 class="fw-bold">

                {{ $menu->nama_menu }}

            </h2>


            {{-- Harga --}}
            <h3 class="fw-bold text-dark mb-3">

                Rp {{ number_format($menu->harga, 0, ',', '.') }}

            </h3>


            {{-- Deskripsi --}}
            <p class="text-muted">

                {{ $menu->deskripsi ?: 'Belum ada deskripsi menu.' }}

            </p>

            <hr>


            {{-- Informasi Stok & Kategori --}}
            <div class="row mb-4">

                {{-- Stok --}}
                <div class="col-6">

                    <div class="border rounded-3 p-3 text-center">

                        <small class="text-muted d-block">
                            Stok
                        </small>

                        <h5 class="fw-bold mb-0">

                            {{ $menu->stok }}

                        </h5>

                    </div>

                </div>


                {{-- Kategori --}}
                <div class="col-6">

                    <div class="border rounded-3 p-3 text-center">

                        <small class="text-muted d-block">
                            Kategori
                        </small>

                        <h6 class="fw-bold mb-0">

                            {{ $menu->kategori->nama_kategori ?? '-' }}

                        </h6>

                    </div>

                </div>

            </div>


            {{-- Tombol Tambah ke Keranjang --}}
            <div class="mt-auto">

                <form
                    action="{{ route('pelanggan.keranjang.tambah', $menu->id_menu) }}"
                    method="POST"
                    class="w-100"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="jumlah"
                        value="1"
                    >

                    <button
                        type="submit"
                        class="btn btn-dark btn-lg w-100"
                    >

                        <i class="bi bi-cart-plus me-2"></i>

                        Tambah ke Keranjang

                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MENU PENDAMPING / SERING DIBELI BERSAMA
    ========================================================== --}}
    <div class="mt-5">

        {{-- Header --}}
        <div class="d-flex align-items-center mb-4">

            <i class="bi bi-stars text-warning fs-3 me-2"></i>

            <div>

                <h3 class="fw-bold mb-0">
                    Menu lainnya
                </h3>

                <small class="text-muted">
                    Menu yang biasanya dibeli dalam transaksi yang sama
                </small>

            </div>

        </div>


        {{-- Daftar Menu Pendamping --}}
        <div class="row g-4">

            @forelse($menuPendamping as $item)

                <div class="col-lg-3 col-md-4 col-sm-6">

                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                        {{-- Foto Menu Pendamping --}}
                        <div style="height: 180px;">

                            @if($item['menu']->foto)

                                <img
                                    src="{{ asset('storage/'.$item['menu']->foto) }}"
                                    alt="{{ $item['menu']->nama_menu }}"
                                    class="w-100 h-100 object-fit-cover"
                                >

                            @else

                                <img
                                    src="https://images.unsplash.com/featured/?coffee,dessert"
                                    alt="{{ $item['menu']->nama_menu }}"
                                    class="w-100 h-100 object-fit-cover"
                                >

                            @endif

                        </div>


                        {{-- Informasi Menu --}}
                        <div class="card-body d-flex flex-column">

                            {{-- Nama --}}
                            <h6 class="fw-bold">

                                {{ $item['menu']->nama_menu }}

                            </h6>


                            {{-- Kategori --}}
                            <small class="text-muted mb-2">

                                {{ $item['menu']->kategori->nama_kategori ?? '-' }}

                            </small>


                            {{-- Harga --}}
                            <div class="fw-bold text-dark fs-5 mb-3">

                                Rp {{ number_format(
                                    $item['menu']->harga,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>


                            {{-- Frekuensi Dibeli Bersama --}}
                            <span class="badge bg-dark mb-3 align-self-start">

                                <i class="bi bi-cart-check me-1"></i>

                                Dibeli bersama
                                {{ $item['frekuensi'] }}
                                {{ $item['frekuensi'] == 1 ? 'kali' : 'kali' }}

                            </span>


                            {{-- Tombol Detail --}}
                            <a
                                href="{{ route(
                                    'pelanggan.menu.show',
                                    $item['menu']->id_menu
                                ) }}"
                                class="btn btn-outline-dark mt-auto"
                            >

                                <i class="bi bi-eye me-2"></i>

                                Lihat Detail

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                {{-- Tidak ada menu pendamping --}}
                <div class="col-12">

                    <div class="alert alert-light border rounded-4 text-center">

                        <i class="bi bi-info-circle me-2"></i>

                        Belum ada menu lain yang tercatat
                        dibeli bersama menu ini.

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection