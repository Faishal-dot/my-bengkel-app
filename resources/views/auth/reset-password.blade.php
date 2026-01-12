<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Token Reset -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <!-- Password Baru -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Password Baru</label>
            <input type="password" name="password" required
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                class="mt-1 block w-full px-4 py-3 rounded-xl border shadow-sm 
                       bg-white/10 text-white placeholder-gray-400 
                       focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
        </div>

        <!-- Tombol Reset -->
        <button type="submit" 
            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 
                   hover:from-blue-600 hover:to-indigo-700 
                   text-white font-semibold text-lg py-3 rounded-xl shadow-lg transition">
            Reset Password
        </button>

        <!-- Back ke Login -->
        <p class="text-center text-sm text-gray-300 mt-4">
            Ingat password? <a href="{{ route('login') }}" class="text-blue-300 hover:underline">Masuk</a>
        </p>

    </form>
</x-guest-layout>