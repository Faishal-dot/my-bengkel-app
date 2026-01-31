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
    </style>
</head>
<body class="font-sans antialiased text-white">

    <div id="slideshow" class="min-h-screen flex flex-col items-center justify-center relative overflow-hidden bg-black">
        
        {{-- Background Slideshow --}}
        <div id="bg1" class="bg-fade active" style="background-image: url('https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop');"></div>
        <div id="bg2" class="bg-fade"></div>

        <div class="absolute inset-0 bg-black/60 z-0"></div>

        {{-- MAIN CONTENT: Di sini tempat Form Login/Reset muncul --}}
        <div class="relative z-10 w-full px-4 flex flex-col items-center">
            {{ $slot }}
        </div>

        <footer class="relative z-10 mt-8 mb-4 text-center text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} Bengkel Oto. All rights reserved.</p>
        </footer>
    </div>

    <script>
        const backgrounds = [
            "https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop",
            "https://plus.unsplash.com/premium_photo-1682142309989-45735a64218b?q=80&w=1198&auto=format&fit=crop"
        ];
        let bgIndex = 0;
        const bg1 = document.getElementById("bg1");
        const bg2 = document.getElementById("bg2");
        let activeBg = bg1;

        function changeBackground() {
            bgIndex = (bgIndex + 1) % backgrounds.length;
            const nextBg = (activeBg === bg1) ? bg2 : bg1;
            nextBg.style.backgroundImage = `url('${backgrounds[bgIndex]}')`;
            nextBg.classList.add("active");
            activeBg.classList.remove("active");
            activeBg = nextBg;
        }
        setInterval(changeBackground, 5000);
    </script>
</body>
</html>