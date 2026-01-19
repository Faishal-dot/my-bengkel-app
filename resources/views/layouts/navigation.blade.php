<style>
    /* -------------------- ANIMASI -------------------- */
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.8; transform: scaleY(1); }
        50%      { opacity: 1; transform: scaleY(1.05); }
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

    /* -------------------- ACTIVE MENU PERBAIKAN -------------------- */
    .active-menu {
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
        background: rgba(255, 255, 255, 0.15) !important;
        box-shadow: inset 0 0 15px rgba(255, 230, 0, 0.1),
                    0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .active-menu span.absolute.left-0 {
        width: 4px !important;
        background-color: #fbbf24 !important;
        box-shadow: 2px 0 8px rgba(251, 191, 36, 0.5);
    }

    .active-menu:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        box-shadow: inset 0 0 20px rgba(255, 230, 0, 0.2);
    }

    /* -------------------- HIDE SCROLLBAR -------------------- */
    .custom-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;     /* Firefox */
    }
    .custom-scrollbar::-webkit-scrollbar {
        display: none;             /* Chrome, Safari and Opera */
    }
</style>

<nav
    x-data="{ open: false }"
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-gradient-to-b from-blue-600 to-indigo-700 text-white shadow-xl transform transition-transform duration-300 lg:translate-x-0"
    :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    x-cloak
>
    <div class="h-16 flex items-center justify-between px-6 border-b border-indigo-500/40 flex-shrink-0">
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

    <div class="flex-1 px-4 py-2 overflow-y-auto custom-scrollbar">
        @auth
            @php
                $role = auth()->user()->role;
                $menuGroups = [];

                if($role === 'admin') {
                    $menuGroups = [
                        'Main' => [
                            ['route'=>'admin.dashboard','icon'=>'home','label'=>'Dashboard'],
                        ],
                        'Manajemen Data' => [
                            ['route'=>'admin.services.index','icon'=>'wrench','label'=>'Layanan'],
                            ['route'=>'admin.products.index','icon'=>'package','label'=>'Produk'],
                            ['route'=>'admin.mechanics.index','icon'=>'users','label'=>'Mekanik'],
                        ],
                        'Operasional' => [
                            ['route'=>'admin.bookings.index','icon'=>'calendar','label'=>'Booking'],
                            ['route'=>'admin.queue.index','icon'=>'list-checks','label'=>'Antrian'],
                        ],
                        'Laporan & Keuangan' => [
                            ['route' => 'admin.payments.index', 'icon' => 'credit-card', 'label' => 'Pembayaran'],
                            ['route'=>'admin.penghasilan','icon'=>'wallet','label'=>'Penghasilan'],
                        ]
                    ];
                } elseif($role === 'mechanic') {
                    $menuGroups = [
                        'Workplace' => [
                            ['route'=>'mechanic.dashboard','icon'=>'home','label'=>'Dashboard'],
                            ['route'=>'mechanic.jobs.index','icon'=>'wrench','label'=>'Pekerjaan Saya'],
                        ],
                        'Updates' => [
                            ['route'=>'mechanic.dashboard','icon'=>'shopping-cart','label'=>'Pesanan Masuk', 'ignoreActive' => true],
                        ]
                    ];
                } else {
                    $menuGroups = [
                        'Beranda' => [
                            ['route'=>'customer.dashboard','icon'=>'home','label'=>'Dashboard'],
                        ],
                        'Tahap 1: Kendaraan' => [
                            ['route'=>'customer.vehicles.index','icon'=>'car','label'=>'Data Kendaraan'],
                        ],
                        'Tahap 2: Layanan' => [
                            ['route'=>'customer.services','icon'=>'wrench','label'=>'Pilih Layanan'],
                            ['route'=>'customer.products','icon'=>'package','label'=>'Produk & Part'],
                            ['route'=>'customer.booking.index','icon'=>'calendar','label'=>'Booking Service'],
                        ],
                        'Tahap 3: Selesai' => [
                            ['route'=>'customer.queue.index','icon'=>'list-checks','label'=>'Status Antrian'],
                            ['route'=>'customer.payment.index','icon'=>'wallet','label'=>'Pembayaran'],
                            ['route'=>'customer.testimoni.create','icon'=>'message-circle','label'=>'Beri Ulasan'],
                        ]
                    ];
                }
            @endphp

            @foreach($menuGroups as $category => $menus)
                <div class="mt-4 mb-1.5 px-4 first:mt-2">
                    <span class="text-[10px] font-bold tracking-widest text-indigo-200 uppercase opacity-60">
                        {{ $category }}
                    </span>
                </div>
               
                <ul class="space-y-1">
                    @foreach($menus as $menu)
                        @php
                            $isActive = (request()->routeIs($menu['route']) || request()->routeIs(str_replace('.index','.*',$menu['route'])))
                                        && !($menu['ignoreActive'] ?? false);
                        @endphp

                        <li
                            x-show="open || window.innerWidth >= 1024"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 -translate-x-6"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-cloak
                        >
                            <a href="{{ route($menu['route']) }}"
                               class="group flex items-center px-4 py-3 rounded-lg transition-all duration-300 ease-out relative gap-3.5 overflow-hidden
                               {{ $isActive ? 'bg-white/20 backdrop-blur-md shadow-md font-semibold active-menu scale-[1.02]' : 'hover:bg-white/10 hover:pl-5' }}">

                                @if($isActive)
                                    <span class="absolute left-0 top-0 h-full w-1.5 bg-yellow-400 animate-pulse-slow"></span>
                                @endif

                                <i data-lucide="{{ $menu['icon'] }}" class="w-5 h-5 transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"></i>
                                <span class="text-base transition-all duration-300 group-hover:tracking-wide">{{ $menu['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        @endauth
    </div>

    @auth
    <div class="px-5 py-6 border-t border-indigo-500/40 bg-white/10 backdrop-blur-lg flex-shrink-0">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-white to-blue-50 text-blue-700 font-bold flex items-center justify-center shadow-lg border border-white/20 text-xl transform group-hover:scale-105 transition duration-300 flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>
           
            <div class="min-w-0 flex-1">
                <p class="font-bold text-base leading-tight truncate text-white drop-shadow-sm">
                    {{ Auth::user()->name }}
                </p>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-xs font-medium text-red-200 hover:text-red-400 transition-colors group/logout">
                        <i data-lucide="log-out" class="w-3.5 h-3.5 group-hover/logout:-translate-x-1 transition-transform"></i>
                        Logout
                    </button>
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