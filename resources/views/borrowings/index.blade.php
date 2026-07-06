<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peminjaman Barang') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
        $canManage = in_array($user->role_id, [1, 2]);

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
                <h1 class="text-xl font-bold text-gray-800">Peminjaman Barang</h1>
                @if($canManage)
                    <a href="{{ route('borrowings.create') }}"
                       class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        + Tambah Peminjaman
                    </a>
                @endif
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
                <form method="GET" action="{{ route('borrowings.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Cari Nama Peminjam</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama peminjam..."
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">Semua Status</option>
                            <option value="borrowed" @selected(request('status') == 'borrowed')>Dipinjam</option>
                            <option value="returned" @selected(request('status') == 'returned')>Dikembalikan</option>
                            <option value="overdue" @selected(request('status') == 'overdue')>Terlambat</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                        🔍 Cari
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('borrowings.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2">Reset</a>
                    @endif
                </form>

                {{-- TABLE --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Peminjam</th>
                                <th class="px-6 py-3 text-left">Barang</th>
                                <th class="px-6 py-3 text-left">Tgl Pinjam</th>
                                <th class="px-6 py-3 text-left">Tgl Kembali</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($borrowings as $borrowing)
                                @php [$badgeColor, $badgeLabel] = $statusBadge($borrowing->status); @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $borrowing->borrower_name }}</td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $borrowing->details->pluck('product.name')->filter()->join(', ') }}
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
                                    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('borrowings.show', $borrowing) }}" class="text-blue-600 hover:underline">Detail</a>
                                        @if($canManage)
                                            @if($borrowing->status !== 'returned')
                                                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Tandai barang ini sudah dikembalikan?')">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:underline">Kembalikan</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('borrowings.edit', $borrowing) }}" class="text-yellow-600 hover:underline">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada data peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $borrowings->links() }}
                </div>

            </div>
        </main>
    </div>
</x-app-layout>