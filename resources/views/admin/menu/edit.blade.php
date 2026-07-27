@extends('layouts.app')
@section('title', 'Ubah Menu')
@section('content')
<h3 class="mb-4">Ubah Menu</h3>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.menu.update', $menu->id_menu) }}">
            @method('PUT')
            @include('admin.menu._form')
            <button class="btn btn-dark">Simpan Perubahan</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
