<section>

    <div class="mb-4">

        <h4 class="fw-bold text-dark">
            Ubah Password
        </h4>

        <p class="text-muted">
            Gunakan password yang kuat agar akun Anda tetap aman.
        </p>

    </div>

    <form method="POST" action="{{ route('password.update') }}">

        @csrf
        @method('PUT')

        <!-- Password Lama -->
        <div class="mb-3">

            <label for="update_password_current_password" class="form-label fw-semibold">

                Password Saat Ini

            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                autocomplete="current-password">

            @if($errors->updatePassword->has('current_password'))

                <div class="invalid-feedback">

                    {{ $errors->updatePassword->first('current_password') }}

                </div>

            @endif

        </div>

        <!-- Password Baru -->
        <div class="mb-3">

            <label for="update_password_password" class="form-label fw-semibold">

                Password Baru

            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif"
                autocomplete="new-password">

            @if($errors->updatePassword->has('password'))

                <div class="invalid-feedback">

                    {{ $errors->updatePassword->first('password') }}

                </div>

            @endif

        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-4">

            <label for="update_password_password_confirmation" class="form-label fw-semibold">

                Konfirmasi Password Baru

            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control"
                autocomplete="new-password">

        </div>

        <div class="d-flex align-items-center gap-3">

            <button type="submit" class="btn btn-warning px-4">

                <i class="bi bi-key-fill me-2"></i>

                Perbarui Password

            </button>

            @if (session('status') === 'password-updated')

                <span class="text-success fw-semibold">

                    <i class="bi bi-check-circle-fill me-1"></i>

                    Password berhasil diperbarui.

                </span>

            @endif

        </div>

    </form>

</section>