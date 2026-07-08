@php
    $navUser = auth()->user();
    $roleLabel = match ($navUser->role_id) {
        1 => 'Admin',
        2 => 'Staff',
        3 => 'Manager',
        default => 'Unknown',
    };

    $linkClass = fn (bool $active) => $active
        ? 'flex items-center space-x-3 bg-red-700 px-4 py-3 rounded-lg text-sm font-semibold transition'
        : 'flex items-center space-x-3 hover:bg-red-600 px-4 py-3 rounded-lg text-sm font-medium transition';

    // Notifikasi Stok Menipis - dihitung ulang tiap halaman dimuat
    $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)->orderBy('stock')->get();
    $lowStockCount = $lowStockProducts->count();
@endphp

<aside class="w-64 bg-red-600 text-white flex flex-col justify-between">
    <div>
        <div class="px-4 py-5 border-b border-red-700 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('assets/img/telkom-logo-v2.png') }}" alt="Logo"
                     class="w-10 h-10 object-contain">
                <span class="font-bold text-lg tracking-wider">Invent Telkom</span>
            </div>

            {{-- BELL NOTIFIKASI STOK MENIPIS --}}
            <div class="relative">
                <button type="button" onclick="document.getElementById('notif-panel').classList.toggle('hidden')"
                        class="relative p-1.5 rounded-lg hover:bg-red-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                    </svg>
                    @if($lowStockCount > 0)
                        <span class="absolute -top-1 -right-1 bg-yellow-400 text-red-900 text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN PANEL --}}
                <div id="notif-panel"
                     class="hidden absolute left-full top-0 ml-2 w-72 bg-white text-gray-800 rounded-lg shadow-xl border border-gray-200 z-50">
                    <div class="px-4 py-3 border-b border-gray-100 font-semibold text-sm">
                        Notifikasi Stok Menipis
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        @forelse($lowStockProducts as $lowStock)
                            <a href="{{ route('products.edit', $lowStock) }}"
                               class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 border-b border-gray-50 text-sm">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $lowStock->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $lowStock->product_code }}</p>
                                </div>
                                <span class="text-xs font-bold {{ $lowStock->stock == 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                    Sisa {{ $lowStock->stock }}
                                </span>
                            </a>
                        @empty
                            <p class="px-4 py-6 text-center text-sm text-gray-400">
                                Semua stok aman 👍
                            </p>
                        @endforelse
                    </div>
                    @if($lowStockCount > 0)
                        <a href="{{ route('products.index') }}" class="block px-4 py-2 text-center text-xs font-semibold text-red-600 hover:bg-gray-50 border-t border-gray-100">
                            Lihat Semua Barang
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <nav class="mt-6 px-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="{{ $linkClass(request()->routeIs('dashboard')) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            @if($navUser->role_id == 1 || $navUser->role_id == 2)
                <a href="{{ route('products.index') }}" class="{{ $linkClass(request()->routeIs('products.*')) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Master Data Barang</span>
                </a>
                <a href="{{ route('categories.index') }}" class="{{ $linkClass(request()->routeIs('categories.*')) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                    </svg>
                    <span>Kategori Barang</span>
                </a>
                <a href="{{ route('borrowings.index') }}" class="{{ $linkClass(request()->routeIs('borrowings.*')) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Peminjaman Barang</span>
                </a>
            @endif

            @if($navUser->role_id == 1 || $navUser->role_id == 3)
                <a href="{{ route('reports.index') }}" class="{{ $linkClass(request()->routeIs('reports.*')) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 17V9m4 8V5m4 12v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Laporan Inventaris</span>
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
            <button type="submit" class="w-full flex items-center space-x-2 text-left text-xs text-red-200 hover:text-white font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('click', function (event) {
        const panel = document.getElementById('notif-panel');
        if (!panel || panel.classList.contains('hidden')) return;

        const bellButton = event.target.closest('button');
        const isClickInsidePanel = panel.contains(event.target);
        const isClickOnBell = bellButton && bellButton.onclick;

        if (!isClickInsidePanel && !isClickOnBell) {
            panel.classList.add('hidden');
        }
    });
</script>