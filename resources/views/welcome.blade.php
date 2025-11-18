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
html { scroll-behavior: smooth; }

/* Scrollbar */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #0f172a; }
::-webkit-scrollbar-thumb { background-color: #3b82f6; border-radius: 10px; border: 2px solid #0f172a; }
::-webkit-scrollbar-thumb:hover { background-color: #2563eb; }
* { scrollbar-width: thin; scrollbar-color: #3b82f6 #0f172a; }

html, body { height: 100%; margin: 0; padding: 0; background-color: #0f172a; overflow-x: hidden; font-family: 'Figtree', sans-serif; }

/* ========================== ANIMASI HALUS CARD ========================== */
.fade-slide { opacity: 0; transform: translateY(20px); transition: all .6s ease-out; }
.fade-slide.appear { opacity: 1; transform: translateY(0); }
.fade-zoom { opacity: 0; transform: scale(0.95); transition: all .6s ease-out; }
.fade-zoom.appear { opacity: 1; transform: scale(1); }

/* FAQ */
.faq-content { max-height: 0; overflow: hidden; padding-top: 0; padding-bottom: 0; transition: max-height 0.4s ease, padding 0.4s ease; }
.faq-open { max-height: 500px; padding-top: 0.5rem; padding-bottom: 0.5rem; }

/* Navbar highlight */
.nav-link { transition: color 0.25s ease; position: relative; padding-bottom: 6px; }
.nav-link::after { content: ""; position: absolute; left: 50%; transform: translateX(-50%) scaleX(0); bottom: 0; width: 60%; height: 2px; background: #3b82f6; transform-origin: center; transition: transform 0.25s ease; }
.nav-link.active { color: #3b82f6; font-weight: 600; }
.nav-link.active::after { transform: translateX(-50%) scaleX(1); }

@media (max-width: 767px) { .nav-link::after { width: 80%; } }
section { scroll-margin-top: 84px; }

/* HERO WAVE ANIMASI */
.hero-wave span {
    display: inline-block;
    animation: wave 1.2s infinite;
}
@keyframes wave {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-12px); }
}
</style>
</head>

<body class="antialiased bg-gradient-to-br from-gray-900 via-blue-900 to-gray-800 text-white">

<!-- NAVBAR -->
<nav class="flex items-center justify-between px-6 py-4 bg-black/20 backdrop-blur-lg fixed w-full z-50 shadow-md">
    <div class="flex items-center gap-2">
        <i data-lucide="wrench" class="w-6 h-6 text-blue-400"></i>
        <span class="text-xl font-bold">Bengkel Oto.</span>
    </div>

    <div class="space-x-4 hidden md:block">
        <a href="#layanan" class="nav-link hover:text-blue-400">LAYANAN</a>
        <a href="#tentang" class="nav-link hover:text-blue-400">TENTANG</a>
        <a href="#testimoni" class="nav-link hover:text-blue-400">TESTIMONI</a>
        <a href="#faq" class="nav-link hover:text-blue-400">FAQ</a>
        <a href="#kontak" class="nav-link hover:text-blue-400">KONTAK</a>
        <a href="#lokasi" class="nav-link hover:text-blue-400">LOKASI</a>
    </div>

    <div class="space-x-3 hidden md:flex">
        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-blue-400 hover:bg-blue-500 hover:text-white transition">Masuk</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-semibold">Daftar</a>
    </div>

    <button id="menu-btn" class="md:hidden flex items-center text-blue-400">
        <i data-lucide="menu" class="w-7 h-7"></i>
    </button>
</nav>

<!-- MOBILE MENU -->
<div id="mobile-menu" class="hidden fixed top-0 right-0 h-full w-2/3 bg-gray-900/95 p-6 z-40 flex flex-col gap-4">
    <button id="close-btn" class="self-end text-gray-400 hover:text-white">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>
    <a href="#layanan" class="nav-link">LAYANAN</a>
    <a href="#tentang" class="nav-link">TENTANG</a>
    <a href="#testimoni" class="nav-link">TESTIMONI</a>
    <a href="#faq" class="nav-link">FAQ</a>
    <a href="#kontak" class="nav-link">KONTAK</a>
    <a href="#lokasi" class="nav-link">LOKASI</a>
    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-blue-400 hover:bg-blue-500 transition text-center">Masuk</a>
    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-semibold text-center">Daftar</a>
</div>

<!-- HERO -->
<section class="h-screen flex flex-col items-center justify-center text-center px-6 fade-slide">
    <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold leading-tight drop-shadow-lg hero-wave">
        Solusi Perawatan & Perbaikan <span class="text-blue-400">Kendaraan</span> Anda
    </h1>
    <p class="mt-4 text-base sm:text-lg text-gray-300 max-w-2xl">
        Bengkel Oto siap melayani servis mobil & motor dengan profesional, cepat, dan terpercaya.
    </p>
    <div class="mt-6 flex flex-col sm:flex-row gap-4">
        <a href="{{ route('customer.booking.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 font-semibold transition shadow-lg">
            <i data-lucide="calendar" class="w-5 h-5"></i>
            Booking Sekarang
        </a>
        <a href="#layanan" class="px-6 py-3 rounded-xl border border-gray-400 hover:bg-gray-700 transition">Lihat Layanan</a>
    </div>
</section>

<!-- LAYANAN -->
<section id="layanan" class="py-20 px-6 bg-gray-900/60 backdrop-blur-lg">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-12 fade-slide">Layanan Unggulan Kami</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-white/10 shadow-lg hover:scale-105 transition flex flex-col items-center text-center fade-zoom">
                <i data-lucide="droplets" class="w-10 h-10 text-blue-600 mb-4"></i>
                <h3 class="text-xl font-semibold">Ganti Oli</h3>
                <p class="text-gray-300 mt-2">Perawatan rutin agar mesin tetap awet & bertenaga.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/10 shadow-lg hover:scale-105 transition flex flex-col items-center text-center fade-zoom">
                <i data-lucide="car" class="w-10 h-10 text-blue-400 mb-4"></i>
                <h3 class="text-xl font-semibold">Servis Mobil</h3>
                <p class="text-gray-300 mt-2">Diagnosa & perbaikan menyeluruh untuk kendaraan Anda.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/10 shadow-lg hover:scale-105 transition flex flex-col items-center text-center fade-zoom">
                <i data-lucide="settings" class="w-10 h-10 text-blue-400 mb-4"></i>
                <h3 class="text-xl font-semibold">Tune Up</h3>
                <p class="text-gray-300 mt-2">Optimalkan performa mesin agar tetap prima.</p>
            </div>
        </div>
    </div>
</section>

<!-- TENTANG -->
<section id="tentang" class="py-20 px-6 fade-slide">
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
                Kami berdedikasi memberikan layanan terbaik dengan mekanik berpengalaman serta peralatan modern.
            </p>
            <a href="#kontak" class="mt-6 inline-block px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition font-semibold shadow-lg">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

<!-- TESTIMONI -->
<section id="testimoni" class="py-20 px-6 bg-gray-900/60 backdrop-blur-lg fade-slide">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-12">Apa Kata Pelanggan?</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white/10 rounded-2xl shadow-lg fade-zoom">
                <p class="text-gray-300">“Servis cepat dan mekanik ramah!”</p>
                <span class="block mt-4 font-semibold text-blue-400">Andi Setiawan</span>
            </div>
            <div class="p-6 bg-white/10 rounded-2xl shadow-lg fade-zoom">
                <p class="text-gray-300">“Booking online sangat membantu.”</p>
                <span class="block mt-4 font-semibold text-blue-400">Siti Badriah</span>
            </div>
            <div class="p-6 bg-white/10 rounded-2xl shadow-lg fade-zoom">
                <p class="text-gray-300">“Pelayanan profesional, puas sekali.”</p>
                <span class="block mt-4 font-semibold text-blue-400">Budi Recing Hell</span>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="py-20 px-6 bg-gray-800/50 backdrop-blur-lg fade-slide">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold mb-10 text-center">Pertanyaan Umum</h2>
        <div class="space-y-4">
            <div class="bg-white/10 rounded-xl shadow-lg overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle">
                    Apakah harus booking dulu?
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">Tidak wajib, tapi sangat disarankan untuk menghindari antrian.</div>
            </div>
            <div class="bg-white/10 rounded-xl shadow-lg overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle">
                    Metode pembayaran?
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">Tunai, transfer bank, OVO, DANA, GoPay.</div>
            </div>
            <div class="bg-white/10 rounded-xl shadow-lg overflow-hidden">
                <button class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold faq-toggle">
                    Bisa servis motor?
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </button>
                <div class="faq-content px-6 text-gray-300">Ya, kami melayani servis mobil dan motor.</div>
            </div>
        </div>
    </div>
</section>

<!-- KONTAK -->
<section id="kontak" class="py-20 px-6 bg-gray-900/60 backdrop-blur-lg fade-slide">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6">Kontak Kami</h2>
        <p class="text-gray-300 mb-8">Hubungi kami melalui:</p>
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

<!-- LOKASI -->
<section id="lokasi" class="py-20 px-6 fade-slide">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-10">Lokasi Kami</h2>
        <div class="p-6 bg-white/10 rounded-2xl shadow-lg backdrop-blur-md">
            <span class="block font-semibold text-lg">Jl. Otomotif No. 88, Mekar Jaya, Kota Auto, Indonesia</span>
        </div>
        <p class="text-sm text-gray-400 mt-8">Buka pukul <span class="text-blue-300 font-medium">08.00–17.00</span></p>
    </div>
</section>

<!-- FOOTER -->
<footer class="py-6 text-center text-gray-400 text-sm border-t border-gray-700 bg-black/40">
    © {{ date('Y') }} Bengkel Oto. All rights reserved.
</footer>

<!-- SCRIPTS -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // Fade Animasi muncul saat scroll
    const animatedEls = document.querySelectorAll('.fade-slide, .fade-zoom');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('appear'); });
    }, { threshold: 0.2 });
    animatedEls.forEach(el => observer.observe(el));

    // Carousel
    const carousel = document.getElementById("carousel");
    if(carousel){
        let index=0;
        const slides=carousel.children;
        setInterval(()=>{ index=(index+1)%slides.length; carousel.style.transform=`translateX(-${index*100}%)`; },3000);
    }

    // Navbar active highlight
    const navLinks = document.querySelectorAll('.nav-link');
    function setActiveLink(){
        let current="";
        const sections=document.querySelectorAll("section[id]");
        const scrollY=window.pageYOffset;
        sections.forEach(sec=>{
            const secTop=sec.offsetTop-120;
            const secHeight=sec.offsetHeight;
            if(scrollY>=secTop && scrollY<secTop+secHeight) current=sec.getAttribute("id");
        });
        navLinks.forEach(link=>{ link.classList.remove("active"); if(link.getAttribute("href")==`#${current}`) link.classList.add("active"); });
    }
    window.addEventListener("scroll", setActiveLink);
    window.addEventListener("load", setActiveLink);

    // FAQ toggle
    document.querySelectorAll('.faq-toggle').forEach(button=>{
        button.addEventListener('click',()=>{
            const content=button.nextElementSibling;
            const icon=button.querySelector('i');
            content.classList.toggle('faq-open');
            icon.classList.toggle('rotate-180');
        });
    });

    // Mobile menu
    const menuBtn=document.getElementById("menu-btn");
    const closeBtn=document.getElementById("close-btn");
    const mobileMenu=document.getElementById("mobile-menu");
    if(menuBtn) menuBtn.addEventListener("click",()=>mobileMenu.classList.toggle("hidden"));
    if(closeBtn) closeBtn.addEventListener("click",()=>mobileMenu.classList.add("hidden"));

    // HERO WAVE ANIMASI PER HURUF
    const hero = document.querySelector(".hero-wave");
    if(hero){
        [...hero.childNodes].forEach(node=>{
            if(node.nodeType===Node.TEXT_NODE){
                const text=node.textContent;
                node.textContent='';
                [...text].forEach(char=>{
                    const span=document.createElement('span');
                    span.textContent=char===' ' ? '\u00A0' : char;
                    span.style.animationDelay=`${Math.random()*0.6}s`;
                    node.parentNode.insertBefore(span,node);
                });
            }
        });
    }
</script>

</body>
</html>