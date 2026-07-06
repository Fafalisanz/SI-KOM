<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Peminjaman') }}
        </h2>
    </x-slot>

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <h1 class="text-xl font-bold text-gray-800">Tambah Peminjaman</h1>
            </header>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-3xl">

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('borrowings.store') }}" id="borrowing-form">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam</label>
                                <input type="text" name="borrower_name" value="{{ old('borrower_name') }}"
                                       class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                                <input type="date" name="borrow_date" value="{{ old('borrow_date', now()->format('Y-m-d')) }}"
                                       class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>

                        <div class="mb-3 flex justify-between items-center">
                            <label class="block text-sm font-medium text-gray-700">Barang yang Dipinjam</label>
                            <button type="button" id="add-item-btn"
                                    class="text-sm text-red-600 font-semibold hover:underline">
                                + Tambah Barang
                            </button>
                        </div>

                        <div id="items-container" class="space-y-3 mb-6">
                            {{-- Baris item akan ditambahkan lewat JavaScript, mulai dari template di bawah --}}
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('borrowings.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-[#E11417] text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                                Simpan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    {{-- Data produk untuk dropdown, dikirim sebagai JSON supaya bisa dipakai JS --}}
    <script>
        const availableProducts = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'stock' => $p->stock]));
        let itemIndex = 0;

        function buildOptions() {
            return availableProducts.map(p =>
                `<option value="${p.id}">${p.name} (stok: ${p.stock})</option>`
            ).join('');
        }

        function addItemRow() {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'flex gap-3 items-start item-row';
            row.innerHTML = `
                <div class="flex-1">
                    <select name="items[${itemIndex}][product_id]" required
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Pilih Barang --</option>
                        ${buildOptions()}
                    </select>
                </div>
                <div class="w-28">
                    <input type="number" name="items[${itemIndex}][qty]" min="1" value="1" required
                           placeholder="Qty"
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                </div>
                <button type="button" onclick="this.closest('.item-row').remove()"
                        class="text-red-500 hover:text-red-700 px-2 py-2 text-sm">
                    ✕
                </button>
            `;
            container.appendChild(row);
            itemIndex++;
        }

        document.getElementById('add-item-btn').addEventListener('click', addItemRow);

        // Selalu mulai dengan minimal 1 baris
        addItemRow();
    </script>
</x-app-layout>