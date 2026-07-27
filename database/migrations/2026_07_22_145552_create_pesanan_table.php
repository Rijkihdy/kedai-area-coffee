<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pesanan (Tabel 3.15 pada laporan).
 * Kolom `status` ditambahkan (di luar tabel 3.15) agar use case
 * "Mengelola pesanan" oleh Kasir bisa dijalankan (menunggu, diproses, selesai, dibatalkan).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->dateTime('tanggal_pesanan');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
