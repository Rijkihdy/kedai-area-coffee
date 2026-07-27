<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Rating;
use Illuminate\Http\Request;

/**
 * Use case: "Memberikan rating menu" (Pelanggan) - Tabel 3.6 no.8
 * Halaman: "Halaman Rating Menu" - Tabel 3.20 no.7
 */
class RatingController extends Controller
{
    public function create(Menu $menu)
    {
        return view('pelanggan.rating', compact('menu'));
    }

    public function store(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'nilai_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan' => ['nullable', 'string', 'max:1000'],
        ]);

        $pelanggan = $request->user()->pelanggan;
        abort_if(!$pelanggan, 403);

        Rating::updateOrCreate(
            ['id_pelanggan' => $pelanggan->id_pelanggan, 'id_menu' => $menu->id_menu],
            $data
        );

        return redirect()->route('pelanggan.menu.index')->with('sukses', 'Terima kasih atas rating dan ulasannya.');
    }
}
