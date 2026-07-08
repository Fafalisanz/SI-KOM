<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Barang') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
        $canManage = in_array($user->role_id, [1, 2]);
    @endphp

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Master Data Barang</h1>
                <div class="flex gap-2">
                    <a href="{{ route('products.export.excel', request()->query()) }}"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                        </svg>
                        Export Excel
                    </a>
                    @if($canManage)
                        <a href="{{ route('products.create') }}"
                           class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                            + Tambah Barang Baru
                        </a>
                    @endif
                </div>
            </header>

            <div class="p-8 overflow-y-auto flex-1">

                @if(session('success'))
                    <div class="mb-4 bg-green-50 text-green-700 border border-green-200 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- FILTER & SEARCH --}}
                <form method="GET" action="{{ route('products.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Cari Barang</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Kode atau nama barang..."
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                        🔍 Cari
                    </button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2">Reset</a>
                    @endif
                </form>

                {{-- TABLE --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Kode</th>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Stok</th>
                                <th class="px-6 py-3 text-left">Lokasi</th>
                                <th class="px-6 py-3 text-left">Kondisi</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-mono text-gray-700">{{ $product->product_code }}</td>
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $product->stock }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $product->storage_location }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $badgeColor = match($product->condition) {
                                                'Baik' => 'bg-green-50 text-green-700',
                                                'Rusak Ringan' => 'bg-yellow-50 text-yellow-700',
                                                'Rusak Berat' => 'bg-red-50 text-red-700',
                                                default => 'bg-gray-50 text-gray-700',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                            {{ $product->condition }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">Detail</a>
                                        @if($canManage)
                                            <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:underline">Edit</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus barang {{ $product->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada data barang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>

            </div>
        </main>
    </div>
</x-app-layout>