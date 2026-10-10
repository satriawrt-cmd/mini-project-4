<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'total_products'  => Product::count(),
            'inventory_value' => Product::sum(DB::raw('stock * price')),
            'low_count'       => Product::whereColumn('stock', '<=', 'minimum_stock')->count(),
            'total_in'        => StockMovement::where('type', 'IN')->sum('quantity'),
            'total_out'       => StockMovement::where('type', 'OUT')->sum('quantity'),
        ];

        $lowStock = Product::whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->get();

        // Ringkasan per kategori: withCount / withSum / withAvg (setara JOIN + GROUP BY di P4)
        $categories = Category::withCount('products')
            ->withSum('products as total_stock', 'stock')
            ->withAvg('products as average_price', 'price')
            ->orderBy('name')
            ->get();

        // Laporan inventory: total masuk dan keluar per produk
        $report = Product::query()
            ->withSum(['stockMovements as total_in' => fn ($q) => $q->where('type', 'IN')], 'quantity')
            ->withSum(['stockMovements as total_out' => fn ($q) => $q->where('type', 'OUT')], 'quantity')
            ->orderBy('name')
            ->get();

        return view('dashboard', compact('stats', 'lowStock', 'categories', 'report'));
    }
}
