<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id'   => Category::factory(),
            'sku'           => fake()->unique()->bothify('SKU-####'),
            'name'          => ucwords(fake()->words(2, true)),
            'price'         => fake()->randomFloat(2, 10000, 500000),
            // Stok awal 0: stok sebenarnya diisi lewat transaksi barang masuk (lihat DatabaseSeeder)
            'stock'         => 0,
            'minimum_stock' => 5,
        ];
    }
}
