<style>
    /* Pulse lembut untuk garis kuning */
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.8; transform: scaleY(1); }
        50%      { opacity: 1; transform: scaleY(1.1); }
    }
    .animate-pulse-slow {
        animation: pulse-slow 1.7s ease-in-out infinite;
    }

    /* Glow smooth untuk menu aktif */
    .active-menu {
        box-shadow: inset 0 0 20px rgba(255, 230, 0, 0.25),
                    0 0 12px rgba(255, 230, 0, 0.15);
        transition: all 0.35s ease;
    }
    .active-menu:hover {
        box-shadow: inset 0 0 30px rgba(255, 230, 0, 0.35),
                    0 0 20px rgba(255, 230, 0, 0.25);
    }

    /* 🔥 ANIMASI LOGO BENGKEL OTO */
    @keyframes logoFloat {
        0%, 100% { transform: translateY(0); }
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
</style>

<nav 
    x-data="{ open: false }" 
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-gradient-to-b from-blue-600 to-indigo-700 text-white shadow-xl transform transition-transform duration-300 lg:translate-x-0"
    :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <!-- Logo & Toggle -->
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

    <!-- Menu Utama -->
    <div class="flex-1 px-4 py-6 overflow-y-auto">
        @auth
            @php
                if (auth()->user()->role === 'admin') {
                    $menus = [
                        ['route' => 'admin.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                        ['route' => 'admin.services.index', 'icon' => 'wrench', 'label' => 'Layanan'],
                        ['route' => 'admin.mechanics.index', 'icon' => 'users', 'label' => 'Mekanik'],
                        ['route' => 'admin.products.index', 'icon' => 'package', 'label' => 'Produk'],
                        ['route' => 'admin.bookings.index', 'icon' => 'calendar', 'label' => 'Booking'],
                        ['route' => 'admin.orders.index', 'icon' => 'shopping-cart', 'label' => 'Pesanan'],
                        ['route' => 'admin.penghasilan', 'icon' => 'wallet', 'label' => 'Penghasilan'],
                    ];
                } elseif (auth()->user()->role === 'mechanic') {
                    $menus = [
                        ['route' => 'mechanic.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                        ['route' => 'mechanic.jobs.index', 'icon' => 'wrench', 'label' => 'Pekerjaan Saya'],
                        ['route' => 'mechanic.bookings.index', 'icon' => 'calendar', 'label' => 'Booking Masuk'],
                        ['route' => 'mechanic.orders.index', 'icon' => 'shopping-cart', 'label' => 'Pesanan Masuk'],
                        ['route' => 'mechanic.history.index', 'icon' => 'history', 'label' => 'Riwayat'],
                    ];
                } else {
                    $menus = [
                        ['route' => 'customer.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                        ['route' => 'customer.services', 'icon' => 'wrench', 'label' => 'Layanan'],
                        ['route' => 'customer.products', 'icon' => 'package', 'label' => 'Produk'],
                        ['route' => 'customer.vehicles.index', 'icon' => 'car', 'label' => 'Kendaraan'],
                        ['route' => 'customer.booking.index', 'icon' => 'calendar', 'label' => 'Booking Saya'],
                        ['route' => 'customer.queue.index', 'icon' => 'list', 'label' => 'Lihat Antrian'],
                        ['route' => 'customer.orders.index', 'icon' => 'shopping-cart', 'label' => 'Pesanan Saya'],
                    ];
                }
            @endphp

            <ul class="space-y-1" x-data>
                @foreach ($menus as $i => $menu)
                    <li 
                        x-show="open || window.innerWidth >= 1024"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 -translate-x-6"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition.delay.{{ $i * 100 }}ms
                    >
                        <a href="{{ route($menu['route']) }}" 
                           class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-out relative gap-3
                           {{ request()->routeIs($menu['route']) || request()->routeIs(str_replace('.index','.*',$menu['route']))
                                ? 'bg-white/20 backdrop-blur-md shadow-md font-semibold active-menu'
                                : 'hover:bg-white/10 hover:pl-5' }}">

                            @if (request()->routeIs($menu['route']) || request()->routeIs(str_replace('.index','.*',$menu['route'])))
                                <span class="absolute left-0 top-0 h-full w-1 bg-yellow-400 rounded-r-md animate-pulse-slow"></span>
                            @endif

                            <i data-lucide="{{ $menu['icon'] }}" 
                               class="w-5 h-5 transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"></i>

                            <span class="transition-all duration-300 group-hover:tracking-wide">
                                {{ $menu['label'] }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endauth
    </div>

    <!-- Profile -->
    @auth
    <div class="px-4 py-5 border-t border-indigo-500/40 bg-white/10 backdrop-blur-md">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-white text-blue-700 font-bold flex items-center justify-center mr-3 shadow">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
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

<!-- Overlay Mobile -->
<div 
    class="fixed inset-0 bg-black/50 z-40 lg:hidden"
    x-show="open"
    x-transition.opacity
    @click="open = false"
    x-cloak
></div>