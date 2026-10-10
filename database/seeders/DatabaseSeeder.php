<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $stock = app(StockService::class);

        // --- Users (login: admin/admin123, staff/staff123) ---
        $admin = User::create([
            'name' => 'Administrator Toko', 'username' => 'admin', 'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), 'role' => 'admin',
        ]);
        $staff = User::create([
            'name' => 'Staff Gudang', 'username' => 'staff', 'email' => 'staff@example.com',
            'password' => Hash::make('staff123'), 'role' => 'staff',
        ]);

        // --- Kategori & supplier (data sama seperti P4) ---
        $aksesoris  = Category::create(['name' => 'Aksesoris']);
        $elektronik = Category::create(['name' => 'Elektronik']);
        $alatTulis  = Category::create(['name' => 'Alat Tulis']);

        $majuJaya = Supplier::create(['name' => 'PT Maju Jaya', 'phone' => '081234567890']);
        $sumber   = Supplier::create(['name' => 'CV Sumber Rejeki', 'phone' => '082198765432']);

        // --- Produk P4 ---
        $kb = Product::create(['category_id' => $aksesoris->id,  'sku' => 'KB-001', 'name' => 'Keyboard Mechanical', 'price' => 350000, 'minimum_stock' => 5]);
        $ms = Product::create(['category_id' => $aksesoris->id,  'sku' => 'MS-001', 'name' => 'Mouse Wireless',      'price' => 120000, 'minimum_stock' => 5]);
        $hd = Product::create(['category_id' => $elektronik->id, 'sku' => 'HD-001', 'name' => 'Headset Gaming',      'price' => 275000, 'minimum_stock' => 5]);
        $pn = Product::create(['category_id' => $alatTulis->id,  'sku' => 'PN-001', 'name' => 'Pulpen Gel (box)',    'price' => 45000,  'minimum_stock' => 10]);

        // Many-to-many produk <-> supplier (dengan kolom pivot supplier_price)
        $kb->suppliers()->attach([$majuJaya->id => ['supplier_price' => 300000], $sumber->id => ['supplier_price' => 310000]]);
        $ms->suppliers()->attach([$majuJaya->id => ['supplier_price' => 95000]]);
        $hd->suppliers()->attach([$sumber->id => ['supplier_price' => 230000]]);
        $pn->suppliers()->attach([$majuJaya->id => ['supplier_price' => 35000]]);

        // Stok dibentuk lewat transaksi, jadi stok = total masuk - total keluar
        $stock->record($kb->id, $admin->id, 'IN', 20, 'Stok awal');
        $stock->record($kb->id, $staff->id, 'OUT', 5, 'Penjualan');
        $stock->record($ms->id, $admin->id, 'IN', 10, 'Stok awal');
        $stock->record($ms->id, $staff->id, 'OUT', 7, 'Penjualan');
        $stock->record($hd->id, $admin->id, 'IN', 8, 'Stok awal');
        $stock->record($pn->id, $admin->id, 'IN', 40, 'Stok awal');

        // --- Data dummy dari factory agar pagination bisa diuji ---
        $extraCategories = Category::factory(3)->create();
        $allCategories = Category::all();

        Product::factory(20)
            ->state(fn () => ['category_id' => $allCategories->random()->id])
            ->create()
            ->each(function (Product $product) use ($stock, $admin) {
                $stock->record($product->id, $admin->id, 'IN', random_int(1, 60), 'Stok awal (seeder)');
            });
    }
}
