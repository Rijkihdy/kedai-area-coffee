<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = ['id_kategori', 'nama_menu', 'harga', 'stok', 'deskripsi', 'gambar'];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'id_kategori', 'id_kategori');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_menu', 'id_menu');
    }

    public function rataRataRating(): float
    {
        return round($this->ratings()->avg('nilai_rating') ?? 0, 2);
    }
}
