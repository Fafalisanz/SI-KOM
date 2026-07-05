<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                </div>

                {{-- QUICK ACTIONS --}}
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
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

            </div>
        </main>

    </div>
</x-app-layout>