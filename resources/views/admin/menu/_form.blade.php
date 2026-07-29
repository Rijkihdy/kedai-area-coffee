@csrf
<div class="mb-3">
    <label class="form-label">Nama Menu</label>
    <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="id_kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategori as $k)
            <option value="{{ $k->id_kategori }}" @selected(old('id_kategori', $menu->id_kategori ?? null) == $k->id_kategori)>
                {{ $k->nama_kategori }}
            </option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Harga (Rp)</label>
        <input type="number" step="0.01" name="harga" class="form-control" value="{{ old('harga', $menu->harga ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" class="form-control" value="{{ old('stok', $menu->stok ?? 0) }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
</div>
