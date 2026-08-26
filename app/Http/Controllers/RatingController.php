<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Rating;
use App\Services\CollaborativeFilteringService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function create(Menu $menu)
    {
        return view('pelanggan.rating', compact('menu'));
    }

    public function store(
        Request $request,
        Menu $menu,
        CollaborativeFilteringService $cf
    ) {
        $data = $request->validate([
            'nilai_rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
            'ulasan' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $pelanggan = $request->user()->pelanggan;

        abort_if(!$pelanggan, 403);

        /*
         * Simpan / update rating pelanggan terhadap menu.
         */
        Rating::updateOrCreate(
            [
                'id_pelanggan' =>
                    $pelanggan->id_pelanggan,

                'id_menu' =>
                    $menu->id_menu,
            ],
            $data
        );

        /*
         * Setelah rating berubah,
         * hitung ulang rekomendasi pelanggan.
         *
         * Ini penting karena Item-Based CF
         * menggunakan data rating terbaru.
         */
        $cf->simpanRekomendasiUntukPelanggan(
            $pelanggan->id_pelanggan
        );

        return redirect()
            ->route('pelanggan.rekomendasi')
            ->with(
                'sukses',
                'Rating berhasil disimpan dan rekomendasi telah diperbarui.'
            );
    }
}