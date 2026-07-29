<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kedai Area Coffee')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background:#F8F5F2;
            font-family:'Segoe UI',sans-serif;
        }

        .navbar{
            background:linear-gradient(90deg,#6F4E37,#8B5E3C);
            box-shadow:0 5px 15px rgba(0,0,0,.1);
        }

        .navbar-brand{
            font-size:1.35rem;
            font-weight:700;
            color:white!important;
        }

        .navbar-nav .nav-link{
            color:rgba(255,255,255,.85)!important;
            border-radius:8px;
            padding:8px 14px;
            transition:.3s;
        }

        .navbar-nav .nav-link:hover{
            background:rgba(255,255,255,.15);
            color:#fff!important;
        }

        .navbar-nav .nav-link.active{
            background:white;
            color:#6F4E37!important;
            font-weight:600;
        }

        .profile-box{
            display:flex;
            align-items:center;
            gap:10px;
            color:white;
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            background:#fff;
            color:#6F4E37;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
        }

        .badge-role{
            background:#F4C27A;
            color:#4E342E;
            font-size:11px;
            border-radius:20px;
        }

        .btn-logout{
            border-radius:25px;
            padding:6px 18px;
            border:1px solid white;
        }

        .btn-logout:hover{
            background:white;
            color:#6F4E37;
        }

        .page-content{
            background:white;
            border-radius:20px;
            padding:30px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            margin-top:30px;
        }

        .alert{
            border-radius:12px;
        }

        footer{
            color:#888;
            text-align:center;
            padding:20px;
            font-size:.9rem;
        }
        .btn-coffee{
    background:#6F4E37;
    color:#fff;
    border-color:#6F4E37;
}

.btn-coffee:hover{
    color:#fff;
}

.pagination-area .btn{
    min-width:42px;
}
.btn-primary{
    background:#212529 !important;
    border-color:#212529 !important;
}

.btn-primary:hover{
    background:#000 !important;
    border-color:#000 !important;
}

.text-primary{
    color:#D4A017 !important;
}

.bg-primary{
    background:#212529 !important;
}

.border-primary{
    border-color:#212529 !important;
}

    </style>

</head>

<body>

@auth

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container">

<a class="navbar-brand" href="{{ route('dashboard') }}">
    ☕ Kedai Area Coffee
</a>

<button class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navMain">

    <span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navMain">

<ul class="navbar-nav me-auto">

@if(auth()->user()->isPelanggan())

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('pelanggan.menu.*') ? 'active' : '' }}"
       href="{{ route('pelanggan.menu.index') }}">
        <i class="bi bi-cup-hot"></i> Menu
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('pelanggan.keranjang') ? 'active' : '' }}"
       href="{{ route('pelanggan.keranjang') }}">
        <i class="bi bi-cart3"></i> Keranjang
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('pelanggan.pesanan.*') ? 'active' : '' }}"
       href="{{ route('pelanggan.pesanan.riwayat') }}">
        <i class="bi bi-clock-history"></i> Riwayat
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('pelanggan.rekomendasi') ? 'active' : '' }}"
       href="{{ route('pelanggan.rekomendasi') }}">
        <i class="bi bi-stars"></i> Rekomendasi
    </a>
</li>

@elseif(auth()->user()->isAdmin())

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.menu.index') }}">
        <i class="bi bi-grid"></i> Menu
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.kategori.index') }}">
        <i class="bi bi-tags"></i> Kategori
    </a>
</li>

@elseif(auth()->user()->isKasir())

<li class="nav-item">
    <a class="nav-link" href="{{ route('kasir.pesanan.index') }}">
        <i class="bi bi-receipt"></i> Pesanan
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('kasir.transaksi.index') }}">
        <i class="bi bi-cash-stack"></i> Transaksi
    </a>
</li>

@elseif(auth()->user()->isPemilik())

<li class="nav-item">
    <a class="nav-link" href="{{ route('pemilik.laporan') }}">
        <i class="bi bi-bar-chart"></i> Laporan
    </a>
</li>

@endif

</ul>

<div class="dropdown">

    <button class="btn d-flex align-items-center gap-3 text-white border-0"
            data-bs-toggle="dropdown"
            style="background:transparent;">

        <div class="avatar">

            <i class="bi bi-person-fill"></i>

        </div>

        <div class="text-start">

            <div class="fw-semibold">

                {{ auth()->user()->nama }}

            </div>

            <span class="badge badge-role">

                {{ auth()->user()->role }}

            </span>

        </div>

        <i class="bi bi-chevron-down"></i>

    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">

        <li>

            <a class="dropdown-item py-2"
               href="{{ route('profile.edit') }}">

                <i class="bi bi-person-circle me-2"></i>

                Profil Saya

            </a>

        </li>

        <li><hr class="dropdown-divider"></li>

        <li>

            <form action="{{ route('logout') }}" method="POST">

                @csrf

                <button class="dropdown-item py-2">

                    <i class="bi bi-box-arrow-right me-2"></i>

                    Keluar

                </button>

            </form>

        </li>

    </ul>

</div>

</div>

</div>

</nav>

@endauth

<div class="container">

@if(session('sukses'))

<div class="alert alert-success mt-4">

{{ session('sukses') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger mt-4">

<ul class="mb-0">

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<div class="page-content">

@yield('content')

</div>

</div>

<footer>

© {{ date('Y') }} Kedai Area Coffee • Dibuat dengan ❤️ untuk pengalaman ngopi terbaik.

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>