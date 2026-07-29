<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kedai Area Coffee</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            background:#f8f5f2;
            font-family:'Segoe UI',sans-serif;
        }

        /* ===========================
           NAVBAR
        ============================*/

        .navbar{
            background:#6F4E37;
            box-shadow:0 5px 20px rgba(0,0,0,.15);
        }

        .navbar-brand{
            font-size:1.4rem;
            font-weight:700;
            color:#fff !important;
        }

        .navbar-brand:hover{
            color:#f5e6d6 !important;
        }

        /* ===========================
           HERO
        ============================*/

        .hero{

            min-height:88vh;

            display:flex;
            align-items:center;

            background:
            linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
            url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1600&q=80');

            background-size:cover;
            background-position:center;

            color:white;

        }

        .hero h1{

            font-size:3.6rem;
            font-weight:700;

        }

        .hero p{

            font-size:1.2rem;
            max-width:700px;
            margin:auto;

        }

        /* ===========================
           BUTTON
        ============================*/

        .btn-coffee{

            background:#6F4E37;
            color:#fff;
            border:none;

        }

        .btn-coffee:hover{

            background:#533726;
            color:#fff;

        }

        .btn-outline-coffee{

            border:2px solid #fff;
            color:#fff;

        }

        .btn-outline-coffee:hover{

            background:#fff;
            color:#6F4E37;

        }

        /* ===========================
           CARD
        ============================*/

        .menu-card{

            border:none;
            border-radius:18px;
            overflow:hidden;
            transition:.3s;

        }

        .menu-card:hover{

            transform:translateY(-8px);

            box-shadow:0 12px 30px rgba(0,0,0,.18);

        }

        .menu-card img{

            height:230px;
            object-fit:cover;

        }

        .section-title{

            font-weight:700;
            margin-bottom:50px;

        }

        /* ===========================
           FEATURE
        ============================*/

        .feature{

            padding:35px 25px;

            border-radius:18px;

            background:white;

            transition:.3s;

            box-shadow:0 4px 15px rgba(0,0,0,.08);

        }

        .feature:hover{

            transform:translateY(-8px);

        }

        .feature i{

            font-size:50px;
            color:#6F4E37;

        }

        /* ===========================
           FOOTER
        ============================*/

        footer{

            background:#4E342E;
            color:#fff;

        }

        footer p,
        footer small{

            color:#e6d8ce;

        }

    </style>

</head>

<body>

<!-- ===========================
     NAVBAR
===========================-->

<nav class="navbar navbar-expand-lg navbar-dark">

    <div class="container">

        <a class="navbar-brand" href="#">
            ☕ Kedai Area Coffee
        </a>

        <div class="ms-auto">

            @if(Route::has('login'))

                @auth

                    <a href="{{ url('/dashboard') }}"
                       class="btn btn-light">

                        Dashboard

                    </a>

                @else

                    <a href="{{ route('login') }}"
                       class="btn btn-outline-light me-2">

                        Login

                    </a>

                    @if(Route::has('register'))

                        <a href="{{ route('register') }}"
                           class="btn btn-warning">

                            Register

                        </a>

                    @endif

                @endauth

            @endif

        </div>

    </div>

</nav>


<!-- ===========================
     HERO
===========================-->

<section class="hero text-center">

    <div class="container">

        <h1>

            Selamat Datang di
            <br>
            Kedai Area Coffee

        </h1>

        <p class="mt-4">

            Nikmati berbagai pilihan kopi, minuman,
            makanan, dan camilan favorit dengan
            sistem pemesanan yang cepat,
            mudah, dan modern.

        </p>

        <!-- <div class="mt-5">

            <a href="{{ route('login') }}"
               class="btn btn-coffee btn-lg px-5 me-2">

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Login

            </a>

            @if(Route::has('register'))

            <a href="{{ route('register') }}"
               class="btn btn-outline-coffee btn-lg px-5">

                <i class="bi bi-person-plus me-2"></i>

                Register

            </a>

            @endif

        </div> -->

    </div>

</section>
<!-- ===========================
     MENU FAVORIT
===========================-->

<section class="container py-5">

    <h2 class="text-center section-title">
        Menu Favorit
    </h2>

    <div class="row g-4">

        <div class="col-lg-4 col-md-6">

            <div class="card menu-card shadow-sm">

                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=700"
                     class="card-img-top"
                     alt="Espresso">

                <div class="card-body text-center">

                    <h4 class="fw-bold">
                        Espresso
                    </h4>

                    <p class="text-muted">
                        Kopi hitam dengan aroma khas dan rasa yang kuat,
                        cocok untuk pecinta kopi sejati.
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="card menu-card shadow-sm">

                <img src="https://images.unsplash.com/photo-1497636577773-f1231844b336?w=700"
                     class="card-img-top"
                     alt="Cappuccino">

                <div class="card-body text-center">

                    <h4 class="fw-bold">
                        Cappuccino
                    </h4>

                    <p class="text-muted">
                        Perpaduan espresso, susu segar,
                        dan foam lembut yang nikmat.
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="card menu-card shadow-sm">

                <img src="https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=700"
                     class="card-img-top"
                     alt="Latte">

                <div class="card-body text-center">

                    <h4 class="fw-bold">
                        Latte
                    </h4>

                    <p class="text-muted">
                        Minuman kopi creamy dengan rasa
                        yang lembut dan disukai semua kalangan.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ===========================
     KEUNGGULAN
===========================-->

<section class="py-5" style="background:#fff;">

    <div class="container">

        <h2 class="text-center section-title">

            Mengapa Memilih Kami?

        </h2>

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="feature text-center h-100">

                    <i class="bi bi-cup-hot-fill"></i>

                    <h4 class="mt-3">

                        Kopi Berkualitas

                    </h4>

                    <p class="text-muted mb-0">

                        Menggunakan biji kopi pilihan
                        dengan cita rasa terbaik.

                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature text-center h-100">

                    <i class="bi bi-cake2-fill"></i>

                    <h4 class="mt-3">

                        Menu Lengkap

                    </h4>

                    <p class="text-muted mb-0">

                        Berbagai pilihan makanan,
                        dessert, dan minuman.

                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature text-center h-100">

                    <i class="bi bi-lightning-charge-fill"></i>

                    <h4 class="mt-3">

                        Pelayanan Cepat

                    </h4>

                    <p class="text-muted mb-0">

                        Sistem pemesanan digital
                        yang mudah dan efisien.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ===========================
     CTA
===========================-->

<section class="py-5 text-center">

    <div class="container">

        <h2 class="fw-bold">

            Siap Menikmati Kopi Favoritmu?

        </h2>

        <p class="text-muted mb-4">

            Masuk ke akunmu dan mulai memesan
            menu favorit hanya dalam beberapa klik.

        </p>

        <a href="{{ route('login') }}"
           class="btn btn-coffee btn-lg px-5">

            <i class="bi bi-cart-fill me-2"></i>

            Pesan Sekarang

        </a>

    </div>

</section>


<!-- ===========================
     FOOTER
===========================-->

<footer class="py-5">

    <div class="container">

        <div class="row">

            <div class="col-lg-6">

                <h4 class="fw-bold">

                    ☕ Kedai Area Coffee

                </h4>

                <p class="mt-3">

                    Kedai Area Coffee menghadirkan
                    berbagai pilihan kopi,
                    makanan, dan minuman terbaik
                    dengan pelayanan yang cepat
                    dan nyaman.

                </p>

            </div>

            <div class="col-lg-3">

                <h5 class="fw-bold mb-3">

                    Menu

                </h5>

                <p class="mb-2">Coffee</p>

                <p class="mb-2">Non Coffee</p>

                <p class="mb-2">Dessert</p>

                <p class="mb-2">Snack</p>

            </div>

            <div class="col-lg-3">

                <h5 class="fw-bold mb-3">

                    Kontak

                </h5>

                <p class="mb-2">

                    <i class="bi bi-geo-alt-fill me-2"></i>

                    Cianjur

                </p>

                <p class="mb-2">

                    <i class="bi bi-envelope-fill me-2"></i>

                    info@kedaiarea.com

                </p>

                <p>

                    <i class="bi bi-telephone-fill me-2"></i>

                    0812-3456-7890

                </p>

            </div>

        </div>

        <hr class="border-light opacity-25 my-4">

        <div class="text-center">

            <small>

                © {{ date('Y') }} Kedai Area Coffee.
                All Rights Reserved.

            </small>

        </div>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>