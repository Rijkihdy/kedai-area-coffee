<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = ['id_user', 'nama', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function rekomendasi()
    {
        return $this->hasMany(Rekomendasi::class, 'id_pelanggan', 'id_pelanggan');
    }
}
