<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Rekomendasi;
use App\Services\CollaborativeFilteringService;
use Illuminate\Http\Request;

/**
 * Use case: "Melihat rekomendasi menu" (Pelanggan) - Tabel 3.6 no.7
 * Halaman: "Halaman Rekomendasi Menu" - Tabel 3.20 no.6
 */
class RekomendasiController extends Controller
{
    public function index(Request $request, CollaborativeFilteringService $cf)
    {
        $pelanggan = $request->user()->pelanggan;

        if (!$pelanggan) {
            return view('pelanggan.rekomendasi', ['rekomendasi' => collect(), 'populer' => []]);
        }

        $ada = Rekomendasi::where('id_pelanggan', $pelanggan->id_pelanggan)->exists();

        // Cold start: pelanggan belum pernah pesan -> tampilkan menu populer
        if (!$ada) {
            $populer = $cf->rekomendasiPopuler(6);

            return view('pelanggan.rekomendasi', ['rekomendasi' => collect(), 'populer' => $populer]);
        }

        $rekomendasi = Rekomendasi::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('menu.kategori')
            ->orderByDesc('skor_rekomendasi')
            ->get();

        return view('pelanggan.rekomendasi', ['rekomendasi' => $rekomendasi, 'populer' => []]);
    }

    /** Tombol "Hitung ulang rekomendasi" manual (opsional, untuk demo). */
    public function hitungUlang(Request $request, CollaborativeFilteringService $cf)
    {
        $pelanggan = $request->user()->pelanggan;
        abort_if(!$pelanggan, 403);

        $cf->simpanRekomendasiUntukPelanggan($pelanggan->id_pelanggan);

        return redirect()->route('pelanggan.rekomendasi')->with('sukses', 'Rekomendasi berhasil diperbarui.');
    }
}
