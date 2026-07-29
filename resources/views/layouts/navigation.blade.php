<nav x-data="{ open: false }" class="bg-[#6F4E37] shadow-md border-b border-[#5A3E2B]">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- Logo -->
            <div class="flex items-center">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold text-xl">
                    ☕ Kedai Area Coffee
                </a>

                <!-- Menu Desktop -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-6">

                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="!text-white hover:!text-yellow-200">
                        Dashboard
                    </x-nav-link>

                </div>

            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center">

                <x-dropdown align="right" width="56">

                    <x-slot name="trigger">

                        <button
                            class="inline-flex items-center gap-3 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">

                            <div class="w-10 h-10 rounded-full bg-white text-[#6F4E37] flex items-center justify-center">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div class="text-left">

                                <div class="font-semibold">
                                    {{ Auth::user()->nama ?? Auth::user()->name }}
                                </div>

                                @if(isset(Auth::user()->role))
                                    <div class="text-xs text-gray-200">
                                        {{ Auth::user()->role }}
                                    </div>
                                @endif

                            </div>

                            <svg class="fill-current h-4 w-4 ms-1"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">

                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10
                                    10.586l3.293-3.293a1 1 0
                                    111.414 1.414l-4 4a1 1
                                    0 01-1.414 0l-4-4a1 1
                                    0 010-1.414z"
                                    clip-rule="evenodd"/>

                            </svg>

                        </button>

                    </x-slot>

                    <x-slot name="content">

                        <!-- Profile -->
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="bi bi-person-circle me-2"></i>
                            Profil Saya
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault();
                                         this.closest('form').submit();">

                                <i class="bi bi-box-arrow-right me-2"></i>
                                Keluar

                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">

                <button
                    @click="open = !open"
                    class="text-white">

                    <svg class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>

                        <path
                            :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

        </div>

    </div>

    <!-- Mobile Menu -->
    <div
        :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden bg-[#5A3E2B]">

        <div class="pt-3 pb-2 space-y-1">

            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')">

                Dashboard

            </x-responsive-nav-link>

        </div>

        <div class="border-t border-white/20 pt-4 pb-3">

            <div class="px-4">

                <div class="text-white font-semibold">

                    {{ Auth::user()->nama ?? Auth::user()->name }}

                </div>

                <div class="text-sm text-gray-200">

                    {{ Auth::user()->email }}

                </div>

            </div>

            <div class="mt-3 space-y-1">

                <x-responsive-nav-link
                    :href="route('profile.edit')"
                    :active="request()->routeIs('profile.edit')">

                    Profil Saya

                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault();
                                 this.closest('form').submit();">

                        Keluar

                    </x-responsive-nav-link>

                </form>

            </div>

        </div>

    </div>

</nav>