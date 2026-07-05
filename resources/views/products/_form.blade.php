@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
        <input type="text" name="product_code" value="{{ old('product_code', $product->product_code ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
        @error('product_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="category_id" class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
        <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
               class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
        @error('stock') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penyimpanan</label>
        <input type="text" name="storage_location" value="{{ old('storage_location', $product->storage_location ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
        @error('storage_location') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Barang</label>
        <select name="condition" class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
            @foreach(['Baik', 'Rusak Ringan', 'Rusak Berat'] as $option)
                <option value="{{ $option }}" @selected(old('condition', $product->condition ?? '') == $option)>
                    {{ $option }}
                </option>
            @endforeach
        </select>
        @error('condition') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Barang (opsional)</label>
        <input type="file" name="image" accept="image/*"
               class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer">
        @error('image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

        @if(!empty($product->image))
            <div class="mt-3">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="w-24 h-24 object-cover rounded-lg border border-gray-200">
            </div>
        @endif
    </div>

</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
        Batal
    </a>
    <button type="submit" class="bg-[#E11417] text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
        Simpan
    </button>
</div>