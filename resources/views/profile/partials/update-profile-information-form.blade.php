<section>

    <div class="mb-4">

        <h4 class="fw-bold text-dark">
            Informasi Profil
        </h4>

        <p class="text-muted">
            Perbarui nama dan alamat email akun Anda.
        </p>

    </div>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}">

        @csrf
        @method('PATCH')

        <!-- Nama -->
        <div class="mb-3">

            <label for="name" class="form-label fw-semibold">

                Nama Lengkap

            </label>

            <input
                id="name"
                name="name"
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name ?? $user->nama) }}"
                required
                autofocus>

            @error('name')

                <div class="invalid-feedback">

                    {{ $message }}

                </div>

            @enderror

        </div>

        <!-- Email -->
        <div class="mb-3">

            <label for="email" class="form-label fw-semibold">

                Email

            </label>

            <input
                id="email"
                name="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required>

            @error('email')

                <div class="invalid-feedback">

                    {{ $message }}

                </div>

            @enderror

        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

            <div class="alert alert-warning">

                <strong>Email belum terverifikasi.</strong>

                <br>

                Klik tombol berikut untuk mengirim ulang email verifikasi.

                <div class="mt-3">

                    <button
                        form="send-verification"
                        class="btn btn-outline-warning btn-sm">

                        Kirim Ulang Verifikasi

                    </button>

                </div>

            </div>

            @if (session('status') === 'verification-link-sent')

                <div class="alert alert-success">

                    Link verifikasi berhasil dikirim ke email Anda.

                </div>

            @endif

        @endif

        <div class="d-flex align-items-center gap-3 mt-4">

            <button class="btn btn-primary px-4">

                <i class="bi bi-check-circle me-2"></i>

                Simpan Perubahan

            </button>

            @if (session('status') === 'profile-updated')

                <span class="text-success fw-semibold">

                    <i class="bi bi-check-circle-fill"></i>

                    Profil berhasil diperbarui.

                </span>

            @endif

        </div>

    </form>

</section>