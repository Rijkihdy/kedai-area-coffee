<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

/**
 * Use case: "Melihat daftar menu" (Pelanggan) - Tabel 3.6 no.5
 * Halaman: "Halaman Daftar Menu" - Tabel 3.20 no.4
 */
class MenuPublikController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('kategori')->where('stok', '>', 0);

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->input('kategori'));
        }

        if ($request->filled('cari')) {
            $query->where('nama_menu', 'like', '%' . $request->input('cari') . '%');
        }

        $menu = $query->orderBy('nama_menu')->paginate(12)->withQueryString();
        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        return view('pelanggan.menu', compact('menu', 'kategori'));
    }
}
