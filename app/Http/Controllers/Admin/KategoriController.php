<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;

/**
 * Use case: "Mengelola kategori menu" (Admin) - Tabel 3.6 no.4
 */
class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriMenu::withCount('menu')->orderBy('nama_kategori')->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nama_kategori' => ['required', 'string', 'max:100']]);
        KategoriMenu::create($data);

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriMenu $kategori)
    {
        $data = $request->validate(['nama_kategori' => ['required', 'string', 'max:100']]);
        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriMenu $kategori)
    {
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil dihapus.');
    }
}
