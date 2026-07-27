<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel rekomendasi (Tabel 3.19 pada laporan).
 * Menyimpan hasil perhitungan Item-Based Collaborative Filtering per pelanggan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id('id_rekomendasi');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->decimal('skor_rekomendasi', 6, 4);
            $table->dateTime('tanggal_dibuat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
