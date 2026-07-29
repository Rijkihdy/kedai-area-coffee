@extends('layouts.app')

@section('title','Detail Menu')

@section('content')

<div class="container py-4">

    <div class="row g-4">

        <!-- Foto Menu -->
        <div class="col-lg-5">

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

                <div class="bg-light" style="height:380px;">

                    @if($menu->foto)

                        <img src="{{ asset('storage/'.$menu->foto) }}"
                             alt="{{ $menu->nama_menu }}"
                             class="w-100 h-100 object-fit-cover">

                    @else

                        <img src="https://images.unsplash.com/featured/?coffee,cafe"
                             alt="{{ $menu->nama_menu }}"
                             class="w-100 h-100 object-fit-cover">

                    @endif

                </div>

            </div>

        </div>

        <!-- Informasi -->
        <div class="col-lg-7 d-flex flex-column">

            <span class="badge bg-dark align-self-start mb-3 px-3 py-2">

                {{ $menu->kategori->nama_kategori ?? 'Menu' }}

            </span>

            <h2 class="fw-bold">

                {{ $menu->nama_menu }}

            </h2>

            <h3 class="fw-bold text-dark mb-3">

                Rp {{ number_format($menu->harga,0,',','.') }}

            </h3>

            <p class="text-muted">

                {{ $menu->deskripsi ?: 'Belum ada deskripsi menu.' }}

            </p>

            <hr>

            <div class="row mb-4">

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

            <div class="mt-auto">

                <a href="{{ route('pelanggan.keranjang.tambah',$menu->id_menu) }}"
                   class="btn btn-dark btn-lg w-100">

                    <i class="bi bi-cart-plus me-2"></i>

                    Tambah ke Keranjang

                </a>

            </div>

        </div>

    </div>



    {{-- Menu Pendamping --}}

    <div class="mt-5">

        <div class="d-flex align-items-center mb-4">

            <i class="bi bi-stars text-warning fs-3 me-2"></i>

            <h3 class="fw-bold mb-0">

                Sering Dibeli Bersama

            </h3>

        </div>

        <div class="row g-4">

            @forelse($menuPendamping as $item)

            <div class="col-lg-3 col-md-4 col-sm-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div style="height:180px;">

                        @if($item['menu']->foto)

                            <img src="{{ asset('storage/'.$item['menu']->foto) }}"
                                 class="w-100 h-100 object-fit-cover rounded-top-4">

                        @else

                            <img src="https://images.unsplash.com/featured/?coffee,dessert"
                                 class="w-100 h-100 object-fit-cover rounded-top-4">

                        @endif

                    </div>

                    <div class="card-body d-flex flex-column">

                        <h6 class="fw-bold">

                            {{ $item['menu']->nama_menu }}

                        </h6>

                        <small class="text-muted mb-2">

                            {{ $item['menu']->kategori->nama_kategori ?? '-' }}

                        </small>

                        <div class="fw-bold text-dark fs-5 mb-2">

                            Rp {{ number_format($item['menu']->harga,0,',','.') }}

                        </div>

                        <span class="badge bg-dark mb-3 align-self-start">

                            Similarity
                            {{ number_format($item['similarity'],4) }}

                        </span>

                        <a href="{{ route('pelanggan.menu.show',$item['menu']->id_menu) }}"
                           class="btn btn-outline-dark mt-auto">

                            <i class="bi bi-eye me-2"></i>

                            Lihat Detail

                        </a>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12">

                <div class="alert alert-light border rounded-4 text-center">

                    <i class="bi bi-info-circle me-2"></i>

                    Belum ada rekomendasi menu pendamping.

                </div>

            </div>

            @endforelse

        </div>

    </div>

</div>

@endsection