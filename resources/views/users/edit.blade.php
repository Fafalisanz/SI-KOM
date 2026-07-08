<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <h1 class="text-xl font-bold text-gray-800">Edit Pengguna: {{ $user->name }}</h1>
            </header>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-lg">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role_id" class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="1" @selected(old('role_id', $user->role_id) == 1)>Admin</option>
                                <option value="2" @selected(old('role_id', $user->role_id) == 2)>Staff</option>
                                <option value="3" @selected(old('role_id', $user->role_id) == 3)>Manager</option>
                            </select>
                            @if($user->id === auth()->id())
                                {{-- Select yang disabled tidak ikut terkirim, jadi kirim ulang value asli lewat hidden input --}}
                                <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                                <p class="text-xs text-gray-400 mt-1">Kamu tidak bisa mengubah role akunmu sendiri.</p>
                            @endif
                            @error('role_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                   class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                            @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
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