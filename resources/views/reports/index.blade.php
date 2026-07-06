<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Inventaris') }}
        </h2>
    </x-slot>

    @php
        $statusBadge = fn (string $status) => match($status) {
            'borrowed' => ['bg-yellow-50 text-yellow-700', 'Dipinjam'],
            'returned' => ['bg-green-50 text-green-700', 'Dikembalikan'],
            'overdue' => ['bg-red-50 text-red-700', 'Terlambat'],
            default => ['bg-gray-50 text-gray-700', 'Pending'],
        };
    @endphp

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Laporan Inventaris</h1>
                <a href="{{ route('reports.export', request()->query()) }}"
                   class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>
                    Export PDF
                </a>
            </header>

            <div class="p-8 overflow-y-auto flex-1">

                {{-- FILTER TANGGAL --}}
                <form method="GET" action="{{ route('reports.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                               class="rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                        Terapkan Filter
                    </button>
                    <span class="text-xs text-gray-400 self-center">
                        (Filter ini berlaku untuk data peminjaman di bawah)
                    </span>
                </form>

                {{-- RINGKASAN --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-medium">Total Peminjaman</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_borrowings'] }}</h3>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-medium">Dikembalikan</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $summary['returned'] }}</h3>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-medium">Sedang Dipinjam</p>
                        <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $summary['borrowed'] }}</h3>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-medium">Terlambat</p>
                        <h3 class="text-2xl font-bold text-red-600 mt-1">{{ $summary['overdue'] }}</h3>
                    </div>
                </div>

                {{-- LAPORAN STOK BARANG --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-sm font-bold text-gray-800">Laporan Stok Barang</h2>
                        <span class="text-xs text-gray-400">Total {{ $summary['total_item'] }} jenis barang, {{ $summary['total_stok'] }} unit</span>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Kode</th>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Stok</th>
                                <th class="px-6 py-3 text-left">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-mono text-gray-700">{{ $product->product_code }}</td>
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $product->stock }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $product->condition }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- LAPORAN PEMINJAMAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800">
                            Laporan Peminjaman ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})
                        </h2>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Peminjam</th>
                                <th class="px-6 py-3 text-left">Barang</th>
                                <th class="px-6 py-3 text-left">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-left">Tgl Kembali</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($borrowings as $borrowing)
                                @php [$badgeColor, $badgeLabel] = $statusBadge($borrowing->status); @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $borrowing->borrower_name }}</td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $borrowing->details->pluck('product.name')->filter()->join(', ') ?: '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                            {{ $badgeLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        Tidak ada data peminjaman pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>