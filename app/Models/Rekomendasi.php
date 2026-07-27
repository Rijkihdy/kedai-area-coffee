<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasi';
    protected $primaryKey = 'id_rekomendasi';
    public $timestamps = false;

    protected $fillable = ['id_pelanggan', 'id_menu', 'skor_rekomendasi', 'tanggal_dibuat'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
