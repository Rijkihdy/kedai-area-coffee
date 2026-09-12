<?php

use App\Services\CollaborativeFilteringService;

it('menghitung precision recall f1 dan mape dengan benar', function () {
    $service = new CollaborativeFilteringService();

    $nilaiAktual = [
        10 => 5.0,
        11 => 4.0,
        12 => 2.0,
        13 => 1.0,
    ];

    $nilaiPrediksi = [
        10 => 4.8,
        11 => 4.2,
        12 => 3.0,
        13 => 1.5,
    ];

    $hasil = $service->hitungMetrikEvaluasi(
        $nilaiAktual,
        $nilaiPrediksi,
        4,
        4.0
    );

    expect($hasil)->toHaveKeys(['mape', 'precision', 'recall', 'f1_score'])
        ->and($hasil['mape'])->toBeGreaterThan(0)
        ->and($hasil['precision'])->toBeGreaterThanOrEqual(0)
        ->and($hasil['recall'])->toBeGreaterThanOrEqual(0)
        ->and($hasil['f1_score'])->toBeGreaterThanOrEqual(0);
});
