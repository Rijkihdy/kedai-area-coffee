@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-10">

        <div class="mb-4">

            <h2 class="fw-bold text-dark">
                <i class="bi bi-person-circle text-secondary"></i>
                Profil Saya
            </h2>

            <p class="text-muted mb-0">
                Kelola informasi akun, ubah password, atau hapus akun Anda.
            </p>

        </div>

        <!-- Informasi Profil -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white rounded-top-4">

                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2 text-primary"></i>
                    Informasi Profil
                </h5>

            </div>

            <div class="card-body">

                @include('profile.partials.update-profile-information-form')

            </div>

        </div>

        <!-- Password -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white rounded-top-4">

                <h5 class="mb-0">
                    <i class="bi bi-shield-lock me-2 text-warning"></i>
                    Ubah Password
                </h5>

            </div>

            <div class="card-body">

                @include('profile.partials.update-password-form')

            </div>

        </div>

        <!-- Hapus Akun -->
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white rounded-top-4">

                <h5 class="mb-0 text-danger">

                    <i class="bi bi-trash me-2"></i>

                    Hapus Akun

                </h5>

            </div>

            <div class="card-body">

                @include('profile.partials.delete-user-form')

            </div>

        </div>

    </div>

</div>

@endsection