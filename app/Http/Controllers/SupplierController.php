<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->orderBy('name')->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.form', ['supplier' => new Supplier()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Supplier::create($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.form', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->products()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier masih terhubung dengan produk, tidak dapat dihapus.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'  => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
    }
}
