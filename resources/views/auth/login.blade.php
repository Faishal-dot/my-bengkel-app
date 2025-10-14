<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Password</label>
            <input type="password" name="password" required
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between text-sm text-gray-300">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-gray-400 bg-white/10"> Ingat saya
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-blue-300 hover:underline">Lupa password?</a>
            @endif
        </div>

        <!-- Tombol Login -->
        <button type="submit" 
            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 
                   hover:from-blue-600 hover:to-indigo-700 
                   text-white font-semibold text-lg py-3 rounded-xl shadow-lg transition">
            Masuk
        </button>

        <!-- Link ke Register -->
        <p class="text-center text-sm text-gray-300 mt-4">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-300 hover:underline">Daftar</a>
        </p>
    </form>
</x-guest-layout>