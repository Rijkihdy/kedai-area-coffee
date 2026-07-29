<?php

namespace App\Services;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Rekomendasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Item-Based Collaborative Filtering.
 *
 * Mengikuti BAB III laporan TA:
 * - 3.4.2 : bentuk matriks pelanggan x menu dari riwayat pemesanan (jumlah menu yang dipesan).
 * - 3.5   : hitung similarity antar menu (Cosine Similarity) lalu ambil K nearest neighbor.
 * - 3.6.2 : Pred(u,i) = Σ(sim(i,j) x R(u,j)) / Σ|sim(i,j)|
 * - 3.7   : ranking hasil prediksi lalu ambil Top-N sebagai rekomendasi.
 */
class CollaborativeFilteringService
{
    /** Jumlah tetangga terdekat (nearest neighbor) yang dipakai per menu target. */
    protected int $k = 5;

    /** Jumlah rekomendasi (Top-N) yang disimpan/ditampilkan per pelanggan. */
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
     * Bentuk matriks interaksi pelanggan x menu.
     * Nilai matriks = total jumlah menu tersebut dipesan oleh pelanggan (dari detail_pesanan).
     *
     * @return array<int, array<int, float>> [id_pelanggan => [id_menu => nilai]]
     */
    public function buatMatriksInteraksi(): array
    {
        $rows = DetailPesanan::query()
            ->join('pesanan', 'pesanan.id_pesanan', '=', 'detail_pesanan.id_pesanan')
            ->select('pesanan.id_pelanggan', 'detail_pesanan.id_menu', 'detail_pesanan.jumlah')
            ->get();

        $matriks = [];

        foreach ($rows as $row) {
            $pelangganId = (int) $row->id_pelanggan;
            $menuId = (int) $row->id_menu;
            $matriks[$pelangganId][$menuId] = ($matriks[$pelangganId][$menuId] ?? 0) + (float) $row->jumlah;
        }

        return $matriks;
    }

    /**
     * Hitung cosine similarity antar semua pasangan menu berdasarkan matriks interaksi.
     *
     * sim(i,j) = (vektor_i . vektor_j) / (||vektor_i|| x ||vektor_j||)
     * vektor menu i dibentuk dari kolom nilai interaksi seluruh pelanggan terhadap menu i.
     *
     * @return array<int, array<int, float>> [id_menu_i => [id_menu_j => similarity]]
     */
    public function hitungSimilarityAntarMenu(array $matriksInteraksi): array
    {
        // Balik matriks pelanggan x menu -> menu x pelanggan (vektor per menu)
        $vektorMenu = [];
        foreach ($matriksInteraksi as $pelangganId => $menuValues) {
            foreach ($menuValues as $menuId => $nilai) {
                $vektorMenu[$menuId][$pelangganId] = $nilai;
            }
        }

        $menuIds = array_keys($vektorMenu);
        $similarity = [];

        foreach ($menuIds as $i) {
            foreach ($menuIds as $j) {
                if ($i === $j) {
                    continue;
                }
                if (isset($similarity[$i][$j])) {
                    continue; // simetris, sudah dihitung
                }

                $sim = $this->cosineSimilarity($vektorMenu[$i], $vektorMenu[$j]);
                $similarity[$i][$j] = $sim;
                $similarity[$j][$i] = $sim;
            }
        }

        return $similarity;
    }

    protected function cosineSimilarity(array $vektorA, array $vektorB): float
    {
        $pelangganGabungan = array_unique(array_merge(array_keys($vektorA), array_keys($vektorB)));

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($pelangganGabungan as $pid) {
            $a = $vektorA[$pid] ?? 0.0;
            $b = $vektorB[$pid] ?? 0.0;
            $dotProduct += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }

        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Ambil K nearest neighbor (menu paling mirip) untuk sebuah menu target.
     *
     * @return array<int, float> [id_menu_tetangga => similarity] terurut descending, dibatasi K
     */
    public function nearestNeighbor(array $similarityMatrix, int $menuTargetId): array
    {
        $tetangga = $similarityMatrix[$menuTargetId] ?? [];
        arsort($tetangga);

        return array_slice($tetangga, 0, $this->k, true);
    }

    /**
     * Prediksi skor ketertarikan pelanggan terhadap satu menu target.
     * Pred(u,i) = Σ(sim(i,j) x R(u,j)) / Σ|sim(i,j)|
     * j hanya diambil dari menu yang pernah dipesan pelanggan DAN termasuk
     * nearest neighbor dari menu target.
     */
    public function prediksiSkor(array $similarityMatrix, array $riwayatPelanggan, int $menuTargetId): float
    {
        $tetangga = $this->nearestNeighbor($similarityMatrix, $menuTargetId);

        $pembilang = 0.0;
        $penyebut = 0.0;

        foreach ($tetangga as $menuJ => $sim) {
            if (!isset($riwayatPelanggan[$menuJ])) {
                continue; // pelanggan belum pernah memesan menu tetangga ini
            }

            $rUj = $riwayatPelanggan[$menuJ];
            $pembilang += $sim * $rUj;
            $penyebut += abs($sim);
        }

        if ($penyebut <= 0) {
            return 0.0;
        }

        return $pembilang / $penyebut;
    }

    /**
     * Hasilkan Top-N rekomendasi untuk satu pelanggan.
     *
     * @return array<int, array{id_menu:int, skor:float}>
     */
    public function rekomendasikanUntukPelanggan(int $pelangganId): array
    {
        $matriks = $this->buatMatriksInteraksi();
        $similarity = $this->hitungSimilarityAntarMenu($matriks);
        $riwayatPelanggan = $matriks[$pelangganId] ?? [];

        $semuaMenuId = Menu::query()->pluck('id_menu')->all();
        $skorPrediksi = [];

        foreach ($semuaMenuId as $menuId) {
            if (isset($riwayatPelanggan[$menuId])) {
                continue; // hanya rekomendasikan menu yang belum pernah dipesan
            }

            $skor = $this->prediksiSkor($similarity, $riwayatPelanggan, $menuId);

            if ($skor > 0) {
                $skorPrediksi[$menuId] = $skor;
            }
        }

        arsort($skorPrediksi);
        $topN = array_slice($skorPrediksi, 0, $this->topN, true);

        $hasil = [];
        foreach ($topN as $menuId => $skor) {
            $hasil[] = ['id_menu' => $menuId, 'skor' => round($skor, 4)];
        }

        return $hasil;
    }

    /**
     * Hitung ulang & simpan rekomendasi untuk SATU pelanggan ke tabel `rekomendasi`.
     * Dipanggil setelah pelanggan menyelesaikan pesanan / memberi rating baru.
     */
    public function simpanRekomendasiUntukPelanggan(int $pelangganId): void
    {
        $hasil = $this->rekomendasikanUntukPelanggan($pelangganId);

        Rekomendasi::where('id_pelanggan', $pelangganId)->delete();

        foreach ($hasil as $item) {
            Rekomendasi::create([
                'id_pelanggan' => $pelangganId,
                'id_menu' => $item['id_menu'],
                'skor_rekomendasi' => $item['skor'],
                'tanggal_dibuat' => Carbon::now(),
            ]);
        }
    }

    /**
     * Hitung ulang & simpan rekomendasi untuk SEMUA pelanggan.
     * Cocok dijalankan sebagai scheduled job / artisan command harian.
     */
    public function jalankanUntukSemuaPelanggan(): void
    {
        $pelangganIds = Pelanggan::query()->pluck('id_pelanggan')->all();

        foreach ($pelangganIds as $pelangganId) {
            $this->simpanRekomendasiUntukPelanggan($pelangganId);
        }
    }

    /**
     * Fallback untuk pelanggan baru yang belum punya riwayat pemesanan (cold start):
     * rekomendasikan menu paling populer (paling banyak dipesan / rating tertinggi).
     */
    public function rekomendasiPopuler(int $limit = 5): array
    {
        $terjual = DetailPesanan::query()
            ->select('id_menu', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('id_menu')
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->pluck('total_terjual', 'id_menu');

        if ($terjual->isEmpty()) {
            return Menu::query()->limit($limit)->get()->toArray();
        }

        $menus = Menu::query()->whereIn('id_menu', $terjual->keys())->get()->keyBy('id_menu');

        $hasil = [];
        foreach ($terjual as $menuId => $total) {
            if ($menus->has($menuId)) {
                $menu = $menus->get($menuId)->toArray();
                $menu['total_terjual'] = (int) $total;
                $hasil[] = $menu;
            }
        }

        return $hasil;
    }

    /**
 * Mengambil menu yang paling mirip dengan menu tertentu.
 * Digunakan untuk fitur "Sering Dibeli Bersama".
 */
public function rekomendasiMenuPendamping(int $menuId): array
{
    // Bangun matriks interaksi
    $matriks = $this->buatMatriksInteraksi();

    // Hitung similarity antar menu
    $similarity = $this->hitungSimilarityAntarMenu($matriks);

    // Ambil K menu yang paling mirip
    $neighbors = $this->nearestNeighbor($similarity, $menuId);

    if (empty($neighbors)) {
        return [];
    }

    $menus = Menu::whereIn('id_menu', array_keys($neighbors))
        ->get()
        ->keyBy('id_menu');

    $hasil = [];

    foreach ($neighbors as $id => $score) {

        if (!$menus->has($id)) {
            continue;
        }

        $hasil[] = [
            'menu' => $menus[$id],
            'similarity' => round($score, 4),
        ];
    }

    return $hasil;
}
}
