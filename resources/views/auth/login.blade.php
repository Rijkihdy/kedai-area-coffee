<x-guest-layout>

    <div class="text-center mb-8">

        <h2 class="text-3xl font-bold text-[#6F4E37]">
            Login
        </h2>

        <p class="text-gray-500 mt-2">
            Masuk ke akun Kedai Area Coffee
        </p>

    </div>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">

        @csrf

        <!-- Email -->
        <div>
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
                autofocus
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
                autocomplete="current-password" />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2" />

        </div>

        <!-- Remember -->
        <div class="flex items-center justify-between mt-5">

            <label class="inline-flex items-center">

                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-[#6F4E37] focus:ring-[#6F4E37]">

                <span class="ml-2 text-sm text-gray-600">
                    Remember me
                </span>

            </label>

            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-[#6F4E37] hover:underline">

                    Lupa Password?

                </a>

            @endif

        </div>

        <!-- Login Button -->
        <button
            type="submit"
            class="w-full mt-7 bg-[#6F4E37] hover:bg-[#533726] text-white py-3 rounded-xl font-semibold transition duration-300">

            Login

        </button>

    </form>

    @if(Route::has('register'))

        <div class="text-center mt-6">

            <p class="text-gray-600">

                Belum punya akun?

                <a
                    href="{{ route('register') }}"
                    class="font-semibold text-[#6F4E37] hover:underline">

                    Daftar Sekarang

                </a>

            </p>

        </div>

    @endif

</x-guest-layout>