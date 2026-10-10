{{-- Form dipakai bersama oleh create dan edit. Variabel: $product, $categories, $suppliers, $action, $method --}}
@php
    // [supplier_id => supplier_price] untuk supplier yang sudah terhubung (kosong saat create)
    $linked = $product->exists ? $product->suppliers->pluck('pivot.supplier_price', 'id') : collect();
@endphp

<form method="POST" action="{{ $action }}" class="card">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <label for="sku">SKU</label>
    <input id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
    @error('sku') <small class="error">{{ $message }}</small> @enderror

    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $product->name) }}" required>
    @error('name') <small class="error">{{ $message }}</small> @enderror

    <label for="category_id">Kategori</label>
    <select id="category_id" name="category_id" required>
        <option value="">-- pilih --</option>
        @foreach ($categories as $c)
            <option value="{{ $c->id }}" @selected((int) old('category_id', $product->category_id) === (int) $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    @error('category_id') <small class="error">{{ $message }}</small> @enderror

    <label for="price">Harga</label>
    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
    @error('price') <small class="error">{{ $message }}</small> @enderror

    <label for="minimum_stock">Minimum stok</label>
    <input id="minimum_stock" type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
    @error('minimum_stock') <small class="error">{{ $message }}</small> @enderror

    <label>Supplier (many-to-many)</label>
    @foreach ($suppliers as $s)
        @php
            $checked = old('suppliers') !== null
                ? in_array($s->id, old('suppliers'))
                : $linked->has($s->id);
        @endphp
        <div>
            <label style="font-weight:400">
                <input type="checkbox" style="width:auto" name="suppliers[]" value="{{ $s->id }}" @checked($checked)>
                {{ $s->name }}
            </label>
            <input type="number" step="0.01" min="0" name="supplier_price[{{ $s->id }}]"
                   placeholder="Harga dari supplier"
                   value="{{ old('supplier_price.' . $s->id, $linked->get($s->id)) }}">
            @error('supplier_price.' . $s->id) <small class="error">{{ $message }}</small> @enderror
        </div>
    @endforeach

    <p>
        <button type="submit">Simpan</button>
        <a class="btn gray" href="{{ route('products.index') }}">Batal</a>
    </p>
</form>
