<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel menu (Tabel 3.14 pada laporan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->foreignId('id_kategori')->constrained('kategori_menu', 'id_kategori')->onDelete('cascade');
            $table->string('nama_menu', 100);
            $table->decimal('harga', 10, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
