<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel kategori menu (Tabel 3.13 pada laporan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_menu', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_menu');
    }
};
