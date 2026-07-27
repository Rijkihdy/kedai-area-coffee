<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\CollaborativeFilteringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder data dummy untuk demo & testing.
 * Nama-nama menu mengikuti Data Simulasi pada BAB 3.5-3.7 laporan TA
 * (Teh Jeruk Peras / M14, Pisang Keju, Teh Susu, Nasi Chicken Katsu, dst.)
 * sehingga hasil perhitungan CF di aplikasi bisa dibandingkan dengan
 * simulasi manual pada laporan.
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------- USERS ----------------
        $admin = User::create([
            'name' => 'Admin Kedai',
            'email' => 'admin@kedaiareacoffee.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $admin->assignRole('admin');

        $kasir1 = User::create([
        'name' => 'Rian Kasir',
        'email' => 'kasir1@kedaiareacoffee.test',
        'password' => Hash::make('password'),
        'role' => 'kasir',
        ]);
        $kasir1->assignRole('kasir');

        $kasir2 = User::create([
            'name' => 'Sari Kasir',
            'email' => 'kasir2@kedaiareacoffee.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);
        $kasir2->assignRole('kasir');

        $pemilik = User::create([
            'name' => 'Pemilik Kedai Area Coffee',
            'email' => 'pemilik@kedaiareacoffee.test',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
        ]);
        $pemilik->assignRole('pemilik');

        // 8 pelanggan dummy (P1..P8)
        $namaPelanggan = ['Budi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Rina Marlina', 'Dedi Kurniawan', 'Wati Suryani', 'Fajar Ramadhan', 'Lina Putri'];
        $usernamePelanggan = ['budi', 'siti', 'andi', 'rina', 'dedi', 'wati', 'fajar', 'lina'];

        $pelangganList = [];
        foreach ($namaPelanggan as $i => $nama) {
        $u = User::create([
            'name' => $nama,
            'email' => $usernamePelanggan[$i] . '@mail.test',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
        ]);
        $u->assignRole('pelanggan');
            $pelangganList[] = Pelanggan::create([
                'id_user' => $u->id_user,
                'nama' => $nama,
                'alamat' => 'Jl. Contoh No. ' . ($i + 1) . ', Cianjur',
            ]);
        }

        // ---------------- KATEGORI & MENU ----------------
        // Struktur & harga di bawah bersifat dummy untuk keperluan testing.
        $struktur = [
            'Kopi' => [
                ['Es Kopi Susu Gula Aren', 22000],
                ['Es Kopi Susu Jelly', 23000],
                ['Es Kopi Citrus', 21000],
                ['Coffee Beer', 25000],
                ['Japanese Coffee', 24000],
            ],
            'Teh' => [
                ['Teh Susu', 18000],
                ['Teh Strawberry', 19000],
                ['Teh Lecy', 18000],
                ['Teh Mango', 19000],
                ['Teh Jeruk Peras', 17000],
                ['Teh Lemon', 17000],
                ['Teh Manis', 12000],
            ],
            'Dessert' => [
                ['Matcha Oreo', 26000],
                ['Red Velvet', 27000],
                ['Pisang Keju', 20000],
            ],
            'Mie Instan' => [
                ['Indomie Goreng', 15000],
                ['Indomie Ikan Laut', 16000],
                ['Indomie Ala Thailand', 17000],
                ['Mie Nasi Goreng', 18000],
                ['Indomie Rebus Kuah', 15000],
                ['Indomie Telor Dadar', 17000],
            ],
            'Nasi' => [
                ['Nasi Chicken Katsu', 28000],
                ['Nasi Daun Jeruk Ayam Goreng Bawang', 26000],
                ['Nasi Goreng Butter', 24000],
                ['Nasi Telur Kecap Pedas Manis', 22000],
            ],
        ];

        $menuList = [];
        foreach ($struktur as $namaKategori => $daftarMenu) {
            $kategori = KategoriMenu::create(['nama_kategori' => $namaKategori]);

            foreach ($daftarMenu as [$namaMenu, $harga]) {
                $menuList[] = Menu::create([
                    'id_kategori' => $kategori->id_kategori,
                    'nama_menu' => $namaMenu,
                    'harga' => $harga,
                    'stok' => rand(15, 50),
                    'deskripsi' => $namaMenu . ' khas Kedai Area Coffee.',
                ]);
            }
        }

        // ---------------- RIWAYAT PESANAN (untuk membangun matriks CF) ----------------
        // Pola pesanan dibuat manual agar ada tumpang-tindih antar pelanggan
        // (menu yang sering dipesan bersamaan) sehingga cosine similarity bermakna.
        $menuByName = collect($menuList)->keyBy('nama_menu');

        $polaPesanan = [
            // Budi: manis-manis & kopi susu
            'Budi Santoso' => ['Es Kopi Susu Gula Aren' => 2, 'Pisang Keju' => 3, 'Teh Susu' => 2, 'Nasi Goreng Butter' => 1],
            // Siti: pecinta teh & dessert
            'Siti Aminah' => ['Teh Susu' => 4, 'Teh Strawberry' => 2, 'Red Velvet' => 3, 'Matcha Oreo' => 2, 'Pisang Keju' => 2],
            // Andi: mie instan & nasi
            'Andi Wijaya' => ['Indomie Goreng' => 3, 'Indomie Ikan Laut' => 2, 'Nasi Chicken Katsu' => 4, 'Nasi Goreng Butter' => 2],
            // Rina: kopi & mie
            'Rina Marlina' => ['Es Kopi Susu Jelly' => 2, 'Coffee Beer' => 5, 'Indomie Ala Thailand' => 1, 'Mie Nasi Goreng' => 3],
            // Dedi: nasi & teh manis
            'Dedi Kurniawan' => ['Nasi Chicken Katsu' => 5, 'Nasi Daun Jeruk Ayam Goreng Bawang' => 7, 'Teh Manis' => 2, 'Indomie Goreng' => 2],
            // Wati: teh & pisang keju & nasi (mirip Budi & Siti -> penentu nearest neighbor Teh Jeruk Peras)
            'Wati Suryani' => ['Teh Susu' => 5, 'Teh Strawberry' => 2, 'Pisang Keju' => 6, 'Nasi Chicken Katsu' => 5, 'Red Velvet' => 4],
            // Fajar: kopi & citrus
            'Fajar Ramadhan' => ['Es Kopi Citrus' => 4, 'Japanese Coffee' => 1, 'Teh Mango' => 1, 'Indomie Ikan Laut' => 3],
            // Lina: campuran (P1 pada simulasi laporan - BELUM pernah pesan Teh Jeruk Peras)
            'Lina Putri' => [
                'Es Kopi Susu Gula Aren' => 2, 'Es Kopi Susu Jelly' => 1, 'Es Kopi Citrus' => 4, 'Coffee Beer' => 7,
                'Matcha Oreo' => 3, 'Japanese Coffee' => 1, 'Teh Susu' => 5, 'Teh Strawberry' => 2, 'Teh Lecy' => 8,
                'Teh Mango' => 1, 'Red Velvet' => 4, 'Pisang Keju' => 6, 'Indomie Goreng' => 2, 'Indomie Ikan Laut' => 3,
                'Indomie Ala Thailand' => 1, 'Nasi Chicken Katsu' => 5, 'Nasi Daun Jeruk Ayam Goreng Bawang' => 7,
                'Nasi Goreng Butter' => 2, 'Mie Nasi Goreng' => 1,
            ],
        ];

        foreach ($pelangganList as $idx => $pelanggan) {
            $nama = $namaPelanggan[$idx];
            $pola = $polaPesanan[$nama] ?? [];

            if (empty($pola)) {
                continue;
            }

            $tanggal = Carbon::now()->subDays(rand(3, 45));
            $total = 0;

            foreach ($pola as $namaMenu => $jumlah) {
                if (!$menuByName->has($namaMenu)) {
                    continue;
                }
                $total += $menuByName[$namaMenu]->harga * $jumlah;
            }

            $pesanan = Pesanan::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'tanggal_pesanan' => $tanggal,
                'total_harga' => $total,
                'status' => 'selesai',
            ]);

            foreach ($pola as $namaMenu => $jumlah) {
                if (!$menuByName->has($namaMenu)) {
                    continue;
                }
                $menu = $menuByName[$namaMenu];

                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu' => $menu->id_menu,
                    'jumlah' => $jumlah,
                    'subtotal' => $menu->harga * $jumlah,
                ]);
            }

            Transaksi::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'total_bayar' => $total,
                'metode_pembayaran' => ['tunai', 'transfer', 'e-wallet'][array_rand(['tunai', 'transfer', 'e-wallet'])],
                'status_pembayaran' => 'lunas',
            ]);

            // beberapa rating acak untuk sebagian menu yang dipesan
            $menuNames = array_keys($pola);
            foreach (array_slice($menuNames, 0, 2) as $namaMenu) {
                Rating::create([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'id_menu' => $menuByName[$namaMenu]->id_menu,
                    'nilai_rating' => rand(3, 5),
                    'ulasan' => 'Enak dan sesuai harapan.',
                ]);
            }
        }

        // ---------------- HITUNG REKOMENDASI AWAL ----------------
        (new CollaborativeFilteringService())->jalankanUntukSemuaPelanggan();

        $this->command?->info('Data dummy berhasil dibuat. Login: admin/password, kasir1/password, pemilik/password, budi/password, dst.');
    }
}
