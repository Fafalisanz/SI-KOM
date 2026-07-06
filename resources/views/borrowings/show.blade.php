<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peminjaman') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
        $canManage = in_array($user->role_id, [1, 2]);

        $statusBadge = match($borrowing->status) {
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
                <h1 class="text-xl font-bold text-gray-800">Detail Peminjaman</h1>
                <a href="{{ route('borrowings.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali</a>
            </header>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-3xl">

                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $borrowing->borrower_name }}</h2>
                            <p class="text-gray-500 text-sm">Dicatat oleh: {{ $borrowing->user->name ?? '-' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusBadge[0] }}">
                            {{ $statusBadge[1] }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm mb-8">
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Tanggal Pinjam</dt>
                            <dd class="text-gray-800 font-medium mt-1">{{ $borrowing->borrow_date->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs font-medium">Tanggal Kembali</dt>
                            <dd class="text-gray-800 font-medium mt-1">
                                {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'Belum dikembalikan' }}
                            </dd>
                        </div>
                    </dl>

                    <h3 class="text-sm font-bold text-gray-700 mb-3">Daftar Barang</h3>
                    <div class="border border-gray-100 rounded-lg overflow-hidden mb-6">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2 text-left">Barang</th>
                                    <th class="px-4 py-2 text-left">Jumlah</th>
                                    <th class="px-4 py-2 text-left">Status Barang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($borrowing->details as $detail)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-800">{{ $detail->product->name ?? '-' }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $detail->qty }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $detail->item_status ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($canManage)
                        <div class="flex gap-3">
                            @if($borrowing->status !== 'returned')
                                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST"
                                      onsubmit="return confirm('Tandai barang ini sudah dikembalikan?')">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                                        Tandai Dikembalikan
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('borrowings.edit', $borrowing) }}"
                               class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition">
                                Edit Status
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </main>
    </div>
</x-app-layout>