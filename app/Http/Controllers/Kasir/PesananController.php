<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

/**
 * Use case: "Mengelola pesanan" (Kasir) - Tabel 3.6 no.9
 * Halaman: "Halaman Kelola Pesanan" - Tabel 3.20 no.10
 */
class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');

        $query = Pesanan::with('pelanggan', 'detail.menu', 'transaksi')->latest('tanggal_pesanan');
        if ($status) {
            $query->where('status', $status);
        }

        $pesanan = $query->paginate(15)->withQueryString();

        return view('kasir.pesanan.index', compact('pesanan', 'status'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,selesai,dibatalkan'],
        ]);

        $pesanan->update($data);

        return back()->with('sukses', 'Status pesanan #' . $pesanan->id_pesanan . ' diperbarui menjadi ' . $data['status'] . '.');
    }
}
