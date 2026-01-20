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
        html {
    /* Smooth scroll bawaan browser yang paling stabil */
    scroll-behavior: smooth;
    /* Sesuaikan angka 90px dengan tinggi navbar kamu */
    scroll-padding-top: 90px; 
}

/* Pastikan section punya layout yang jelas */
section {
    position: relative;
}

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; } 
        ::-webkit-scrollbar-thumb { background-color: #3b82f6; border-radius: 10px; border: 2px solid #0f172a; }
        ::-webkit-scrollbar-thumb:hover { background-color: #2563eb; }
        * { scrollbar-width: thin; scrollbar-color: #3b82f6 #0f172a; }

        /* --- PERBAIKAN BACKGROUND (SAMA RATA BIRU GELAP) --- */
        html, body { 
            height: 100%; 
            margin: 0; 
            padding: 0; 
            /* Background dibuat fixed (tetap) agar warna rata dan tidak belang saat scroll */
            background-color: #0f172a; 
            background-image: radial-gradient(circle at 50% 0%, #1e3a8a 0%, #0f172a 50%, #020617 100%);
            background-attachment: fixed; 
            background-size: cover;
            overflow-x: hidden; 
            font-family: 'Figtree', sans-serif; 
        }

        .fade-slide { opacity: 0; transform: translateY(20px); transition: all .6s ease-out; }
        .fade-slide.appear { opacity: 1; transform: translateY(0); }
        .fade-zoom { opacity: 0; transform: scale(0.95); transition: all .6s ease-out; }
        .fade-zoom.appear { opacity: 1; transform: scale(1); }

        .faq-content { max-height: 0; overflow: hidden; padding-top: 0; padding-bottom: 0; transition: max-height 0.4s ease, padding 0.4s ease; }
        .faq-open { max-height: 500px; padding-top: 0.5rem; padding-bottom: 0.5rem; }

        /* ANIMASI NAVBAR */
        .nav-link { 
            transition: color 0.3s ease; 
            position: relative; 
            padding-bottom: 5px; 
            color: #cbd5e1; 
            font-weight: 500;
        }
        .nav-link::after { 
            content: ""; 
            position: absolute; 
            left: 0; 
            bottom: 0; 
            width: 0; 
            height: 2px; 
            background: #3b82f6; 
            transition: width 0.3s ease; 
        }
        .nav-link:hover { color: #60a5fa; }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active { color: #3b82f6; font-weight: 700; }
        .nav-link.active::after { width: 100%; }

        @media (max-width: 767px) { .nav-link::after { width: 80%; } }
        section { scroll-margin-top: 84px; }

        .hero-wave span {
            display: inline-block;
            animation: wave 1.2s infinite;
        }
        @keyframes wave {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-12px); }
        }

        /* ANIMASI CARD LAYANAN */
        .service-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .service-card:hover {
            transform: translateY(-15px);
            background: rgba(255, 255, 255, 0.15);
            border-color: #3b82f6;
            box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.3);
        }
        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(10deg);
            color: #60a5fa;
        }
    </style>
</head>

<body class="antialiased text-white selection:bg-blue-500 selection:text-white">

<nav class="flex items-center justify-between px-6 py-4 bg-[#0f172a]/80 backdrop-blur-lg fixed w-full z-50 shadow-md border-b border-white/5">
    <div class="flex items-center gap-2">
        <i data-lucide="wrench" class="w-6 h-6 text-blue-400"></i>
        <span class="text-xl font-bold">Bengkel Oto.</span>
    </div>

    <div class="space-x-6 hidden md:block">
        <a href="#layanan" class="nav-link">LAYANAN</a>
        <a href="#tentang" class="nav-link">TENTANG</a>
        <a href="#testimoni" class="nav-link">TESTIMONI</a>
        <a href="#faq" class="nav-link">FAQ</a>
        <a href="#kontak" class="nav-link">KONTAK</a>
        <a href="#lokasi" class="nav-link">LOKASI</a>
    </div>

    <div class="space-x-3 hidden md:flex">
        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-blue-400 text-blue-400 hover:bg-blue-500 hover:text-white transition font-medium">Masuk</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-semibold text-white shadow-lg shadow-blue-900/50">Daftar</a>
    </div>

    <button id="menu-btn" class="md:hidden flex items-center text-blue-400">
        <i data-lucide="menu" class="w-7 h-7"></i>
    </button>
</nav>

<div id="mobile-menu" class="hidden fixed top-0 right-0 h-full w-2/3 bg-gray-900/95 p-6 z-40 flex flex-col gap-4 border-l border-gray-800">
    <button id="close-btn" class="self-end text-gray-400 hover:text-white">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>
    <a href="#layanan" class="nav-link text-lg">LAYANAN</a>
    <a href="#tentang" class="nav-link text-lg">TENTANG</a>
    <a href="#testimoni" class="nav-link text-lg">TESTIMONI</a>
    <a href="#faq" class="nav-link text-lg">FAQ</a>
    <a href="#kontak" class="nav-link text-lg">KONTAK</a>
    <a href="#lokasi" class="nav-link text-lg">LOKASI</a>
    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-blue-400 hover:bg-blue-500 transition text-center mt-4">Masuk</a>
    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-semibold text-center">Daftar</a>
</div>

<section class="h-screen flex flex-col items-center justify-center text-center px-6 fade-slide">
    <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold leading-tight drop-shadow-lg hero-wave">
        Solusi Perawatan & Perbaikan <span class="text-blue-400">Kendaraan</span> Anda
    </h1>
    <p class="mt-4 text-base sm:text-lg text-gray-300 max-w-2xl">
        Bengkel Oto siap melayani servis mobil dengan profesional, cepat, dan terpercaya.
    </p>
    <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <a href="{{ route('customer.booking.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 font-semibold transition shadow-lg hover:shadow-blue-500/50">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            Booking Sekarang
        </a>
        <a href="#layanan" class="px-6 py-3 rounded-xl border border-gray-400 hover:bg-white/10 transition">Lihat Layanan</a>
    </div>
</section>

<section id="layanan" class="py-20 px-6 bg-black/20 backdrop-blur-lg">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-12 fade-slide">Layanan Unggulan Kami</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="service-card p-8 rounded-2xl bg-white/5 flex flex-col items-center text-center fade-zoom group cursor-default">
                <div class="p-4 bg-blue-900/30 rounded-full mb-6 service-icon transition-transform duration-300">
                    <i data-lucide="droplets" class="w-10 h-10 text-blue-500"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Ganti Oli</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Perawatan rutin dengan oli berkualitas tinggi agar mesin tetap awet & bertenaga.</p>
            </div>
            
            <div class="service-card p-8 rounded-2xl bg-white/5 flex flex-col items-center text-center fade-zoom group cursor-default">
                <div class="p-4 bg-blue-900/30 rounded-full mb-6 service-icon transition-transform duration-300">
                    <i data-lucide="car" class="w-10 h-10 text-blue-500"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Servis Mobil</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Diagnosa komputer & perbaikan menyeluruh untuk segala jenis kerusakan mobil Anda.</p>
            </div>
            
            <div class="service-card p-8 rounded-2xl bg-white/5 flex flex-col items-center text-center fade-zoom group cursor-default">
                <div class="p-4 bg-blue-900/30 rounded-full mb-6 service-icon transition-transform duration-300">
                    <i data-lucide="settings" class="w-10 h-10 text-blue-500"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Tune Up</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Optimalkan performa mesin, bersihkan kerak karbon, dan kembalikan efisiensi bahan bakar.</p>
            </div>
        </div>
    </div>
</section>

<section id="tentang" class="py-20 px-6 fade-slide border-y border-gray-800 bg-black/10">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div class="relative w-full overflow-hidden rounded-2xl shadow-lg border border-gray-700">
            <div id="carousel" class="flex transition-transform duration-700 ease-in-out">
                <img src="https://plus.unsplash.com/premium_photo-1682142297775-c73dfbaf8a79?q=80&w=1170&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover h-64 md:h-80">
                <img src="https://plus.unsplash.com/premium_photo-1682142309989-45735a64218b?q=80&w=1198&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover h-64 md:h-80">
                <img src="https://plus.unsplash.com/premium_photo-1682142300386-3f4ca97acc38?q=80&w=1230&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover h-64 md:h-80">
                <img src="https://plus.unsplash.com/premium_photo-1661371851168-df27a954e373?q=80&w=1169&auto=format&fit=crop" class="w-full flex-shrink-0 object-cover h-64 md:h-80">
            </div>
        </div>
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-4">Tentang Bengkel Oto</h2>
            <p class="text-gray-300 leading-relaxed text-lg">
                Kami berdedikasi memberikan layanan terbaik dengan mekanik berpengalaman serta peralatan modern. Kepuasan pelanggan adalah prioritas utama kami dalam setiap pengerjaan.
            </p>
            <a href="#kontak" class="mt-8 inline-block px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition font-semibold shadow-lg hover:shadow-blue-500/20">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

<section id="testimoni" class="py-20 overflow-hidden relative">
    <style>
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); } 
        }
        .animate-scroll {
            display: flex;
            width: max-content; 
            animation: scroll 30s linear infinite; 
        }
        .animate-scroll:hover {
            animation-play-state: paused;
        }
    </style>

    <div class="max-w-7xl mx-auto text-center px-6 relative z-10">
        <h2 class="text-2xl sm:text-3xl font-bold mb-12 text-white">Apa Kata Pelanggan?</h2>
    </div>

@if($testimonials->count() > 0)
    <div class="relative w-full">
        {{-- Jika data <= 3, kita hilangkan animasi 'animate-scroll' agar tidak terlihat aneh --}}
        <div class="{{ $testimonials->count() > 3 ? 'animate-scroll' : 'flex justify-center' }} gap-6 px-6">
            
            {{-- Loop pertama (Data Asli) --}}
            @foreach($testimonials as $t)
                <div class="w-[300px] sm:w-[350px] flex-shrink-0 p-6 bg-white/5 rounded-2xl shadow-lg border border-white/5 flex flex-col items-center text-center transition-transform hover:scale-105 hover:bg-white/10 cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-xl shadow-md mb-3 ring-2 ring-blue-400/30">
                        {{ strtoupper(substr($t->user->name, 0, 1)) }}
                    </div>
                    <p class="text-blue-400 font-semibold text-lg mb-1 truncate w-full">
                        {{ $t->user->name }}
                    </p>
                    <div class="flex justify-center gap-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="star"
                            class="w-4 h-4 {{ $i <= $t->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-600 fill-gray-600/30' }}">
                            </i>
                        @endfor
                    </div>
                    <p class="text-gray-300 text-sm italic leading-relaxed line-clamp-4">
                        “{{ $t->message }}”
                    </p>
                </div>
            @endforeach

            {{-- Loop kedua (Data Kloning) HANYA muncul jika data > 3 untuk keperluan animasi infinite --}}
            @if($testimonials->count() > 3)
                @foreach($testimonials as $t)
                    <div class="w-[300px] sm:w-[350px] flex-shrink-0 p-6 bg-white/5 rounded-2xl shadow-lg border border-white/5 flex flex-col items-center text-center transition-transform hover:scale-105 hover:bg-white/10 cursor-pointer">
                        {{-- Isi kartu sama persis dengan di atas --}}
                        <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-xl shadow-md mb-3 ring-2 ring-blue-400/30">
                            {{ strtoupper(substr($t->user->name, 0, 1)) }}
                        </div>
                        <p class="text-blue-400 font-semibold text-lg mb-1 truncate w-full">{{ $t->user->name }}</p>
                        <div class="flex justify-center gap-1 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $t->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-600 fill-gray-600/30' }}"></i>
                            @endfor
                        </div>
                        <p class="text-gray-300 text-sm italic leading-relaxed line-clamp-4">“{{ $t->message }}”</p>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
@else
    <div class="text-center pb-10">
        <p class="text-gray-300 italic">Belum ada testimoni dari pelanggan.</p>
    </div>
@endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
</section>

<section id="faq" class="py-20 px-6 bg-black/10 fade-slide">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold mb-10 text-center">Pertanyaan Umum</h2>
        <div class="space-y-4">
            <div class="bg-white/5 rounded-xl border border-white/5 overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle hover:bg-white/5 transition">
                    Apakah harus booking dulu?
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">Tidak wajib, tapi sangat disarankan untuk menghindari antrian.</div>
            </div>
            <div class="bg-white/5 rounded-xl border border-white/5 overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle hover:bg-white/5 transition">
                    Metode pembayaran?
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">Kami menerima Tunai, Transfer Bank, dan QRIS.</div>
            </div>
            <div class="bg-white/5 rounded-xl border border-white/5 overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle hover:bg-white/5 transition">
                    Bisa servis motor?
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">Sayang nya tidak, kami hanya melayani servis mobil.</div>
            </div>
        </div>
    </div>
</section>

<section id="kontak" class="py-20 px-6 fade-slide">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6">Kontak Kami</h2>
        <p class="text-gray-300 mb-8">Butuh bantuan? Hubungi kami melalui:</p>
        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="tel:+628123456789" class="flex items-center gap-3 px-6 py-4 rounded-xl bg-white/5 hover:bg-blue-600 transition shadow-lg border border-white/5 group">
                <div class="p-2 bg-white/10 rounded-full group-hover:bg-white/20 transition">
                    <i data-lucide="phone" class="w-5 h-5"></i> 
                </div>
                <span class="font-semibold">+62 812 3456 789</span>
            </a>
            <a href="mailto:info@bengkeloto.com" class="flex items-center gap-3 px-6 py-4 rounded-xl bg-white/5 hover:bg-blue-600 transition shadow-lg border border-white/5 group">
                <div class="p-2 bg-white/10 rounded-full group-hover:bg-white/20 transition">
                    <i data-lucide="mail" class="w-5 h-5"></i> 
                </div>
                <span class="font-semibold">info@bengkeloto.com</span>
            </a>
        </div>
    </div>
</section>

<section id="lokasi" class="py-20 px-6 fade-slide bg-black/10">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-10">Lokasi Kami</h2>
        <div class="p-8 bg-white/5 rounded-2xl shadow-lg backdrop-blur-md border border-white/5 hover:border-blue-500/50 transition duration-300">
            <i data-lucide="map-pin" class="w-8 h-8 text-blue-500 mx-auto mb-4"></i>
            <span class="block font-semibold text-lg md:text-xl">Jl. Otomotif No. 88, Mekar Jaya, Kota Auto, Indonesia</span>
            <a href="https://maps.google.com" target="_blank" class="inline-block mt-4 text-blue-400 text-sm hover:underline">Lihat di Google Maps</a>
</section>

<footer class="bg-[#0b1120] pt-16 pb-8 border-t border-gray-800 text-sm">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            {{-- Kolom 1: Brand & Desc --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="wrench" class="w-5 h-5 text-blue-500"></i>
                    <span class="text-xl font-bold text-white">Bengkel Oto.</span>
                </div>
                <p class="text-gray-400 leading-relaxed mb-6">
                    Solusi terpercaya untuk perawatan kendaraan Anda. Teknisi handal, harga transparan, dan pelayanan prima.
                </p>
                {{-- Social Media Icons --}}
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Link Cepat --}}
            <div>
                <h3 class="text-white font-bold text-lg mb-6">Menu Navigasi</h3>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="#layanan" class="hover:text-blue-400 transition">Layanan Kami</a></li>
                    <li><a href="#tentang" class="hover:text-blue-400 transition">Tentang Kami</a></li>
                    <li><a href="#testimoni" class="hover:text-blue-400 transition">Ulasan Pelanggan</a></li>
                    <li><a href="{{ route('customer.booking.index') }}" class="hover:text-blue-400 transition">Booking Servis</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div>
                <h3 class="text-white font-bold text-lg mb-6">Hubungi Kami</h3>
                <ul class="space-y-4 text-gray-400">
                    <li class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-blue-500 mt-0.5"></i>
                        <span>Jl. Otomotif No. 88, Mekar Jaya,<br>Kota Auto, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-5 h-5 text-blue-500"></i>
                        <span>+62 812 3456 789</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-5 h-5 text-blue-500"></i>
                        <span>info@bengkeloto.com</span>
                    </li>
                </ul>
            </div>

            {{-- Kolom 4: Jam Operasional --}}
            <div>
                <h3 class="text-white font-bold text-lg mb-6">Jam Buka</h3>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex justify-between border-b border-gray-800 pb-2">
                        <span>Senin - Jumat</span>
                        <span class="text-white">08:00 - 17:00</span>
                    </li>
                    <li class="flex justify-between border-b border-gray-800 pb-2">
                        <span>Sabtu</span>
                        <span class="text-white">08:00 - 15:00</span>
                    </li>
                    <li class="flex justify-between border-b border-gray-800 pb-2">
                        <span>Minggu</span>
                        <span class="text-red-500 font-medium">Tutup</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center text-gray-600 border-t border-gray-800 pt-8">
            © {{ date('Y') }} Bengkel Oto. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Inisialisasi Lucide Icons
    lucide.createIcons();

    // ========================== ANIMASI MUNCUL SAAT SCROLL (Intersection Observer) ==========================
    const animatedEls = document.querySelectorAll('.fade-slide, .fade-zoom');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('appear');
                // Berhenti mengamati setelah muncul
                observer.unobserve(e.target); 
            }
        });
    }, { threshold: 0.2 });
    animatedEls.forEach(el => observer.observe(el));

    // ========================== CAROUSEL ==========================
    const carousel = document.getElementById("carousel");
    if(carousel){
        let index = 0;
        const slides = carousel.children;
        setInterval(() => {
            index = (index + 1) % slides.length;
            carousel.style.transform = `translateX(-${index * 100}%)`;
        }, 3000);
    }

    // ========================== NAVBAR ACTIVE HIGHLIGHT ==========================
    const navLinks = document.querySelectorAll('.nav-link');
    function setActiveLink(){
        let current = "";
        const sections = document.querySelectorAll("section[id]");
        const scrollY = window.pageYOffset;

        sections.forEach(sec => {
            const secTop = sec.offsetTop - 120; // Sesuaikan offset navbar
            const secHeight = sec.offsetHeight;
            if(scrollY >= secTop && scrollY < secTop + secHeight) {
                current = sec.getAttribute("id");
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove("active");
            if(link.getAttribute("href") === `#${current}`) { 
                link.classList.add("active"); 
            }
        });
    }
    window.addEventListener("scroll", setActiveLink);
    window.addEventListener("load", setActiveLink);

    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            document.querySelectorAll('.faq-content').forEach(otherContent => {
                if (otherContent !== content && otherContent.classList.contains('faq-open')) {
                    otherContent.classList.remove('faq-open');
                    otherContent.previousElementSibling.querySelector('i').classList.remove('rotate-180');
                }
            });

            content.classList.toggle('faq-open');
            icon.classList.toggle('rotate-180');
        });
    });

    // ========================== MOBILE MENU ==========================
    const menuBtn = document.getElementById("menu-btn");
    const closeBtn = document.getElementById("close-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    
    if(menuBtn) menuBtn.addEventListener("click", () => mobileMenu.classList.toggle("hidden"));
    if(closeBtn) closeBtn.addEventListener("click", () => mobileMenu.classList.add("hidden"));

const hero = document.querySelector(".hero-wave");
if(hero){
    [...hero.childNodes].forEach(node => {
        if(node.nodeType === Node.TEXT_NODE){
            const text = node.textContent;
            node.textContent = '';

            let i = 0;
            [...text].forEach(char => {
                const span = document.createElement('span');
                span.textContent = char === ' ' ? '\u00A0' : char;
                span.style.animationDelay = `${i * 0.05}s`;
                i++;
                node.parentNode.insertBefore(span, node);
            });
        }
    });
}

    // ========================== TESTIMONI SLIDER ==========================
    const track = document.getElementById("testimonial-track");
    if(track){
        let idx = 0;
        const total = track.children.length;

        setInterval(() => {
            idx = (idx + 1) % total;
            track.style.transform = `translateX(-${idx * 100}%)`;
        }, 3500);
    }
</script>

</body>
</html>