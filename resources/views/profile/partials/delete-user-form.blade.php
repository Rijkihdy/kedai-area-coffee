<section>

    <div class="mb-4">

        <!-- <h4 class="fw-bold text-danger">
            Hapus Akun
        </h4> -->

        <p class="text-muted">
            Menghapus akun akan menghilangkan seluruh data akun secara permanen. Tindakan ini tidak dapat dibatalkan.
        </p>

    </div>

    <div class="alert alert-danger">

        <h6 class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Peringatan
        </h6>

        <p class="mb-0">
            Sebelum menghapus akun, pastikan Anda sudah menyimpan data penting yang masih diperlukan.
        </p>

    </div>

    <button
        type="button"
        class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#deleteAccountModal">

        <i class="bi bi-trash me-2"></i>

        Hapus Akun

    </button>

</section>

<!-- Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow">

            <form method="POST" action="{{ route('profile.destroy') }}">

                @csrf
                @method('DELETE')

                <div class="modal-header border-0">

                    <h5 class="modal-title text-danger fw-bold">

                        <i class="bi bi-trash me-2"></i>

                        Konfirmasi Hapus Akun

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <p class="text-muted">

                        Apakah Anda yakin ingin menghapus akun ini?

                    </p>

                    <p class="text-muted">

                        Semua data akun akan dihapus secara permanen dan tidak dapat dipulihkan kembali.

                    </p>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Masukkan Password

                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif"
                            placeholder="Masukkan password">

                        @if($errors->userDeletion->has('password'))

                            <div class="invalid-feedback">

                                {{ $errors->userDeletion->first('password') }}

                            </div>

                        @endif

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        <i class="bi bi-trash me-2"></i>

                        Ya, Hapus Akun

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>