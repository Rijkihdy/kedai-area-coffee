<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Use case: "Melihat laporan penjualan" (Pemilik Cafe) - Tabel 3.6 no.11
 * Halaman: "Halaman Laporan Penjualan" - Tabel 3.20 no.12
 */
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $transaksi = Transaksi::query()
            ->where('status_pembayaran', 'lunas')
            ->whereHas('pesanan', function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_pesanan', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);
            })
            ->with('pesanan.pelanggan', 'pesanan.detail.menu')
            ->get();

        $totalPenjualan = $transaksi->sum('total_bayar');
        $jumlahTransaksi = $transaksi->count();

        $menuTerlaris = DB::table('detail_pesanan')
            ->join('pesanan', 'pesanan.id_pesanan', '=', 'detail_pesanan.id_pesanan')
            ->join('transaksi', 'transaksi.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('menu', 'menu.id_menu', '=', 'detail_pesanan.id_menu')
            ->where('transaksi.status_pembayaran', 'lunas')
            ->whereBetween('pesanan.tanggal_pesanan', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
            ->select('menu.nama_menu', DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'), DB::raw('SUM(detail_pesanan.subtotal) as total_omzet'))
            ->groupBy('menu.nama_menu')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        return view('pemilik.laporan', compact('transaksi', 'totalPenjualan', 'jumlahTransaksi', 'menuTerlaris', 'dari', 'sampai'));
    }
}
