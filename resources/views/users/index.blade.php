<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atur Pengguna') }}
        </h2>
    </x-slot>

    @php
        $roleBadge = fn (int $roleId) => match($roleId) {
            1 => ['bg-red-50 text-red-700', 'Admin'],
            2 => ['bg-blue-50 text-blue-700', 'Staff'],
            3 => ['bg-purple-50 text-purple-700', 'Manager'],
            default => ['bg-gray-50 text-gray-700', 'Unknown'],
        };
    @endphp

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Atur Pengguna</h1>
                <a href="{{ route('users.create') }}"
                   class="bg-[#E11417] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                    + Tambah Pengguna
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

                <form method="GET" action="{{ route('users.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau email..."
                           class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                        🔍 Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 self-center">Reset</a>
                    @endif
                </form>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $u)
                                @php [$badgeColor, $badgeLabel] = $roleBadge($u->role_id); @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">
                                        {{ $u->name }}
                                        @if($u->id === auth()->id())
                                            <span class="text-xs text-gray-400">(kamu)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">{{ $u->email }}</td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                            {{ $badgeLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('users.edit', $u) }}" class="text-yellow-600 hover:underline">Edit</a>
                                        @if($u->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>
        </main>
    </div>
</x-app-layout>