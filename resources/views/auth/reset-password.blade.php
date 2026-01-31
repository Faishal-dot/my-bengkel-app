<x-guest-layout>
    {{-- Script Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Custom Input Style untuk Glassmorphism agar sama dengan Register */
        .glass-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #3B82F6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
        .fade-in { animation: fadeIn 0.6s ease forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    {{-- Main Container --}}
    <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl fade-in">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white drop-shadow-md">
                Bengkel 
                <span id="typing-text-reset" class="inline-block border-r-4 border-blue-500 pr-1 animate-blink text-blue"></span>
            </h1>
            <p class="mt-2 text-gray-300 text-sm">Atur ulang kata sandi akun Anda</p>
        </div>

        {{-- Form Reset Password --}}
        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            {{-- Password Reset Token --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email Field (Readonly) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                        class="w-full pl-10 pr-4 py-3 rounded-xl glass-input opacity-70 cursor-not-allowed text-sm sm:text-base">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 ml-1" />
            </div>

            {{-- Password Baru --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Kata Sandi Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="password" required autofocus autocomplete="new-password" placeholder="Minimal 8 karakter"
                        class="w-full pl-10 pr-4 py-3 rounded-xl glass-input text-sm sm:text-base">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 ml-1" />
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi"
                        class="w-full pl-10 pr-4 py-3 rounded-xl glass-input text-sm sm:text-base">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-400 ml-1" />
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                Simpan Perubahan <i data-lucide="save" class="w-4 h-4"></i>
            </button>
        </form>
    </div>

    <script>
        // Jalankan Ikon Lucide
        lucide.createIcons();

        // Animasi Mengetik khusus untuk halaman Reset
        document.addEventListener("DOMContentLoaded", function () {
            const element = document.getElementById("typing-text-reset");
            const textToType = "Oto."; 
            let charIndex = 0;
            let isTyping = true;

            function typeLoop() {
                if (isTyping) {
                    if (charIndex < textToType.length) {
                        element.textContent += textToType.charAt(charIndex);
                        charIndex++;
                        setTimeout(typeLoop, 200);
                    } else {
                        isTyping = false;
                        setTimeout(typeLoop, 2000);
                    }
                } else {
                    if (charIndex > 0) {
                        element.textContent = textToType.substring(0, charIndex - 1);
                        charIndex--;
                        setTimeout(typeLoop, 100);
                    } else {
                        isTyping = true;
                        setTimeout(typeLoop, 500);
                    }
                }
            }
            typeLoop();
        });
    </script>
</x-guest-layout>