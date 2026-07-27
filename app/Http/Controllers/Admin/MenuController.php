<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

/**
 * Use case: "Mengelola data menu" (Admin) - Tabel 3.6 no.3
 * Halaman: "Halaman Kelola Data Menu" - Tabel 3.20 no.8
 */
class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::with('kategori')->orderBy('nama_menu')->paginate(15);

        return view('admin.menu.index', compact('menu'));
    }

    public function create()
    {
        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        return view('admin.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('sukses', 'Menu baru berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        return view('admin.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validasi($request);
        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('sukses', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('sukses', 'Menu berhasil dihapus.');
    }

    protected function validasi(Request $request): array
    {
        return $request->validate([
            'id_kategori' => ['required', 'exists:kategori_menu,id_kategori'],
            'nama_menu' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);
    }
}
