<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Services\CollaborativeFilteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Use case: "Melakukan pemesanan menu" (Pelanggan) - Tabel 3.6 no.6
 * Halaman: "Halaman Pemesanan Menu" - Tabel 3.20 no.5 (keranjang + checkout)
 *
 * Keranjang disimpan sederhana di session: cart => [id_menu => jumlah]
 */
class PemesananController extends Controller
{
    public function keranjang(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $menu = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $items = [];
        $total = 0;
        foreach ($cart as $menuId => $jumlah) {
            if (!$menu->has($menuId)) {
                continue;
            }
            $subtotal = $menu[$menuId]->harga * $jumlah;
            $total += $subtotal;
            $items[] = ['menu' => $menu[$menuId], 'jumlah' => $jumlah, 'subtotal' => $subtotal];
        }

        return view('pelanggan.keranjang', compact('items', 'total'));
    }

    public function tambah(Request $request, Menu $menu)
    {
        $jumlah = max(1, (int) $request->input('jumlah', 1));

        $cart = $request->session()->get('cart', []);
        $cart[$menu->id_menu] = ($cart[$menu->id_menu] ?? 0) + $jumlah;
        $request->session()->put('cart', $cart);

        return back()->with('sukses', $menu->nama_menu . ' ditambahkan ke keranjang.');
    }

    public function hapusDariKeranjang(Request $request, int $menuId)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$menuId]);
        $request->session()->put('cart', $cart);

        return back()->with('sukses', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request, CollaborativeFilteringService $cf)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $pelanggan = $request->user()->pelanggan;
        if (!$pelanggan) {
            abort(403, 'Hanya pelanggan yang dapat melakukan pemesanan.');
        }

        $menuList = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $pesanan = DB::transaction(function () use ($cart, $menuList, $pelanggan) {
            $total = 0;
            foreach ($cart as $menuId => $jumlah) {
                if ($menuList->has($menuId)) {
                    $total += $menuList[$menuId]->harga * $jumlah;
                }
            }

            $pesanan = Pesanan::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'tanggal_pesanan' => now(),
                'total_harga' => $total,
                'status' => 'menunggu',
            ]);

            foreach ($cart as $menuId => $jumlah) {
                if (!$menuList->has($menuId)) {
                    continue;
                }
                $menu = $menuList[$menuId];

                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu' => $menuId,
                    'jumlah' => $jumlah,
                    'subtotal' => $menu->harga * $jumlah,
                ]);

                // Kurangi stok
                $menu->decrement('stok', min($jumlah, $menu->stok));
            }

            return $pesanan;
        });

        $request->session()->forget('cart');

        // Perbarui rekomendasi pelanggan karena riwayat pemesanan berubah
        $cf->simpanRekomendasiUntukPelanggan($pelanggan->id_pelanggan);

        return redirect()->route('pelanggan.pesanan.show', $pesanan->id_pesanan)
            ->with('sukses', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran di kasir.');
    }

    public function riwayat(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;

        $pesanan = Pesanan::where('id_pelanggan', $pelanggan?->id_pelanggan)
            ->with('detail.menu', 'transaksi')
            ->latest('tanggal_pesanan')
            ->paginate(10);

        return view('pelanggan.riwayat', compact('pesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        $this->authorizePesanan($pesanan);
        $pesanan->load('detail.menu', 'transaksi', 'pelanggan');

        return view('pelanggan.detail-pesanan', compact('pesanan'));
    }

    protected function authorizePesanan(Pesanan $pesanan): void
    {
        $user = auth()->user();
        if ($user->isPelanggan() && $pesanan->id_pelanggan !== $user->pelanggan?->id_pelanggan) {
            abort(403);
        }
    }
}
