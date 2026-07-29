<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kasir\PesananController as KasirPesananController;
use App\Http\Controllers\Kasir\TransaksiController as KasirTransaksiController;
use App\Http\Controllers\MenuPublikController;
use App\Http\Controllers\Pemilik\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RekomendasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---------- Pelanggan ----------
    Route::middleware('role:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/menu', [MenuPublikController::class, 'index'])->name('menu.index');
        Route::get('/menu/{menu}', [MenuPublikController::class, 'show'])->name('menu.show');

        Route::get('/keranjang', [PemesananController::class, 'keranjang'])->name('keranjang');
        Route::post('/keranjang/{menu}', [PemesananController::class, 'tambah'])->name('keranjang.tambah');
        Route::delete('/keranjang/{menuId}', [PemesananController::class, 'hapusDariKeranjang'])->name('keranjang.hapus');
        Route::post('/checkout', [PemesananController::class, 'checkout'])->name('checkout');

        Route::get('/pesanan', [PemesananController::class, 'riwayat'])->name('pesanan.riwayat');
        Route::get('/pesanan/{pesanan}', [PemesananController::class, 'show'])->name('pesanan.show');

        Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi');
        Route::post('/rekomendasi/hitung-ulang', [RekomendasiController::class, 'hitungUlang'])->name('rekomendasi.hitung-ulang');

        Route::get('/rating/{menu}', [RatingController::class, 'create'])->name('rating.create');
        Route::post('/rating/{menu}', [RatingController::class, 'store'])->name('rating.store');
    });

    // ---------- Admin ----------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('menu', MenuController::class)->except(['show']);
        Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['kategori' => 'kategori']);
    });

    // ---------- Kasir ----------
    Route::middleware('role:kasir')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/pesanan', [KasirPesananController::class, 'index'])->name('pesanan.index');
        Route::patch('/pesanan/{pesanan}/status', [KasirPesananController::class, 'updateStatus'])->name('pesanan.status');

        Route::get('/transaksi', [KasirTransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{pesanan}/bayar', [KasirTransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi/{pesanan}/bayar', [KasirTransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{transaksi}/struk', [KasirTransaksiController::class, 'struk'])->name('transaksi.struk');
    });

    // ---------- Pemilik Cafe ----------
    Route::middleware('role:pemilik')->prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    });
});


require __DIR__.'/auth.php';
