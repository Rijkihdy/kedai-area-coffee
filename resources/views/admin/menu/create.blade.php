@extends('layouts.app')
@section('title', 'Tambah Menu')
@section('content')
<h3 class="mb-4">Tambah Menu</h3>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.menu.store') }}">
            @include('admin.menu._form')
            <button class="btn btn-dark">Simpan</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
