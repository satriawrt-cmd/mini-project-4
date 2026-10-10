<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Business logic stok dari P4, dipindahkan apa adanya:
 *  - satu proses mengubah dua tabel (products + stock_movements)
 *  - dibungkus database transaction (semua berhasil atau semua dibatalkan)
 *  - barang keluar tidak boleh melebihi stok (stok tidak boleh negatif)
 */
class StockService
{
    public function record(int $productId, int $userId, string $type, int $quantity, ?string $note = null): StockMovement
    {
        return DB::transaction(function () use ($productId, $userId, $type, $quantity, $note) {
            // SELECT ... FOR UPDATE: kunci baris produk agar transaksi bersamaan tidak membuat stok negatif
            $product = Product::whereKey($productId)->lockForUpdate()->firstOrFail();

            if ($type === 'OUT') {
                if ($quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'quantity' => "Stok tidak cukup. Stok tersedia: {$product->stock}.",
                    ]);
                }
                $product->decrement('stock', $quantity);
            } else {
                $product->increment('stock', $quantity);
            }

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id'    => $userId,
                'type'       => $type,
                'quantity'   => $quantity,
                'note'       => $note,
            ]);
        });
    }
}
