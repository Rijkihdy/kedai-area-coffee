<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => $this->admin(),
            'kasir' => $this->kasir(),
            'pemilik' => $this->pemilik(),
            default => $this->pelanggan($request),
        };
    }

    protected function admin()
    {
        $data = [
            'totalMenu' => Menu::count(),
            'totalPesananHariIni' => Pesanan::whereDate('tanggal_pesanan', now()->toDateString())->count(),
            'menuStokMenipis' => Menu::where('stok', '<=', 5)->orderBy('stok')->get(),
        ];

        return view('dashboard.admin', $data);
    }

    protected function kasir()
    {
        $data = [
            'pesananMenunggu' => Pesanan::where('status', 'menunggu')->with('pelanggan', 'detail.menu')->latest('tanggal_pesanan')->get(),
            'pesananDiproses' => Pesanan::where('status', 'diproses')->with('pelanggan', 'detail.menu')->latest('tanggal_pesanan')->get(),
        ];

        return view('dashboard.kasir', $data);
    }

    protected function pemilik()
    {
        $data = [
            'totalPenjualan' => Transaksi::where('status_pembayaran', 'lunas')->sum('total_bayar'),
            'totalTransaksi' => Transaksi::where('status_pembayaran', 'lunas')->count(),
            'menuTerlaris' => DB::table('detail_pesanan')
                ->join('menu', 'menu.id_menu', '=', 'detail_pesanan.id_menu')
                ->select('menu.nama_menu', DB::raw('SUM(detail_pesanan.jumlah) as total'))
                ->groupBy('menu.nama_menu')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ];

        return view('dashboard.pemilik', $data);
    }

    protected function pelanggan(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;

        $data = [
            'menuTerbaru' => Menu::latest()->limit(6)->get(),
            'riwayatPesanan' => $pelanggan
                ? Pesanan::where('id_pelanggan', $pelanggan->id_pelanggan)->latest('tanggal_pesanan')->limit(5)->get()
                : collect(),
        ];

        return view('dashboard.pelanggan', $data);
    }
}
