<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategori Barang') }}
        </h2>
    </x-slot>

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Kategori Barang</h1>
                <a href="{{ route('categories.create') }}"
                   class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                    + Tambah Kategori
                </a>
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

                <form method="GET" action="{{ route('categories.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama kategori..."
                           class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                        🔍 Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 self-center">Reset</a>
                    @endif
                </form>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama Kategori</th>
                                <th class="px-6 py-3 text-left">Jumlah Barang</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $category->products_count }}</td>
                                    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('categories.edit', $category) }}" class="text-yellow-600 hover:underline">Edit</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>

            </div>
        </main>
    </div>
</x-app-layout>