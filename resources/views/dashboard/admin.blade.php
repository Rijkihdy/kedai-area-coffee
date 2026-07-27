@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<h3 class="mb-4">Dashboard Admin</h3>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Total Menu</div>
            <div class="fs-3 fw-bold">{{ $totalMenu }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Pesanan Hari Ini</div>
            <div class="fs-3 fw-bold">{{ $totalPesananHariIni }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Menu Stok Menipis (&le;5)</div>
            <div class="fs-3 fw-bold">{{ $menuStokMenipis->count() }}</div>
        </div></div>
    </div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('admin.menu.index') }}" class="btn btn-dark"><i class="bi bi-egg-fried"></i> Kelola Menu</a>
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-dark"><i class="bi bi-tags"></i> Kelola Kategori</a>
</div>

@if($menuStokMenipis->count())
<div class="card shadow-sm">
    <div class="card-header">Menu dengan stok menipis</div>
    <ul class="list-group list-group-flush">
        @foreach($menuStokMenipis as $m)
            <li class="list-group-item d-flex justify-content-between">
                {{ $m->nama_menu }} <span class="badge bg-danger">stok: {{ $m->stok }}</span>
            </li>
        @endforeach
    </ul>
</div>
@endif
@endsection
