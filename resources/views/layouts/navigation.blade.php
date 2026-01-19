<style>
    /* -------------------- ANIMASI -------------------- */
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.8; transform: scaleY(1); }
        50%      { opacity: 1; transform: scaleY(1.1); }
    }
    .animate-pulse-slow { animation: pulse-slow 1.7s ease-in-out infinite; }

    @keyframes logoFloat {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    .logo-animate {
        animation: logoFloat 3s ease-in-out infinite;
        text-shadow: 0 0 10px rgba(255,255,255,0.4);
        transition: all .3s ease;
    }
    .logo-animate:hover {
        transform: scale(1.05);
        text-shadow: 0 0 15px rgba(255,255,255,0.7);
    }

    /* -------------------- ACTIVE MENU -------------------- */
    .active-menu {
        box-shadow: inset 0 0 20px rgba(255, 230, 0, 0.25),
                    0 0 12px rgba(255, 230, 0, 0.15);
        transition: all 0.35s ease;
    }
    .active-menu:hover {
        box-shadow: inset 0 0 30px rgba(255, 230, 0, 0.35),
                    0 0 20px rgba(255, 230, 0, 0.25);
    }
</style>

<nav 
    x-data="{ open: false }"
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-gradient-to-b from-blue-600 to-indigo-700 text-white shadow-xl transform transition-transform duration-300 lg:translate-x-0"
    :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    x-cloak
>
    <div class="h-16 flex items-center justify-between px-6 border-b border-indigo-500/40">
        <a href="{{ route('dashboard') }}" 
           class="font-extrabold text-xl tracking-wide flex items-center gap-2 hover:opacity-90 transition logo-animate">
            <i data-lucide="wrench" class="w-6 h-6"></i>
            <span>Bengkel Oto.</span>
        </a>

        <button @click="open = !open" class="lg:hidden p-2 hover:bg-white/10 rounded-md">
            <i data-lucide="x" class="w-5 h-5" x-show="open" x-cloak></i>
            <i data-lucide="menu" class="w-5 h-5" x-show="!open" x-cloak></i>
        </button>
    </div>

    <div class="flex-1 px-4 py-6 overflow-y-auto">
        @auth
            @php
                if(auth()->user()->role === 'admin') {
                    $menus = [
                        ['route'=>'admin.dashboard','icon'=>'home','label'=>'Dashboard'],
                        ['route'=>'admin.services.index','icon'=>'wrench','label'=>'Layanan'],
                        ['route'=>'admin.mechanics.index','icon'=>'users','label'=>'Mekanik'],
                        ['route'=>'admin.products.index','icon'=>'package','label'=>'Produk'],
                        ['route'=>'admin.bookings.index','icon'=>'calendar','label'=>'Booking'],
                        ['route'=>'admin.queue.index','icon'=>'list-checks','label'=>'Antrian'],
                        ['route' => 'admin.payments.index', 'icon' => 'credit-card', 'label' => 'Pembayaran'],
                        ['route'=>'admin.penghasilan','icon'=>'wallet','label'=>'Penghasilan'],
                    ];
                } elseif(auth()->user()->role==='mechanic') {
                    // PERBAIKAN: Menambahkan parameter 'ignoreActive' => true pada menu Pesanan Masuk
                    // Ini mencegah menu ini menyala kuning saat kita berada di dashboard
                    $menus = [
                        ['route'=>'mechanic.dashboard','icon'=>'home','label'=>'Dashboard'],
                        ['route'=>'mechanic.jobs.index','icon'=>'wrench','label'=>'Pekerjaan Saya'], 
                        ['route'=>'mechanic.dashboard','icon'=>'shopping-cart','label'=>'Pesanan Masuk', 'ignoreActive' => true], 
                    ];
                } else {
                    $menus = [
                        ['route'=>'customer.dashboard','icon'=>'home','label'=>'Dashboard'],
                        ['route'=>'customer.services','icon'=>'wrench','label'=>'Layanan'],
                        ['route'=>'customer.products','icon'=>'package','label'=>'Produk'],
                        ['route'=>'customer.vehicles.index','icon'=>'car','label'=>'Kendaraan'],
                        ['route'=>'customer.booking.index','icon'=>'calendar','label'=>'Booking Saya'],
                        ['route'=>'customer.queue.index','icon'=>'list-checks','label'=>'Lihat Antrian'],
                        ['route'=>'customer.payment.index','icon'=>'wallet','label'=>'Pembayaran'],
                        ['route'=>'customer.testimoni.create','icon'=>'message-circle','label'=>'Buat Testimoni'],

                    ];
                }
            @endphp

            <ul class="space-y-1">
                @foreach($menus as $i => $menu)
                @php
                    // Logika Cek Aktif: Route cocok DAN tidak ada flag 'ignoreActive'
                    $isActive = (request()->routeIs($menu['route']) || request()->routeIs(str_replace('.index','.*',$menu['route']))) 
                                && !($menu['ignoreActive'] ?? false);
                @endphp

                <li 
                    x-show="open || window.innerWidth >= 1024"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 -translate-x-6"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-6"
                    x-cloak
                >
                    <a href="{{ route($menu['route']) }}" 
                       class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-out relative gap-3
                       {{ $isActive ? 'bg-white/20 backdrop-blur-md shadow-md font-semibold active-menu' : 'hover:bg-white/10 hover:pl-5' }}">

                        @if($isActive)
                            <span class="absolute left-0 top-0 h-full w-1 bg-yellow-400 rounded-r-md animate-pulse-slow"></span>
                        @endif

                        <i data-lucide="{{ $menu['icon'] }}" class="w-5 h-5 transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"></i>
                        <span class="transition-all duration-300 group-hover:tracking-wide">{{ $menu['label'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        @endauth
    </div>

    @auth
    <div class="px-4 py-5 border-t border-indigo-500/40 bg-white/10 backdrop-blur-md">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-white text-blue-700 font-bold flex items-center justify-center mr-3 shadow">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>
            <div>
                <p class="font-semibold">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-300 hover:text-red-500 transition">Logout</button>
                </form>
            </div>
        </div>
    </div>
    @endauth
</nav>

<div 
    class="fixed inset-0 bg-black/50 z-40 lg:hidden"
    x-show="open"
    x-transition.opacity
    @click="open=false"
    x-cloak
></div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>