<x-guest-layout>
    <div class="min-h-screen bg-[#E11417] flex flex-col justify-center items-center relative overflow-hidden font-sans">
        
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white opacity-40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-white opacity-40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-40 h-40 bg-white opacity-30 rounded-full blur-2xl"></div>
        <div class="absolute top-10 right-10 w-40 h-40 bg-white opacity-30 rounded-full blur-2xl"></div>

        <div class="relative w-full max-w-md bg-white p-10 rounded-2xl shadow-2xl z-10 m-4">
            
            <div class="absolute -top-12 -right-6 w-28 h-28 z-20">
                <img src="{{ asset('assets/img/telkom-logo.png') }}" alt="Logo Telkom" class="w-full h-full object-contain">
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-1">Forgot password ?</h2>
            <p class="text-xs text-gray-500 mb-6">No worries, we'll send you reset instructions.</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" :value="old('email')" required autofocus placeholder="Email"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition text-sm @error('email') border-red-500 @enderror">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <button type="submit" class="w-full bg-[#E11417] hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition duration-200 text-sm shadow-md mb-4">
                    Reset password
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-gray-500 hover:underline hover:text-red-600">
                        Back to login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>