<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = ['id_pelanggan', 'tanggal_pesanan', 'total_harga', 'status'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_pesanan', 'id_pesanan');
    }
}
