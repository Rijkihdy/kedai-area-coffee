<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'id_rating';

    protected $fillable = ['id_pelanggan', 'id_menu', 'nilai_rating', 'ulasan'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
