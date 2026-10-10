<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function createIn()
    {
        return view('stock.in', ['products' => Product::orderBy('name')->get()]);
    }

    public function storeIn(Request $request, StockService $stock): RedirectResponse
    {
        $data = $this->validated($request);
        $stock->record($data['product_id'], $request->user()->id, 'IN', $data['quantity'], $data['note'] ?? null);

        return redirect()->route('stock.in.create')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function createOut()
    {
        return view('stock.out', ['products' => Product::orderBy('name')->get()]);
    }

    // Jika stok tidak cukup, StockService melempar ValidationException -> kembali ke form dengan pesan error
    public function storeOut(Request $request, StockService $stock): RedirectResponse
    {
        $data = $this->validated($request);
        $stock->record($data['product_id'], $request->user()->id, 'OUT', $data['quantity'], $data['note'] ?? null);

        return redirect()->route('stock.out.create')->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function history()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->latest()
            ->orderByDesc('id')
            ->paginate(15);

        return view('stock.history', compact('movements'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'note'       => ['nullable', 'string', 'max:255'],
        ]);
    }
}
