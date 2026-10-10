<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // GET /products : search, filter, sorting (allowlist), pagination
    public function index(Request $request)
    {
        $sorts = ['name', 'sku', 'price', 'stock'];
        $sort  = in_array($request->query('sort'), $sorts, true) ? $request->query('sort') : 'name';
        $dir   = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        $search     = trim($request->string('search')->toString());
        $categoryId = $request->integer('category_id');

        $products = Product::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString(); // search/filter/sort tetap terbawa saat pindah halaman

        // URL untuk judul kolom yang bisa diklik (klik lagi = arah sebaliknya)
        $sortUrls = [];
        foreach ($sorts as $col) {
            $sortUrls[$col] = route('products.index', array_merge($request->query(), [
                'sort' => $col,
                'dir'  => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc',
                'page' => 1,
            ]));
        }

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'sort', 'dir', 'sortUrls'));
    }

    public function create()
    {
        return view('products.create', [
            'product'    => new Product(['minimum_stock' => 5]),
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        // stok TIDAK diisi dari form; stok hanya berubah lewat transaksi masuk/keluar
        $product = Product::create(collect($validated)->only(['category_id', 'sku', 'name', 'price', 'minimum_stock'])->all());
        $product->suppliers()->sync($this->supplierSyncData($request));

        return redirect()->route('products.index')->with('success', 'Produk berhasil disimpan.');
    }

    // Route model binding: {product} otomatis menjadi objek Product (404 jika tidak ada)
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers']);
        $movements = $product->stockMovements()->with('user')->latest()->take(10)->get();

        return view('products.show', compact('product', 'movements'));
    }

    public function edit(Product $product)
    {
        $product->load('suppliers');

        return view('products.edit', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->rules($product));

        $product->update(collect($validated)->only(['category_id', 'sku', 'name', 'price', 'minimum_stock'])->all());
        $product->suppliers()->sync($this->supplierSyncData($request));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Business rule P4: produk yang sudah punya histori transaksi tidak boleh dihapus
        if ($product->stockMovements()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah memiliki histori transaksi.');
        }

        $product->delete(); // baris pivot ikut terhapus (cascade)

        return redirect()->route('products.index')->with('success', 'Produk dihapus.');
    }

    private function rules(?Product $product = null): array
    {
        return [
            'category_id'      => ['required', 'integer', 'exists:categories,id'],
            'sku'              => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product)],
            'name'             => ['required', 'string', 'max:150'],
            'price'            => ['required', 'numeric', 'min:0'],
            'minimum_stock'    => ['required', 'integer', 'min:0'],
            'suppliers'        => ['nullable', 'array'],
            'suppliers.*'      => ['integer', 'exists:suppliers,id'],
            'supplier_price'   => ['nullable', 'array'],
            'supplier_price.*' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    // Susun data untuk sync() many-to-many: [supplier_id => ['supplier_price' => harga]]
    private function supplierSyncData(Request $request): array
    {
        $data = [];
        foreach ((array) $request->input('suppliers', []) as $supplierId) {
            $price = $request->input("supplier_price.{$supplierId}");
            $data[(int) $supplierId] = ['supplier_price' => ($price === null || $price === '') ? null : $price];
        }

        return $data;
    }
}
