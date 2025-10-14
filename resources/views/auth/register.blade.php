<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5" autocomplete="off">
        @csrf

        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Nama Lengkap</label>
            <input type="text" name="name" autocomplete="off" required autofocus
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Email</label>
            <input type="email" name="email" autocomplete="off" required
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Password</label>
            <input type="password" name="password" autocomplete="new-password" required
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" autocomplete="new-password" required
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
        </div>

        <!-- Tombol Register -->
        <button type="submit" 
            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 
                   hover:from-blue-600 hover:to-indigo-700 
                   text-white font-semibold text-lg py-3 rounded-xl shadow-lg transition">
            Daftar
        </button>

        <!-- Link ke Login -->
        <p class="text-center text-sm text-gray-300 mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-300 hover:underline">Masuk</a>
        </p>
    </form>
</x-guest-layout>