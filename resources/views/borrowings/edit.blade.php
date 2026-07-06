<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Status Peminjaman') }}
        </h2>
    </x-slot>

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <h1 class="text-xl font-bold text-gray-800">Edit Status: {{ $borrowing->borrower_name }}</h1>
            </header>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-lg">
                    <form method="POST" action="{{ route('borrowings.update', $borrowing) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                                @foreach(['pending' => 'Pending', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'overdue' => 'Terlambat'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $borrowing->status) == $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali (opsional)</label>
                            <input type="date" name="return_date"
                                   value="{{ old('return_date', $borrowing->return_date?->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                            @error('return_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <p class="text-xs text-gray-400 mb-6">
                            Catatan: mengubah status di sini <strong>tidak</strong> otomatis menyesuaikan stok barang.
                            Untuk pengembalian normal, gunakan tombol "Kembalikan" di halaman daftar/detail.
                        </p>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('borrowings.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-[#E11417] text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>