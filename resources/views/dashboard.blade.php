<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();

        $statusBadge = fn (string $status) => match($status) {
            'borrowed' => ['bg-yellow-50 text-yellow-700', 'Dipinjam'],
            'returned' => ['bg-green-50 text-green-700', 'Dikembalikan'],
            'overdue' => ['bg-red-50 text-red-700', 'Terlambat'],
            default => ['bg-gray-50 text-gray-700', 'Pending'],
        };
    @endphp

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Dashboard Overview</h1>
                <div class="text-sm text-gray-500">Hari ini: {{ now()->format('d M Y') }}</div>
            </header>

            <div class="p-8 overflow-y-auto flex-1">

                {{-- CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium uppercase">Total Barang</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBarang }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 text-red-600 rounded-lg text-2xl">📦</div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium uppercase">Barang Tersedia</p>
                            <h3 class="text-3xl font-bold text-gray-700 mt-1">{{ $barangTersedia }}</h3>
                        </div>
                        <div class="p-3 bg-green-50 text-green-600 rounded-lg text-2xl">✅</div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium uppercase">Barang Dipinjam</p>
                            <h3 class="text-3xl font-bold text-gray-700 mt-1">{{ $barangDipinjam }}</h3>
                        </div>
                        <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg text-2xl">🔄</div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium uppercase">Kategori</p>
                            <h3 class="text-3xl font-bold text-gray-700 mt-1">{{ $totalKategori }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg text-2xl">📂</div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border {{ $stokMenipis > 0 ? 'border-red-200 ring-1 ring-red-100' : 'border-gray-100' }} flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400 font-medium uppercase">Stok Menipis</p>
                            <h3 class="text-3xl font-bold {{ $stokMenipis > 0 ? 'text-red-600' : 'text-gray-700' }} mt-1">{{ $stokMenipis }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 text-red-600 rounded-lg text-2xl">⚠️</div>
                    </div>
                </div>

                {{-- QUICK ACTIONS --}}
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Aktivitas Cepat Sistem</h2>

                        <div>
                            @if($user->role_id == 1)
                                <a href="{{ route('products.create') }}" class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold mr-2 hover:bg-red-700 transition inline-block">
                                    + Tambah Barang Baru
                                </a>
                                <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                                    ⚙️ Atur Pengguna
                                </button>
                            @elseif($user->role_id == 2)
                                <a href="{{ route('products.create') }}" class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition inline-block">
                                    + Tambah Barang Baru
                                </a>
                            @elseif($user->role_id == 3)
                                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                                    🖨️ Cetak Laporan Bulanan
                                </button>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed">
                        Selamat datang di Panel Manajemen Sistem Inventaris PT Telkomsel. Gunakan menu navigasi di sebelah kiri untuk mengelola data master barang, melakukan pencatatan transaksi peminjaman logistik kantor, serta memantau status kondisi aset terkini secara waktu nyata (<em>real-time</em>).
                    </p>
                </div>

                {{-- GRAFIK PEMINJAMAN PER BULAN --}}
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Grafik Peminjaman per Bulan ({{ now()->year }})</h2>
                    <canvas id="borrowingChart" height="90"></canvas>
                </div>

                {{-- AKTIVITAS TERBARU --}}
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Aktivitas Terbaru</h2>

                    <div class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left">Peminjam</th>
                                    <th class="px-4 py-3 text-left">Barang</th>
                                    <th class="px-4 py-3 text-left">Tanggal</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($aktivitasTerbaru as $aktivitas)
                                    @php [$badgeColor, $badgeLabel] = $statusBadge($aktivitas->status); @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $aktivitas->borrower_name }}</td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $aktivitas->details->pluck('product.name')->filter()->join(', ') ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $aktivitas->borrow_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                                {{ $badgeLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                            Belum ada aktivitas peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

    </div>

    {{-- Chart.js via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('borrowingChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($chartData),
                    backgroundColor: '#E11417',
                    borderRadius: 6,
                    maxBarThickness: 40,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                    },
                },
            }
        });
    </script>
</x-app-layout>