<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kedai Area Coffee</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Poppins]">

<div class="min-h-screen flex">

    <!-- Left -->
    <div class="hidden lg:flex lg:w-1/2 relative">

        <img
            src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1600&q=80"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 flex flex-col justify-center px-16 text-white">

            <h1 class="text-5xl font-bold mb-6">
                ☕ Kedai Area Coffee
            </h1>

            <p class="text-xl leading-relaxed">
                Nikmati kopi terbaik dengan suasana nyaman,
                pelayanan cepat, dan sistem pemesanan modern.
            </p>

        </div>

    </div>

    <!-- Right -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#F8F5F2] p-8">

        <div class="w-full max-w-md">

            <!-- Logo -->
            <div class="text-center mb-8">

                <a href="/" class="inline-block">

                    <div class="w-24 h-24 rounded-full bg-[#6F4E37] flex items-center justify-center text-white text-4xl shadow-lg mx-auto">
                        ☕
                    </div>

                </a>

                <h2 class="mt-5 text-3xl font-bold text-[#6F4E37]">
                    Kedai Area Coffee
                </h2>

                <p class="text-gray-500 mt-2">
                    Selamat datang kembali.
                </p>

            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">

                {{ $slot }}

            </div>

        </div>

    </div>

</div>

</body>
</html>