<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bengkel Oto</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* FAQ Animasi */
        .faq-content {
            max-height: 0;
            overflow: hidden;
            padding-top: 0;
            padding-bottom: 0;
            transition: max-height 0.4s ease, padding 0.4s ease;
        }
        .faq-open {
            max-height: 500px; /* cukup untuk konten */
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-gray-900 via-blue-900 to-gray-800 text-white font-sans">

<!-- Navbar -->
<nav class="flex items-center justify-between px-6 py-4 bg-black/20 backdrop-blur-lg fixed w-full z-50 shadow-md">
    <div class="flex items-center gap-2">
        <i data-lucide="wrench" class="w-6 h-6 text-blue-400"></i>
        <span class="text-xl font-bold">Bengkel Oto.</span>
    </div>

    <!-- Menu Desktop -->
    <div class="space-x-4 hidden md:block">
        <a href="#layanan" class="hover:text-blue-400 transition">LAYANAN</a>
        <a href="#tentang" class="hover:text-blue-400 transition">TENTANG</a>
        <a href="#testimoni" class="hover:text-blue-400 transition">TESTIMONI</a>
        <a href="#faq" class="hover:text-blue-400 transition">FAQ</a>
        <a href="#kontak" class="hover:text-blue-400 transition">KONTAK</a>
        <a href="#lokasi" class="hover:text-blue-400 transition">LOKASI</a>
    </div>

    <!-- Auth Buttons Desktop -->
    <div class="space-x-3 hidden md:flex">
        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-blue-400 hover:bg-blue-500 hover:text-white transition">
            Masuk
        </a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-semibold">
            Daftar
        </a>
    </div>

    <!-- Hamburger -->
    <button id="menu-btn" class="md:hidden flex items-center text-blue-400">
        <i data-lucide="menu" class="w-7 h-7"></i>
    </button>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu" class="hidden fixed top-0 right-0 h-full w-2/3 bg-gray-900/95 p-6 z-40 flex flex-col gap-4">
    <button id="close-btn" class="self-end text-gray-400 hover:text-white">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>
    <a href="#layanan" class="text-white hover:text-blue-400">LAYANAN</a>
    <a href="#tentang" class="text-white hover:text-blue-400">TENTANG</a>
    <a href="#testimoni" class="text-white hover:text-blue-400">TESTIMONI</a>
    <a href="#faq" class="text-white hover:text-blue-400">FAQ</a>
    <a href="#kontak" class="text-white hover:text-blue-400">KONTAK</a>
    <a href="#lokasi" class="text-white hover:text-blue-400">LOKASI</a>
    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-blue-400 hover:bg-blue-500 hover:text-white transition text-center">
        Masuk
    </a>
    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-semibold text-center">
        Daftar
    </a>
</div>

<!-- Hero Section -->
<section class="h-screen flex flex-col items-center justify-center text-center px-6">
    <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold leading-tight drop-shadow-lg">
        Solusi Perawatan & Perbaikan <span class="text-blue-400">Kendaraan</span> Anda
    </h1>
    <p class="mt-4 text-base sm:text-lg text-gray-300 max-w-2xl">
        Bengkel Oto siap melayani servis mobil & motor dengan profesional, cepat, dan terpercaya.
    </p>
    <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <a href="{{ route('customer.booking.index') }}" 
            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 font-semibold text-white transition shadow-lg">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            Booking Sekarang
        </a>
        <a href="#layanan" class="px-6 py-3 rounded-xl border border-gray-400 hover:bg-gray-700 transition">
           Lihat Layanan
        </a>
    </div>
</section>

<!-- Services Section -->
<section id="layanan" class="py-20 px-6 bg-gray-900/60 backdrop-blur-lg">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-12">Layanan Kami</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-white/10 shadow-lg hover:scale-105 transition flex flex-col items-center text-center">
                <i data-lucide="droplets" class="w-10 h-10 text-blue-600 mb-4"></i>
                <h3 class="text-xl font-semibold">Ganti Oli</h3>
                <p class="text-gray-300 mt-2">Perawatan rutin agar mesin tetap awet & bertenaga.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/10 shadow-lg hover:scale-105 transition flex flex-col items-center text-center">
                <i data-lucide="car" class="w-10 h-10 text-blue-400 mb-4"></i>
                <h3 class="text-xl font-semibold">Servis Mobil</h3>
                <p class="text-gray-300 mt-2">Diagnosa & perbaikan menyeluruh untuk kendaraan Anda.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/10 shadow-lg hover:scale-105 transition flex flex-col items-center text-center">
                <i data-lucide="settings" class="w-10 h-10 text-blue-400 mb-4"></i>
                <h3 class="text-xl font-semibold">Tune Up</h3>
                <p class="text-gray-300 mt-2">Optimalkan performa mesin agar tetap prima.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="tentang" class="py-20 px-6">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div class="relative w-full overflow-hidden rounded-2xl shadow-lg">
            <div id="carousel" class="flex transition-transform duration-700 ease-in-out">
                <img src="https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover">
                <img src="https://plus.unsplash.com/premium_photo-1682142309989-45735a64218b?q=80&w=1198&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover">
                <img src="https://plus.unsplash.com/premium_photo-1682142300386-3f4ca97acc38?q=80&w=1230&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover">
                <img src="https://plus.unsplash.com/premium_photo-1661371851168-df27a954e373?q=80&w=1169&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover">
            </div>
        </div>
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-4">Tentang Bengkel Oto</h2>
            <p class="text-gray-300 leading-relaxed">
                Kami berdedikasi memberikan layanan servis kendaraan terbaik dengan mekanik berpengalaman
                serta peralatan modern. Kepuasan pelanggan adalah prioritas utama kami.
            </p>
            <a href="#kontak" class="mt-6 inline-block px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition font-semibold shadow-lg">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section id="testimoni" class="py-20 px-6 bg-gray-900/60 backdrop-blur-lg">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-12">Apa Kata Pelanggan?</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white/10 rounded-2xl shadow-lg">
                <p class="text-gray-300">“Servis cepat dan mekanik ramah, mobil saya kembali prima!”</p>
                <span class="block mt-4 font-semibold text-blue-400">Andi Setiawan</span>
            </div>
            <div class="p-6 bg-white/10 rounded-2xl shadow-lg">
                <p class="text-gray-300">“Booking online sangat membantu, tidak perlu antri lama.”</p>
                <span class="block mt-4 font-semibold text-blue-400">Siti Badriah</span>
            </div>
            <div class="p-6 bg-white/10 rounded-2xl shadow-lg">
                <p class="text-gray-300">“Pelayanan profesional, saya puas sekali. mantappp”</p>
                <span class="block mt-4 font-semibold text-blue-400">Budi Recing Hell</span>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-20 px-6 bg-gray-800/50 backdrop-blur-lg">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold mb-10 text-center">Pertanyaan Umum</h2>
        <div class="space-y-4">
            <div class="bg-white/10 rounded-xl shadow-lg overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle">
                    Apakah harus booking dulu sebelum datang?
                    <i data-lucide="chevron-down" class="w-5 h-5 transition-transform"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">
                    Tidak wajib, tetapi kami sarankan booking online agar Anda tidak perlu menunggu lama.
                </div>
            </div>
            <div class="bg-white/10 rounded-xl shadow-lg overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle">
                    Apa saja metode pembayaran yang tersedia?
                    <i data-lucide="chevron-down" class="w-5 h-5 transition-transform"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">
                    Kami menerima pembayaran tunai, transfer bank, dan e-wallet seperti OVO, DANA, serta GoPay.
                </div>
            </div>
            <div class="bg-white/10 rounded-xl shadow-lg overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle">
                    Apakah bengkel ini melayani servis motor juga?
                    <i data-lucide="chevron-down" class="w-5 h-5 transition-transform"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">
                    Ya, kami melayani servis mobil dan motor dengan mekanik berpengalaman.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="kontak" class="py-20 px-6 bg-gray-900/60 backdrop-blur-lg">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6">Kontak Kami</h2>
        <p class="text-gray-300 mb-8">Butuh bantuan atau informasi lebih lanjut? Hubungi kami melalui:</p>
        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="tel:+628123456789" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-blue-600 transition shadow-lg">
                <i data-lucide="phone" class="w-5 h-5"></i> +62 812 3456 789
            </a>
            <a href="mailto:info@bengkeloto.com" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-blue-600 transition shadow-lg">
                <i data-lucide="mail" class="w-5 h-5"></i> info@bengkeloto.com
            </a>
        </div>
    </div>
</section>

<!-- Location Section -->
<section id="lokasi" class="py-20 px-6">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6">Lokasi Kami</h2>
        <p class="text-gray-300 mb-8">Kunjungi bengkel kami di alamat berikut:</p>
        <p class="mb-6 text-blue-400 font-semibold">Jl. Raya Ponti, Sidoarjo</p>
        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-700">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.1875937110467!2d112.70245907476226!3d-7.444486192566541!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e148bf17a5e7%3A0x478d9769ef86ac81!2sJl.%20Raya%20Ponti%2C%20Kabupaten%20Sidoarjo%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1758509627159!5m2!1sid!2sid" 
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-6 text-center text-gray-400 text-sm border-t border-gray-700 bg-black/40">
    © {{ date('Y') }} Bengkel Oto. All rights reserved.
</footer>

<!-- Lucide Icons + Script -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // Carousel
    const carousel = document.getElementById("carousel");
    const slides = carousel.children;
    let index = 0;
    function showNextSlide() {
        index = (index + 1) % slides.length;
        carousel.style.transform = `translateX(-${index * 100}%)`;
    }
    setInterval(showNextSlide, 3000);

    // FAQ toggle buka lambat, tutup cepat
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            if (content.classList.contains('faq-open')) {
                // Tutup cepat
                content.style.transition = "max-height 0.2s ease, padding 0.2s ease";
            } else {
                // Buka lambat
                content.style.transition = "max-height 0.5s ease, padding 0.5s ease";
            }
            content.classList.toggle('faq-open');
            icon.classList.toggle('rotate-180');
        });
    });

    // Mobile menu toggle
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
    closeBtn.addEventListener('click', () => mobileMenu.classList.add('hidden'));
    document.addEventListener('click', e => {
        if (!mobileMenu.classList.contains('hidden') &&
            !mobileMenu.contains(e.target) &&
            !menuBtn.contains(e.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>
</body>
</html>