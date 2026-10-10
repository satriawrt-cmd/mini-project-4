<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // One-to-many: categories -> products. RESTRICT: kategori yang dipakai produk tidak bisa dihapus.
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('sku', 50)->unique();
            $table->string('name', 150);
            $table->decimal('price', 12, 2);
            // unsigned: database menolak stok negatif (lapisan pengaman kedua setelah validasi)
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('minimum_stock')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
