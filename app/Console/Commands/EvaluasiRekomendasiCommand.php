<?php

namespace App\Console\Commands;

use App\Models\Rating;
use App\Services\CollaborativeFilteringService;
use Illuminate\Console\Command;

class EvaluasiRekomendasiCommand extends Command
{
    protected $signature = 'rekomendasi:evaluasi {--top=5} {--threshold=4}';

    protected $description = 'Evaluasi rekomendasi collaborative filtering dan tampilkan metrik di terminal';

    public function handle(CollaborativeFilteringService $cf): int
    {
        $topN = (int) $this->option('top');
        $threshold = (float) $this->option('threshold');

        $ratings = Rating::query()
            ->select('id_pelanggan', 'id_menu', 'nilai_rating')
            ->orderBy('id_pelanggan')
            ->get();

        if ($ratings->isEmpty()) {
            $this->warn('Belum ada data rating untuk dievaluasi.');

            return self::SUCCESS;
        }

        $nilaiAktual = [];
        $nilaiPrediksi = [];

        foreach ($ratings as $rating) {
            $nilaiAktual[$rating->id_menu] = (float) $rating->nilai_rating;

            $riwayatRating = $cf->buatMatriksRating();
            $similarity = $cf->hitungSimilarityAntarMenu($riwayatRating);
            $nilaiPrediksi[$rating->id_menu] = $cf->prediksiRating(
                $similarity,
                $riwayatRating[$rating->id_pelanggan] ?? [],
                (int) $rating->id_menu
            );
        }

        $cf->tampilkanMetrikEvaluasi(
            $nilaiAktual,
            $nilaiPrediksi,
            $topN,
            $threshold
        );

        return self::SUCCESS;
    }
}
