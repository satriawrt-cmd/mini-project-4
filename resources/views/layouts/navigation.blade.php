<nav>
    <strong>📦 Inventory</strong>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('products.index') }}">Produk</a>
    <a href="{{ route('stock.in.create') }}">Barang Masuk</a>
    <a href="{{ route('stock.out.create') }}">Barang Keluar</a>
    <a href="{{ route('stock.history') }}">Histori</a>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('categories.index') }}">Kategori</a>
        <a href="{{ route('suppliers.index') }}">Supplier</a>
        <a href="{{ route('users.index') }}">User</a>
    @endif
    <span class="right">
        {{ auth()->user()->username }} ({{ auth()->user()->role }}) ·
        <form class="inline" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="linkbtn" type="submit">Logout</button>
        </form>
    </span>
</nav>
