<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full animate-fadeSlide">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="wrench" class="w-7 h-7 text-indigo-600"></i>
                Daftar Layanan Bengkel
            </h2>

            <form method="GET" action="{{ route('customer.services') }}" class="relative group">
                <input type="text" name="q" placeholder="Cari layanan..."
                       class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-xl shadow-sm 
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all duration-300 w-48 focus:w-64"
                       value="{{ request('q') }}">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Hasil Pencarian --}}
            @if(request('q'))
                <div class="mb-6 p-4 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-xl flex items-center gap-2 shadow-sm animate-fadeSlide">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    <span>Menampilkan hasil untuk: <span class="font-bold">"{{ request('q') }}"</span></span>
                    <a href="{{ route('customer.services') }}" 
                       class="ml-auto text-sm bg-white px-3 py-1 rounded-lg border border-indigo-200 hover:bg-indigo-100 transition shadow-sm">
                        Reset
                    </a>
                </div>
            @endif

            @php
                $bundleServices = $services->filter(fn($s) => $s->products && $s->products->count() > 0);
                $nonBundleServices = $services->filter(fn($s) => !($s->products && $s->products->count() > 0));
            @endphp

            @if($services->count())
                
                {{-- SECTION: PAKET BUNDLE --}}
                @if($bundleServices->count() > 0)
                    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-2 animate-fadeSlide">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                                <i data-lucide="package-plus" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Paket Bundle</h3>
                        </div>
                        <span class="text-sm font-medium text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                            {{ $bundleServices->count() }} Layanan
                        </span>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @foreach($bundleServices as $service)
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 animate-cardFade group flex flex-col relative hover:scale-[1.02]">
                                <div class="absolute -top-2 -right-2 z-20 flex items-center gap-2">
                                    {{-- Fitur Tooltip Menggunakan Alpine.js --}}
                                    <div x-data="{ open: false }" class="relative">
                                        <span @mouseenter="open = true" @mouseleave="open = false" 
                                            class="cursor-pointer bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md flex items-center gap-1 transition-transform hover:scale-110">
                                            <i data-lucide="package" class="w-3 h-3"></i>
                                            Paket Bundle
                                        </span>
                                        
                                        {{-- Popover Content --}}
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute right-0 mt-2 w-64 bg-white border border-gray-100 shadow-2xl rounded-xl p-4 z-50 pointer-events-none">
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 border-b pb-1">Isi Paket Produk:</p>
                                            <ul class="space-y-2">
                                                @foreach($service->products as $product)
                                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                                    <span class="font-medium text-xs">{{ $product->name }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="mt-3 pt-2 border-t border-gray-50 flex items-center text-[10px] text-indigo-500 italic">
                                                <i data-lucide="sparkles" class="w-3 h-3 mr-1"></i>
                                                Harga sudah termasuk item di atas
                                            </div>
                                        </div>
                                    </div>

                                    @if($service->discount_price)
                                        <span class="inline-flex items-center bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md">
                                            <i data-lucide="badge-percent" class="w-3 h-3 mr-1"></i>
                                            DISKON -{{ round((($service->price - $service->discount_price) / $service->price) * 100) }}%
                                        </span>
                                    @endif
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-indigo-600 transition-colors pr-4 leading-tight">
                                            {{ $service->name }}
                                        </h3>
                                        <div class="flex flex-col items-end min-w-fit">
                                            @if($service->discount_price)
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 whitespace-nowrap">
                                                    Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 line-through mt-1 whitespace-nowrap">
                                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 whitespace-nowrap">
                                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-6 flex-grow leading-relaxed">
                                        {{ $service->description ?? 'Nikmati layanan perawatan kendaraan terbaik dari teknisi ahli kami.' }}
                                    </p>
                                    <div class="pt-5 border-t border-gray-50 mt-auto">
                                        <a href="{{ route('customer.booking.create', ['service_id' => $service->id]) }}" 
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition-all transform active:scale-95 justify-center shadow-lg shadow-indigo-100">
                                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                            Booking Layanan Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- SECTION: LAYANAN STANDAR --}}
                @if($nonBundleServices->count() > 0)
                    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-2 animate-fadeSlide">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                <i data-lucide="cog" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Layanan Satuan</h3>
                        </div>
                        <span class="text-sm font-medium text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                            {{ $nonBundleServices->count() }} Layanan
                        </span>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($nonBundleServices as $service)
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 animate-cardFade group flex flex-col relative hover:scale-[1.02]">
                                <div class="absolute -top-2 -right-2 z-10 flex items-center gap-2">
                                    @if($service->discount_price)
                                        <span class="inline-flex items-center bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md">
                                            <i data-lucide="badge-percent" class="w-3 h-3 mr-1"></i>
                                            DISKON -{{ round((($service->price - $service->discount_price) / $service->price) * 100) }}%
                                        </span>
                                    @endif
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-indigo-600 transition-colors pr-4 leading-tight">
                                            {{ $service->name }}
                                        </h3>
                                        <div class="flex flex-col items-end min-w-fit">
                                            @if($service->discount_price)
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 whitespace-nowrap">
                                                    Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 line-through mt-1 whitespace-nowrap">
                                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 whitespace-nowrap">
                                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-6 flex-grow leading-relaxed">
                                        {{ $service->description ?? 'Nikmati layanan perawatan kendaraan terbaik dari teknisi ahli kami.' }}
                                    </p>
                                    <div class="pt-5 border-t border-gray-50 mt-auto">
                                        <a href="{{ route('customer.booking.create', ['service_id' => $service->id]) }}" 
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition-all transform active:scale-95 justify-center shadow-lg shadow-indigo-100">
                                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                            Booking Layanan Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-12 animate-fadeSlide">
                    {{ $services->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center animate-fadeSlide shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="wrench" class="w-10 h-10 text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Tidak ada layanan ditemukan</h3>
                    <p class="text-gray-500 max-w-xs mx-auto">Coba cari dengan kata kunci lain atau reset pencarian Anda.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    <style>
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes cardFade {
            from { opacity: 0; transform: scale(0.97); }
            to   { opacity: 1; transform: scale(1); }
        }
        .animate-fadeSlide { animation: fadeSlide 0.6s ease-out forwards; }
        .animate-cardFade { animation: cardFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</x-app-layout>