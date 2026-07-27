<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel rating (Tabel 3.18 pada laporan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->id('id_rating');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->tinyInteger('nilai_rating');
            $table->text('ulasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
