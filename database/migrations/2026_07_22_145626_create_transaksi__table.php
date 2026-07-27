<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel transaksi (Tabel 3.17 pada laporan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            $table->decimal('total_bayar', 10, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'e-wallet']);
            $table->enum('status_pembayaran', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
