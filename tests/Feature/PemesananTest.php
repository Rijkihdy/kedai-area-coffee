<?php

use Illuminate\Support\ViewErrorBag;

it('renders add-to-cart as a POST form on the menu detail page', function () {
    $menu = new class {
        public $id_menu = 4;
        public $nama_menu = 'Espresso';
        public $harga = 12000;
        public $deskripsi = 'Kopi hitam';
        public $stok = 10;
        public $foto = null;
        public $kategori;

        public function __construct()
        {
            $this->kategori = new class {
                public $nama_kategori = 'Coffee';
            };
        }
    };

    $expectedRoute = route('pelanggan.keranjang.tambah', $menu->id_menu);
    $html = view('dashboard.menu-detail', [
        'menu' => $menu,
        'menuPendamping' => [],
    ])->with('errors', new ViewErrorBag())->render();

    expect($html)
        ->toContain('method="POST"')
        ->and($html)->toContain($expectedRoute)
        ->and($html)->not->toContain('href="' . $expectedRoute . '"');
});
