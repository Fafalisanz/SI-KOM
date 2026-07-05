<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Barang') }}
        </h2>
    </x-slot>

    @php $user = auth()->user(); @endphp

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Detail Barang</h1>
                <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</a>
            </header>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-3xl">

                    <div class="flex items-start gap-6 mb-6">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/120?text=No+Image' }}"
                             alt="{{ $product->name }}" class="w-28 h-28 object-cover rounded-lg border border-gray-200">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h2>
                            <p class="text-gray-500 font-mono text-sm">{{ $product->product_code }}</p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Kategori</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $product->category->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Stok</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $product->stock }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Lokasi Penyimpanan</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $product->storage_location }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Kondisi</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $product->condition }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Ditambahkan</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $product->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Terakhir Diubah</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $product->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>

                    @if(in_array($user->role_id, [1, 2]))
                        <div class="mt-8 flex gap-3">
                            <a href="{{ route('products.edit', $product) }}"
                               class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Hapus barang {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </main>
    </div>
</x-app-layout>