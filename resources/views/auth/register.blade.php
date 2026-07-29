<x-guest-layout>

    <div class="text-center mb-8">

        <h2 class="text-3xl font-bold text-[#6F4E37]">
            Buat Akun
        </h2>

        <p class="text-gray-500 mt-2">
            Daftar sebagai pelanggan Kedai Area Coffee
        </p>

    </div>

    <form method="POST" action="{{ route('register') }}">

        @csrf

        <!-- Nama -->
        <div>

            <x-input-label
                for="name"
                :value="__('Nama Lengkap')" />

            <x-text-input
                id="name"
                class="block mt-2 w-full rounded-xl"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name" />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2" />

        </div>

        <!-- Email -->
        <div class="mt-5">

            <x-input-label
                for="email"
                :value="__('Email')" />

            <x-text-input
                id="email"
                class="block mt-2 w-full rounded-xl"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username" />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2" />

        </div>

        <!-- Password -->
        <div class="mt-5">

            <x-input-label
                for="password"
                :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-2 w-full rounded-xl"
                type="password"
                name="password"
                required
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2" />

        </div>

        <!-- Konfirmasi Password -->
        <div class="mt-5">

            <x-input-label
                for="password_confirmation"
                :value="__('Konfirmasi Password')" />

            <x-text-input
                id="password_confirmation"
                class="block mt-2 w-full rounded-xl"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2" />

        </div>

        <!-- Tombol Register -->
        <button
            type="submit"
            class="w-full mt-8 bg-[#6F4E37] hover:bg-[#533726] text-white py-3 rounded-xl font-semibold transition duration-300">

            Daftar Sekarang

        </button>

        <!-- Login -->
        <div class="text-center mt-6">

            <p class="text-gray-600">

                Sudah punya akun?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-[#6F4E37] hover:underline">

                    Login

                </a>

            </p>

        </div>

    </form>

</x-guest-layout>