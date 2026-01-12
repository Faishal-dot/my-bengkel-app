<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - Bengkel Oto</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Script Tailwind (Gunakan Vite di Production) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* CSS Animasi (Sama dengan Login) */
        .bg-fade {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            z-index: 0;
        }
        .bg-fade.active { opacity: 1; }

        @keyframes blink { 50% { border-color: transparent; } }
        .animate-blink { animation: blink 1s step-end infinite; }

        .text-blue { color: #3B82F6; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.6s ease forwards; }

        /* Custom Input Style untuk Glassmorphism */
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
    </style>
</head>
<body class="font-sans antialiased text-white">

    <div id="slideshow" class="min-h-screen flex flex-col items-center justify-center relative overflow-hidden bg-black py-10">
        
        {{-- Background Slideshow --}}
        <div id="bg1" class="bg-fade active" style="background-image: url('https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop');"></div>
        <div id="bg2" class="bg-fade"></div>

        {{-- Overlay Gelap --}}
        <div class="absolute inset-0 bg-black/60 z-0"></div>

        {{-- Tombol Kembali --}}
        <div class="absolute top-6 left-6 z-20">
            <a href="/" class="flex items-center gap-2 text-gray-300 hover:text-white transition text-sm font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        {{-- Main Container --}}
        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 flex justify-center">
            <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl fade-in">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-white drop-shadow-md">
                        Bengkel 
                        <span id="typing-text" class="inline-block border-r-4 border-blue-500 pr-1 animate-blink text-blue"></span>
                    </h1>
                    <p class="mt-2 text-gray-300 text-sm">Buat akun baru untuk memulai</p>
                </div>

                {{-- Form Register --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-5" autocomplete="off">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Lengkap Anda"
                                class="w-full pl-10 pr-4 py-3 rounded-xl glass-input placeholder-gray-500 text-sm sm:text-base">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-400 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-3 rounded-xl glass-input placeholder-gray-500 text-sm sm:text-base">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-400 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input type="password" name="password" required placeholder="Minimal 8 karakter"
                                class="w-full pl-10 pr-4 py-3 rounded-xl glass-input placeholder-gray-500 text-sm sm:text-base">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-400 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5 ml-1">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                                class="w-full pl-10 pr-4 py-3 rounded-xl glass-input placeholder-gray-500 text-sm sm:text-base">
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-2">
                        Daftar Sekarang <i data-lucide="user-plus" class="w-4 h-4"></i>
                    </button>

                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-400">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-bold transition ml-1 hover:underline">
                                Masuk disini
                            </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>

        <footer class="relative z-10 mt-8 mb-4 text-center text-xs text-gray-500">
            © {{ date('Y') }} Bengkel Oto. All rights reserved.
        </footer>
    </div>

    <script>
        // Init Icons
        lucide.createIcons();

        // 1. Background Slideshow Logic
        const backgrounds = [
            "https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682142309989-45735a64218b?q=80&w=1198&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682142300386-3f4ca97acc38?q=80&w=1230&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1661371851168-df27a954e373?q=80&w=1169&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682141766135-95d9c398babb?q=80&w=1172&auto=format&fit=crop"
        ];

        let index = 0;
        const bg1 = document.getElementById("bg1");
        const bg2 = document.getElementById("bg2");
        let activeBg = bg1;

        function changeBackground() {
            index = (index + 1) % backgrounds.length;
            const nextBg = (activeBg === bg1) ? bg2 : bg1;

            nextBg.style.backgroundImage = `url('${backgrounds[index]}')`;
            nextBg.classList.add("active");
            activeBg.classList.remove("active");
            activeBg = nextBg;
        }

        setInterval(changeBackground, 5000);

        // 2. Typing Animation Logic
        document.addEventListener("DOMContentLoaded", function () {
            const element = document.getElementById("typing-text");
            const textToType = "Oto."; 
            const typeSpeed = 200;
            const eraseSpeed = 100;
            const delayBetween = 2000;
            
            let charIndex = 0;
            let isTyping = true;

            function typeLoop() {
                if (isTyping) {
                    if (charIndex < textToType.length) {
                        element.textContent += textToType.charAt(charIndex);
                        charIndex++;
                        setTimeout(typeLoop, typeSpeed);
                    } else {
                        isTyping = false;
                        setTimeout(typeLoop, delayBetween);
                    }
                } else {
                    if (charIndex > 0) {
                        element.textContent = textToType.substring(0, charIndex - 1);
                        charIndex--;
                        setTimeout(typeLoop, eraseSpeed);
                    } else {
                        isTyping = true;
                        setTimeout(typeLoop, 500);
                    }
                }
            }
            typeLoop();
        });
    </script>
</body>
</html>