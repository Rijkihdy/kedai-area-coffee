<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Rekomendasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CollaborativeFilteringService
{
    protected int $k = 5;
    protected int $topN = 5;

    public function __construct(?int $k = null, ?int $topN = null)
    {
        if ($k !== null) {
            $this->k = $k;
        }

        if ($topN !== null) {
            $this->topN = $topN;
        }
    }

    /**
     * =========================================================
     * 1. MEMBUAT MATRIKS RATING
     * =========================================================
     *
     * Bentuk:
     *
     * [
     *   id_pelanggan => [
     *       id_menu => nilai_rating
     *   ]
     * ]
     *
     * Contoh:
     *
     * [
     *   2 => [
     *       1 => 4,
     *       15 => 4
     *   ],
     *   3 => [
     *       6 => 5,
     *       7 => 5
     *   ]
     * ]
     */
    public function buatMatriksRating(): array
    {
        $rows = DB::table('rating')
            ->select(
                'id_pelanggan',
                'id_menu',
                'nilai_rating'
            )
            ->orderBy('id_pelanggan')
            ->get();

        $matriks = [];

        foreach ($rows as $row) {
            $pelangganId = (int) $row->id_pelanggan;
            $menuId = (int) $row->id_menu;
            $rating = (float) $row->nilai_rating;

            $matriks[$pelangganId][$menuId] = $rating;
        }

        return $matriks;
    }

    /**
     * =========================================================
     * 2. TRANSPOSE:
     *    pelanggan x menu
     *    menjadi
     *    menu x pelanggan
     * =========================================================
     */
    protected function buatVektorMenu(array $matriksRating): array
    {
        $vektorMenu = [];

        foreach ($matriksRating as $pelangganId => $menuRatings) {

            foreach ($menuRatings as $menuId => $rating) {

                $vektorMenu[$menuId][$pelangganId] = $rating;
            }
        }

        return $vektorMenu;
    }

    /**
     * =========================================================
     * 3. COSINE SIMILARITY ANTAR MENU
     * =========================================================
     */
    public function hitungSimilarityAntarMenu(
        array $matriksRating
    ): array {

        $vektorMenu = $this->buatVektorMenu($matriksRating);

        $menuIds = array_keys($vektorMenu);

        $similarity = [];

        foreach ($menuIds as $i) {

            foreach ($menuIds as $j) {

                if ($i === $j) {
                    continue;
                }

                if (isset($similarity[$i][$j])) {
                    continue;
                }

                $sim = $this->cosineSimilarity(
                    $vektorMenu[$i],
                    $vektorMenu[$j]
                );

                $similarity[$i][$j] = $sim;
                $similarity[$j][$i] = $sim;
            }
        }

        return $similarity;
    }

    /**
     * =========================================================
     * 4. COSINE SIMILARITY
     * =========================================================
     */
    protected function cosineSimilarity(
        array $vektorA,
        array $vektorB
    ): float {

        $pelangganGabungan = array_unique(
            array_merge(
                array_keys($vektorA),
                array_keys($vektorB)
            )
        );

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($pelangganGabungan as $pelangganId) {

            $a = $vektorA[$pelangganId] ?? 0.0;
            $b = $vektorB[$pelangganId] ?? 0.0;

            $dotProduct += $a * $b;

            $normA += $a * $a;
            $normB += $b * $b;
        }

        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        return $dotProduct /
            (sqrt($normA) * sqrt($normB));
    }

    /**
     * =========================================================
     * 5. K-NEAREST NEIGHBOR
     * =========================================================
     */
    public function nearestNeighbor(
        array $similarityMatrix,
        int $menuTargetId
    ): array {

        $neighbors = $similarityMatrix[$menuTargetId] ?? [];

        arsort($neighbors);

        return array_slice(
            $neighbors,
            0,
            $this->k,
            true
        );
    }

    /**
     * =========================================================
     * 6. PREDIKSI RATING
     *
     * Pred(u,i) =
     *
     * SUM(sim(i,j) * R(u,j))
     * -----------------------
     * SUM(abs(sim(i,j)))
     *
     * Sekarang R(u,j) benar-benar NILAI RATING.
     * =========================================================
     */
    public function prediksiRating(
        array $similarityMatrix,
        array $riwayatRating,
        int $menuTargetId
    ): float {

        $neighbors = $this->nearestNeighbor(
            $similarityMatrix,
            $menuTargetId
        );

        $pembilang = 0.0;
        $penyebut = 0.0;

        foreach ($neighbors as $menuJ => $similarity) {

            /*
             * Hanya gunakan menu tetangga
             * yang sudah diberi rating oleh pelanggan.
             */
            if (!array_key_exists($menuJ, $riwayatRating)) {
                continue;
            }

            $ratingUser = (float) $riwayatRating[$menuJ];

            $pembilang +=
                $similarity * $ratingUser;

            $penyebut += abs($similarity);
        }

        if ($penyebut <= 0) {
            return 0.0;
        }

        $prediksi = $pembilang / $penyebut;

        /*
         * Rating menggunakan skala 1-5.
         * Jadi hasil prediksi dibatasi 1-5.
         */
        return max(
            1.0,
            min(5.0, $prediksi)
        );
    }

    /**
     * =========================================================
     * 7. REKOMENDASI UNTUK SATU PELANGGAN
     * =========================================================
     */
    public function rekomendasikanUntukPelanggan(
        int $pelangganId
    ): array {

        /*
         * Matriks sekarang berasal dari TABEL RATING.
         */
        $matriksRating =
            $this->buatMatriksRating();

        /*
         * Hitung similarity antar menu.
         */
        $similarity =
            $this->hitungSimilarityAntarMenu(
                $matriksRating
            );

        /*
         * Ambil rating pelanggan.
         */
        $riwayatRating =
            $matriksRating[$pelangganId] ?? [];

        /*
         * Semua menu.
         */
        $semuaMenuId =
            Menu::query()
                ->pluck('id_menu')
                ->map(fn ($id) => (int) $id)
                ->all();

        $hasilPrediksi = [];

        foreach ($semuaMenuId as $menuId) {

            /*
             * Jangan rekomendasikan menu
             * yang sudah pernah diberi rating.
             */
            if (array_key_exists(
                $menuId,
                $riwayatRating
            )) {
                continue;
            }

            $prediksi =
                $this->prediksiRating(
                    $similarity,
                    $riwayatRating,
                    $menuId
                );

            /*
             * Hanya masukkan prediksi valid.
             */
            if ($prediksi > 0) {

                $hasilPrediksi[$menuId] =
                    $prediksi;
            }
        }

        /*
         * Rating terbesar = rekomendasi terbaik.
         */
        arsort($hasilPrediksi);

        /*
         * Ambil Top-N.
         */
        $topN = array_slice(
            $hasilPrediksi,
            0,
            $this->topN,
            true
        );

        $hasil = [];

        foreach ($topN as $menuId => $prediksi) {

            $hasil[] = [
                'id_menu' => (int) $menuId,
                'prediksi_rating' =>
                    round($prediksi, 2),
            ];
        }

        return $hasil;
    }

    /**
     * =========================================================
     * 8. SIMPAN REKOMENDASI
     * =========================================================
     */
    public function simpanRekomendasiUntukPelanggan(
        int $pelangganId
    ): void {

        $hasil =
            $this->rekomendasikanUntukPelanggan(
                $pelangganId
            );

        /*
         * Hapus rekomendasi lama.
         */
        Rekomendasi::where(
            'id_pelanggan',
            $pelangganId
        )->delete();

        /*
         * Simpan rekomendasi baru.
         */
        foreach ($hasil as $item) {

            Rekomendasi::create([
                'id_pelanggan' =>
                    $pelangganId,

                'id_menu' =>
                    $item['id_menu'],

                'skor_rekomendasi' =>
                    $item['prediksi_rating'],

                'tanggal_dibuat' =>
                    Carbon::now(),
            ]);
        }
    }

    /**
     * =========================================================
     * 9. GENERATE UNTUK SEMUA PELANGGAN
     * =========================================================
     */
    public function jalankanUntukSemuaPelanggan(): void
    {
        $pelangganIds =
            Pelanggan::query()
                ->pluck('id_pelanggan')
                ->all();

        foreach ($pelangganIds as $pelangganId) {

            $this->simpanRekomendasiUntukPelanggan(
                (int) $pelangganId
            );
        }
    }

    /**
     * =========================================================
     * 10. FALLBACK PELANGGAN BARU
     * =========================================================
     *
     * Pelanggan belum punya rating.
     * Untuk sementara gunakan menu paling populer
     * berdasarkan jumlah penjualan.
     */
    public function rekomendasiPopuler(
        int $limit = 5
    ): array {

        $terjual = DB::table('detail_pesanan')
            ->select(
                'id_menu',
                DB::raw(
                    'SUM(jumlah) AS total_terjual'
                )
            )
            ->groupBy('id_menu')
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->pluck(
                'total_terjual',
                'id_menu'
            );

        if ($terjual->isEmpty()) {

            return Menu::query()
                ->limit($limit)
                ->get()
                ->toArray();
        }

        $menus =
            Menu::query()
                ->whereIn(
                    'id_menu',
                    $terjual->keys()
                )
                ->get()
                ->keyBy('id_menu');

        $hasil = [];

        foreach ($terjual as $menuId => $total) {

            if (!$menus->has($menuId)) {
                continue;
            }

            $menu =
                $menus
                    ->get($menuId)
                    ->toArray();

            $menu['total_terjual'] =
                (int) $total;

            $hasil[] = $menu;
        }

        return $hasil;
    }

    /**
     * =========================================================
     * 11. MENU YANG SERING DIBELI BERSAMA
     *
     * Ini menggunakan ID PESANAN,
     * bukan rating.
     * =========================================================
     */
    public function rekomendasiMenuPendamping(
        int $menuId
    ): array {

        /*
         * Cari semua transaksi yang mengandung
         * menu target.
         */
        $pesananIds = DB::table('detail_pesanan')
            ->where('id_menu', $menuId)
            ->pluck('id_pesanan');

        if ($pesananIds->isEmpty()) {
            return [];
        }

        /*
         * Cari menu lain yang berada
         * pada transaksi yang sama.
         */
        $menuBersama = DB::table('detail_pesanan')
            ->select(
                'id_menu',
                DB::raw(
                    'COUNT(DISTINCT id_pesanan) AS frekuensi'
                )
            )
            ->whereIn(
                'id_pesanan',
                $pesananIds
            )
            ->where(
                'id_menu',
                '!=',
                $menuId
            )
            ->groupBy('id_menu')
            ->orderByDesc('frekuensi')
            ->limit($this->topN)
            ->get();

        if ($menuBersama->isEmpty()) {
            return [];
        }

        $menuIds =
            $menuBersama
                ->pluck('id_menu')
                ->all();

        $menus =
            Menu::whereIn(
                'id_menu',
                $menuIds
            )
            ->get()
            ->keyBy('id_menu');

        $hasil = [];

        foreach ($menuBersama as $item) {

            if (!$menus->has($item->id_menu)) {
                continue;
            }

            $hasil[] = [
                'menu' =>
                    $menus[$item->id_menu],

                'frekuensi' =>
                    (int) $item->frekuensi,
            ];
        }

        return $hasil;
    }
}