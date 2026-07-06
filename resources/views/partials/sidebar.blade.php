@php
    $navUser = auth()->user();
    $roleLabel = match ($navUser->role_id) {
        1 => 'Admin',
        2 => 'Staff',
        3 => 'Manager',
        default => 'Unknown',
    };

    // Helper kecil untuk class menu aktif
    $linkClass = fn (bool $active) => $active
        ? 'flex items-center space-x-3 bg-red-700 px-4 py-3 rounded-lg text-sm font-semibold transition'
        : 'flex items-center space-x-3 hover:bg-red-600 px-4 py-3 rounded-lg text-sm font-medium transition';
@endphp

<aside class="w-64 bg-red-600 text-white flex flex-col justify-between">
    <div>
        <div class="px-4 py-5 border-b border-red-700 flex items-center space-x-3">
            <img src="{{ asset('assets/img/telkom-logo.png') }}" alt="Logo"
                 class="w-10 h-10 object-contain bg-white rounded-full p-1">
            <span class="font-bold text-lg tracking-wider">TSK INVENTORY</span>
        </div>

        <nav class="mt-6 px-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="{{ $linkClass(request()->routeIs('dashboard')) }}">
                <span>📊 Dashboard</span>
            </a>

            @if($navUser->role_id == 1 || $navUser->role_id == 2)
                <a href="{{ route('products.index') }}" class="{{ $linkClass(request()->routeIs('products.*')) }}">
                    <span>📦 Master Data Barang</span>
                </a>
                <a href="{{ route('categories.index') }}" class="{{ $linkClass(request()->routeIs('categories.*')) }}">
                    <span>🗂️ Kategori Barang</span>
                </a>
                <a href="{{ route('borrowings.index') }}" class="{{ $linkClass(request()->routeIs('borrowings.*')) }}">
                    <span>🔄 Peminjaman Barang</span>
                </a>
            @endif

            @if($navUser->role_id == 1 || $navUser->role_id == 3)
                <a href="#" class="{{ $linkClass(request()->routeIs('reports.*')) }}">
                    <span>📜 Laporan Inventaris</span>
                </a>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t border-red-700 bg-red-900 bg-opacity-30">
        <div class="text-xs opacity-75">Login sebagai:</div>
        <div class="font-bold text-sm truncate">{{ $navUser->name }}</div>
        <div class="inline-block bg-white text-red-600 text-[10px] font-extrabold px-2 py-0.5 rounded mt-1 uppercase">
            {{ $roleLabel }}
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full text-left text-xs text-red-200 hover:text-white font-medium transition">
                🚪 Keluar Aplikasi
            </button>
        </form>
    </div>
</aside>