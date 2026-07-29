@extends('layouts.app')

@section('title', 'Daftar Menu')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">
            <i class="bi bi-cup-hot-fill text-primary me-2"></i>
            Daftar Menu
        </h2>

        <p class="text-muted mb-0">
            Temukan berbagai menu favorit Kedai Area Coffee.
        </p>

    </div>

</div>

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <form method="GET" class="row g-3 align-items-end">

            <div class="col-lg-5">

                <label class="form-label fw-semibold">
                    Cari Menu
                </label>

                <input
                    type="text"
                    name="cari"
                    value="{{ request('cari') }}"
                    class="form-control"
                    placeholder="Cari nama menu...">

            </div>

            <div class="col-lg-4">

                <label class="form-label fw-semibold">
                    Kategori
                </label>

                <select name="kategori" class="form-select">

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach($kategori as $k)

                        <option value="{{ $k->id_kategori }}"
                            @selected(request('kategori') == $k->id_kategori)>

                            {{ $k->nama_kategori }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-lg-3">

                <button class="btn btn-primary w-100">

                    <i class="bi bi-funnel-fill me-2"></i>

                    Terapkan Filter

                </button>

            </div>

        </form>

    </div>

</div>

<div class="row g-4">

@forelse($menu as $m)

<div class="col-lg-4 col-md-6">

    <div class="card border-0 shadow-sm rounded-4 h-100 menu-card">

<div class="menu-image">

    @if(!empty($m->gambar))

        <img
            src="{{ asset('storage/'.$m->gambar) }}"
            alt="{{ $m->nama_menu }}"
            class="img-fluid w-100 h-100 object-fit-cover rounded-top-4">

    @else

        @php
            $keyword = strtolower($m->kategori->nama_kategori ?? 'coffee');
        @endphp

        <img
            src="https://images.unsplash.com/featured/?{{ $keyword }},coffee"
            alt="{{ $m->nama_menu }}"
            class="img-fluid w-100 h-100 object-fit-cover rounded-top-4">

    @endif

</div>

        <div class="card-body d-flex flex-column">

            <span class="badge bg-light text-secondary border align-self-start mb-2">

                {{ $m->kategori->nama_kategori ?? '-' }}

            </span>

            <h5 class="fw-bold">

                {{ $m->nama_menu }}

            </h5>

            <p class="text-muted small flex-grow-1">

                {{ \Illuminate\Support\Str::limit($m->deskripsi,80) }}

            </p>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold text-primary mb-0">

                    Rp {{ number_format($m->harga,0,',','.') }}

                </h5>

                @if($m->stok > 5)

                    <span class="badge bg-success">

                        Stok {{ $m->stok }}

                    </span>

                @else

                    <span class="badge bg-danger">

                        Sisa {{ $m->stok }}

                    </span>

                @endif

            </div>
            <a href="{{ route('pelanggan.menu.show',$m->id_menu) }}"
   class="btn btn-outline-dark w-100 mb-2">

    <i class="bi bi-eye me-2"></i>

    Lihat Detail

</a>

            <form
                action="{{ route('pelanggan.keranjang.tambah',$m->id_menu) }}"
                method="POST"
                class="d-flex gap-2 mb-2">

                @csrf

                <input
                    type="number"
                    name="jumlah"
                    value="1"
                    min="1"
                    max="{{ $m->stok }}"
                    class="form-control"
                    style="max-width:80px">

                <button class="btn btn-primary flex-grow-1">

                    <i class="bi bi-cart-plus me-2"></i>

                    Tambah

                </button>

            </form>

            <a href="{{ route('pelanggan.rating.create',$m->id_menu) }}"
               class="text-decoration-none text-warning fw-semibold">

                <i class="bi bi-star-fill"></i>

                Beri Rating

            </a>

        </div>

    </div>

</div>

@empty

<div class="col-12">

    <div class="alert alert-warning rounded-4">

        <i class="bi bi-search me-2"></i>

        Tidak ada menu yang sesuai dengan pencarian.

    </div>

</div>

@endforelse

</div>

@if ($menu->hasPages())

<div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3">

    <small class="text-muted">
        Menampilkan {{ $menu->firstItem() }} - {{ $menu->lastItem() }}
        dari {{ $menu->total() }} menu
    </small>

    <div>

        {{-- Previous --}}
        @if ($menu->onFirstPage())
            <button class="btn btn-light border" disabled>
                <i class="bi bi-chevron-left"></i>
            </button>
        @else
            <a href="{{ $menu->previousPageUrl() }}" class="btn btn-outline-secondary">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        {{-- Nomor --}}
        @foreach ($menu->getUrlRange(1, $menu->lastPage()) as $page => $url)

            @if ($page == $menu->currentPage())

                <span class="btn btn-coffee disabled">
                    {{ $page }}
                </span>

            @else

                <a href="{{ $url }}" class="btn btn-outline-secondary">
                    {{ $page }}
                </a>

            @endif

        @endforeach

        {{-- Next --}}
        @if ($menu->hasMorePages())
            <a href="{{ $menu->nextPageUrl() }}" class="btn btn-outline-secondary">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <button class="btn btn-light border" disabled>
                <i class="bi bi-chevron-right"></i>
            </button>
        @endif

    </div>

</div>

@endif

@endsection