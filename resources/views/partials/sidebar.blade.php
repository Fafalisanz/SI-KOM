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
@endphp

<aside class="w-64 bg-red-600 text-white flex flex-col justify-between">
    <div>
        <div class="px-4 py-5 border-b border-red-700 flex items-center space-x-3">
            <img src="{{ asset('assets/img/telkom-logo-v2.png') }}" alt="Logo"
                 class="w-10 h-10 object-contain bg-white rounded-full p-1">
            <span class="font-bold text-lg tracking-wider">Invent Telkom</span>
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