@extends('layouts.app')
@section('title', 'Beri Rating')
@section('content')
<h3 class="mb-4">Beri Rating: {{ $menu->nama_menu }}</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('pelanggan.rating.store', $menu->id_menu) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nilai Rating</label>
                <select name="nilai_rating" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @for($i=5;$i>=1;$i--)
                        <option value="{{ $i }}">{{ $i }} - {{ str_repeat('⭐', $i) }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ulasan (opsional)</label>
                <textarea name="ulasan" class="form-control" rows="3" placeholder="Bagaimana rasa/kualitas menu ini?"></textarea>
            </div>
            <button class="btn btn-dark">Kirim Rating</button>
            <a href="{{ route('pelanggan.menu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
