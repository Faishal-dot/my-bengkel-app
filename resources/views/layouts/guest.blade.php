<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-fade {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }
        .bg-fade.active {
            opacity: 1;
        }

        @keyframes blink {
            50% { border-color: transparent; }
        }
        .animate-blink {
            animation: blink 1s step-end infinite;
        }

        .text-blue {
            color: #3B82F6;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
    </style>
</head>
<body class="font-sans antialiased">

    <!-- Background wrapper -->
    <div id="slideshow" class="min-h-screen flex flex-col items-center justify-center relative overflow-hidden bg-black">
        
        <!-- Layer background (fade effect) -->
        <div id="bg1" class="bg-fade active" style="background-image: url('https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop');"></div>
        <div id="bg2" class="bg-fade"></div>

        <!-- Overlay gelap -->
        <div class="absolute inset-0 bg-black/50"></div>

        <!-- Konten utama -->
        <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8">
            <div class="mx-auto w-full 
                        max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg
                        bg-white/10 backdrop-blur-lg 
                        rounded-xl p-4 sm:p-6 lg:p-8 shadow-lg">

                <!-- Judul -->
                <div class="text-center mb-6">
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-white drop-shadow">
                        Bengkel 
                        <span id="typing-text" 
                              class="inline-block border-r-4 border-white pr-2 animate-blink text-blue">
                        </span>
                    </h1>
                    <p class="mt-2 text-gray-200 text-sm sm:text-base md:text-lg">
                        Selamat datang, silakan masuk atau daftar
                    </p>
                </div>

                <!-- Slot Form (Login/Register) -->
                <div class="space-y-4 text-sm sm:text-base md:text-lg text-white">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="relative z-10 mt-6 mb-4 text-center text-xs sm:text-sm md:text-base text-gray-200">
            © {{ date('Y') }} Bengkel Oto. All rights reserved.
        </footer>
    </div>

    <!-- Script fade background & animasi teks -->
    <script>
        // ==== Background slideshow ====
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

        // ==== Animasi teks "Oto" ====
        document.addEventListener("DOMContentLoaded", function () {
            const element = document.getElementById("typing-text");
            const text = "Oto";
            const speed = 150;
            const eraseSpeed = 100;
            const delayBetween = 1000;
            let index = 0;
            let typing = true;

            function typeLoop() {
                if (typing) {
                    if (index < text.length) {
                        element.textContent += text.charAt(index);
                        element.classList.add("fade-in");
                        index++;
                        setTimeout(typeLoop, speed);
                    } else {
                        typing = false;
                        setTimeout(typeLoop, delayBetween);
                    }
                } else {
                    if (index > 0) {
                        element.textContent = text.substring(0, index - 1);
                        index--;
                        setTimeout(typeLoop, eraseSpeed);
                    } else {
                        typing = true;
                        setTimeout(typeLoop, delayBetween);
                    }
                }
            }

            element.textContent = "";
            typeLoop();
        });
    </script>
</body>
</html>
