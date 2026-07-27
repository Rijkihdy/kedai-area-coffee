<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kedai Area Coffee')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f7f3ee; }
        .navbar-brand { font-weight:700; letter-spacing:.5px; }
        .card-menu img, .card-menu .placeholder-img { height:160px; object-fit:cover; background:#e9ecef; }
        .badge-role { font-size:.7rem; }
    </style>
</head>
<body>
@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}"><i class="bi bi-cup-hot-fill"></i> Kedai Area Coffee</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->isPelanggan())
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.menu.index') }}">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.keranjang') }}">Keranjang</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.pesanan.riwayat') }}">Riwayat Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.rekomendasi') }}">Rekomendasi</a></li>
                    @elseif(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.menu.index') }}">Kelola Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.kategori.index') }}">Kelola Kategori</a></li>
                    @elseif(auth()->user()->isKasir())
                        <li class="nav-item"><a class="nav-link" href="{{ route('kasir.pesanan.index') }}">Kelola Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('kasir.transaksi.index') }}">Kelola Transaksi</a></li>
                    @elseif(auth()->user()->isPemilik())
                        <li class="nav-item"><a class="nav-link" href="{{ route('pemilik.laporan') }}">Laporan Penjualan</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center me-3">
                    <span class="text-white-50 small">{{ auth()->user()->nama }}
                        <span class="badge bg-secondary badge-role">{{ auth()->user()->role }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endauth

<div class="container mb-5">
    @if(session('sukses'))
        <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
