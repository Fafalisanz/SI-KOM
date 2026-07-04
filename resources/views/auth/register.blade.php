<x-guest-layout>
    <div class="min-h-screen bg-[#E11417] flex flex-col justify-center items-center relative overflow-hidden font-sans">
        
        <!-- Ornamen lingkaran putih blur di sudut-sudut -->
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white opacity-40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-white opacity-40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-40 h-40 bg-white opacity-30 rounded-full blur-2xl"></div>
        <div class="absolute top-10 right-10 w-40 h-40 bg-white opacity-30 rounded-full blur-2xl"></div>

        <!-- Kartu Form Register -->
        <div class="relative w-full max-w-md bg-white p-10 rounded-2xl shadow-2xl z-10 m-4">
            
            <!-- Posisi Logo Telkom -->
            <div class="absolute -top-12 -right-6 w-28 h-28 z-20">
                <img src="{{ asset('assets/img/telkom-logo.png') }}" alt="Logo Telkom" class="w-full h-full object-contain">
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-6">Register</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" :value="old('name')" required autofocus placeholder="Masukkan Nama Lengkap"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition text-sm @error('name') border-red-500 @enderror">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" :value="old('email')" required placeholder="Masukkan email anda"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition text-sm @error('email') border-red-500 @enderror">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Kata Sandi -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Masukkan kata sandi"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition text-sm @error('password') border-red-500 @enderror">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi kata sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition text-sm">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <!-- Tombol Daftar (Merah) -->
                <button type="submit" class="w-full bg-[#E11417] hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition duration-200 text-sm shadow-md mb-4">
                    Daftar
                </button>

                <div class="text-center">
                    <span class="text-xs text-gray-500">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-[#E11417] font-bold hover:underline">Login</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>