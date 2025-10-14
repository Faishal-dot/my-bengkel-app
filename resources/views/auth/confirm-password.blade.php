<x-guest-layout>
    <div class="max-w-md w-full bg-white/10 backdrop-blur-xl p-8 rounded-2xl shadow-2xl mx-auto">
        <h1 class="text-2xl font-bold text-center text-white">Konfirmasi Password</h1>
        <p class="text-center text-gray-300 text-sm mt-2">
            Ini adalah area aman, silakan masukkan password Anda untuk melanjutkan.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-5" autocomplete="off">
            @csrf

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-200">Password</label>
                <input type="password" name="password" required autocomplete="new-password"
                    class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                           bg-white/10 text-white placeholder-gray-400 
                           focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                @error('password')
                    <span class="text-red-400 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 
                       hover:from-blue-600 hover:to-indigo-700 
                       text-white font-semibold text-lg py-3 rounded-xl shadow-lg transition">
                Konfirmasi
            </button>

            <!-- Kembali ke login -->
            <p class="text-center text-sm text-gray-300 mt-4">
                <a href="{{ route('login') }}" class="text-blue-300 hover:underline">Kembali ke Login</a>
            </p>
        </form>
    </div>
</x-guest-layout>