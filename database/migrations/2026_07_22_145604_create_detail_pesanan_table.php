<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel detail pesanan (Tabel 3.16 pada laporan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
