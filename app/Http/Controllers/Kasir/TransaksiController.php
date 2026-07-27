<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

/**
 * Use case: "Mengelola transaksi" (Kasir) - Tabel 3.6 no.10
 * Halaman: "Halaman Kelola Transaksi" - Tabel 3.20 no.11 (bayar + cetak struk)
 */
class TransaksiController extends Controller
{
    public function create(Pesanan $pesanan)
    {
        $pesanan->load('detail.menu', 'pelanggan');

        return view('kasir.transaksi.create', compact('pesanan'));
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'metode_pembayaran' => ['required', 'in:tunai,transfer,e-wallet'],
            'status_pembayaran' => ['required', 'in:lunas,belum_lunas'],
        ]);

        $transaksi = Transaksi::updateOrCreate(
            ['id_pesanan' => $pesanan->id_pesanan],
            [
                'total_bayar' => $pesanan->total_harga,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'status_pembayaran' => $data['status_pembayaran'],
            ]
        );

        if ($data['status_pembayaran'] === 'lunas') {
            $pesanan->update(['status' => 'diproses']);
        }

        return redirect()->route('kasir.transaksi.struk', $transaksi->id_transaksi)
            ->with('sukses', 'Transaksi berhasil dicatat.');
    }

    public function struk(Transaksi $transaksi)
    {
        $transaksi->load('pesanan.detail.menu', 'pesanan.pelanggan');

        return view('kasir.transaksi.struk', compact('transaksi'));
    }

    public function index(Request $request)
    {
        $transaksi = Transaksi::with('pesanan.pelanggan')->latest()->paginate(15);

        return view('kasir.transaksi.index', compact('transaksi'));
    }
}
