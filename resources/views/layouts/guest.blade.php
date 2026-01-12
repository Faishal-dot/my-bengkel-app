<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bengkel Oto</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Background Fade */
        .bg-fade {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            z-index: 0;
        }
        .bg-fade.active {
            opacity: 1;
        }

        /* Animasi Kursor Kedip */
        @keyframes blink {
            50% { border-color: transparent; }
        }
        .animate-blink {
            animation: blink 1s step-end infinite;
        }

        /* Warna Custom */
        .text-blue {
            color: #3B82F6;
        }

        /* Animasi Fade In untuk Text */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
    </style>
</head>
<body class="font-sans antialiased text-white">

    <div id="slideshow" class="min-h-screen flex flex-col items-center justify-center relative overflow-hidden bg-black">
        
        {{-- Background Images Slideshow --}}
        <div id="bg1" class="bg-fade active" style="background-image: url('https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop');"></div>
        <div id="bg2" class="bg-fade"></div>

        {{-- Dark Overlay --}}
        <div class="absolute inset-0 bg-black/60 z-0"></div>

        {{-- Main Content Container (Glassmorphism) --}}
        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            
            <div class="w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/10 rounded-2xl p-8 shadow-2xl transform transition-all hover:scale-[1.01]">
                
                {{-- Logo / Title Section --}}
                <div class="text-center mb-8">
                    {{-- Icon Bengkel (Optional) --}}
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>

                    <h1 class="text-3xl font-extrabold text-white tracking-tight">
                        Bengkel 
                        <span id="typing-text" class="inline-block text-blue-400 border-r-4 border-blue-400 pr-1 animate-blink"></span>
                    </h1>
                    <p class="mt-3 text-gray-300 text-sm sm:text-base">
                        Solusi terpercaya untuk perawatan kendaraan Anda.
                    </p>
                </div>

                {{-- Auth Buttons Section --}}
                <div class="space-y-4">
                    @if (Route::has('login'))
                        @auth
                            {{-- Tombol Dashboard jika sudah login --}}
                            <a href="{{ url('/dashboard') }}" 
                               class="block w-full text-center py-3 px-4 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-all duration-300 shadow-[0_0_15px_rgba(59,130,246,0.5)] hover:shadow-[0_0_25px_rgba(59,130,246,0.7)]">
                                Menuju Dashboard
                            </a>
                        @else
                            {{-- Tombol Login --}}
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center py-3 px-4 rounded-lg bg-white text-gray-900 hover:bg-gray-100 font-bold transition-all duration-300">
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                {{-- Tombol Register --}}
                                <a href="{{ route('register') }}" 
                                   class="block w-full text-center py-3 px-4 rounded-lg border border-white/30 hover:bg-white/10 text-white font-semibold transition-all duration-300 backdrop-blur-sm">
                                    Daftar Akun Baru
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <footer class="relative z-10 mt-8 mb-4 text-center text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} Bengkel Oto. All rights reserved.</p>
        </footer>
    </div>

    {{-- Javascript Logic (Background & Typing) --}}
    <script>
        // 1. Background Slideshow Logic
        const backgrounds = [
            "https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682142309989-45735a64218b?q=80&w=1198&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682142300386-3f4ca97acc38?q=80&w=1230&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1661371851168-df27a954e373?q=80&w=1169&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682141766135-95d9c398babb?q=80&w=1172&auto=format&fit=crop"
        ];

        let bgIndex = 0;
        const bg1 = document.getElementById("bg1");
        const bg2 = document.getElementById("bg2");
        let activeBg = bg1;

        function changeBackground() {
            bgIndex = (bgIndex + 1) % backgrounds.length;
            const nextBg = (activeBg === bg1) ? bg2 : bg1;

            // Set image to the hidden div
            nextBg.style.backgroundImage = `url('${backgrounds[bgIndex]}')`;
            
            // Fade in the new div, fade out the old one
            nextBg.classList.add("active");
            activeBg.classList.remove("active");
            
            // Swap references
            activeBg = nextBg;
        }

        // Ganti background setiap 5 detik
        setInterval(changeBackground, 5000);


        // 2. Typing Animation Logic
        document.addEventListener("DOMContentLoaded", function () {
            const element = document.getElementById("typing-text");
            const textToType = "Oto."; // Text yang akan diketik
            const typeSpeed = 200;
            const eraseSpeed = 100;
            const delayBeforeErase = 2000;
            const delayBeforeType = 500;
            
            let charIndex = 0;
            let isTyping = true;

            function typeWriter() {
                if (isTyping) {
                    // Typing...
                    if (charIndex < textToType.length) {
                        element.textContent += textToType.charAt(charIndex);
                        charIndex++;
                        setTimeout(typeWriter, typeSpeed);
                    } else {
                        // Selesai ngetik, tunggu sebentar lalu hapus
                        isTyping = false;
                        setTimeout(typeWriter, delayBeforeErase);
                    }
                } else {
                    // Erasing...
                    if (charIndex > 0) {
                        element.textContent = textToType.substring(0, charIndex - 1);
                        charIndex--;
                        setTimeout(typeWriter, eraseSpeed);
                    } else {
                        // Selesai hapus, mulai ngetik lagi
                        isTyping = true;
                        setTimeout(typeWriter, delayBeforeType);
                    }
                }
            }

            // Mulai animasi
            setTimeout(typeWriter, 500);
        });
    </script>
</body>
</html>