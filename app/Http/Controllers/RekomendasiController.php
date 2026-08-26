<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Services\CollaborativeFilteringService;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    /**
     * Halaman rekomendasi menu pelanggan.
     */
    public function index(
        Request $request,
        CollaborativeFilteringService $cf
    ) {
        $pelanggan = $request->user()->pelanggan;

        /*
         * Kalau user bukan pelanggan.
         */
        if (!$pelanggan) {
            return view('pelanggan.rekomendasi', [
                'rekomendasi' => collect(),
                'populer' => [],
            ]);
        }

        $pelangganId = $pelanggan->id_pelanggan;

        /*
         * Cek apakah pelanggan sudah memberikan rating.
         *
         * Karena sekarang rekomendasi dibuat berdasarkan
         * data rating, bukan hanya berdasarkan transaksi.
         */
        $jumlahRating = \DB::table('rating')
            ->where('id_pelanggan', $pelangganId)
            ->count();

        /*
         * =====================================================
         * COLD START
         * =====================================================
         *
         * Kalau pelanggan belum mempunyai rating,
         * belum bisa dilakukan prediksi rating personal.
         *
         * Maka tampilkan menu populer.
         */
        if ($jumlahRating === 0) {

            $populer = $cf->rekomendasiPopuler(6);

            return view('pelanggan.rekomendasi', [
                'rekomendasi' => collect(),
                'populer' => $populer,
            ]);
        }

        /*
         * =====================================================
         * GENERATE REKOMENDASI
         * =====================================================
         *
         * Kita cek apakah pelanggan sudah mempunyai
         * hasil rekomendasi.
         */
        $adaRekomendasi = Rekomendasi::where(
            'id_pelanggan',
            $pelangganId
        )->exists();

        /*
         * Kalau belum ada, generate sekarang.
         */
        if (!$adaRekomendasi) {

            $cf->simpanRekomendasiUntukPelanggan(
                $pelangganId
            );
        }

        /*
         * Ambil hasil rekomendasi.
         */
        $rekomendasi = Rekomendasi::where(
            'id_pelanggan',
            $pelangganId
        )
            ->with('menu.kategori')
            ->orderByDesc('skor_rekomendasi')
            ->get();

        return view('pelanggan.rekomendasi', [
            'rekomendasi' => $rekomendasi,
            'populer' => [],
        ]);
    }

    /**
     * Tombol "Hitung Ulang Rekomendasi".
     */
    public function hitungUlang(
        Request $request,
        CollaborativeFilteringService $cf
    ) {
        $pelanggan = $request->user()->pelanggan;

        abort_if(!$pelanggan, 403);

        /*
         * Hitung ulang berdasarkan data rating terbaru.
         */
        $cf->simpanRekomendasiUntukPelanggan(
            $pelanggan->id_pelanggan
        );

        return redirect()
            ->route('pelanggan.rekomendasi')
            ->with(
                'sukses',
                'Rekomendasi berhasil diperbarui.'
            );
    }
}