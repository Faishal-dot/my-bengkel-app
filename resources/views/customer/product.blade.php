<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full animate-fadeSlide">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="package" class="w-7 h-7 text-indigo-600"></i>
                Daftar Produk & Sparepart
            </h2>

            <form method="GET" action="{{ route('customer.products') }}" class="relative group">
                <input type="text" name="q" placeholder="Cari produk..."
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
                    <a href="{{ route('customer.products') }}" 
                       class="ml-auto text-sm bg-white px-3 py-1 rounded-lg border border-indigo-200 hover:bg-indigo-100 transition shadow-sm">
                        Reset
                    </a>
                </div>
            @endif

            @if($products->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 animate-cardFade group flex flex-col relative hover:scale-[1.02]">
                            
                            {{-- Badges Container --}}
                            <div class="absolute -top-2 -right-2 z-10 flex items-center gap-2">
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md flex items-center gap-1">
                                        <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                        Stok Terbatas
                                    </span>
                                @elseif($product->stock <= 0)
                                    <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md">
                                        Habis
                                    </span>
                                @else
                                    <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md">
                                        Tersedia
                                    </span>
                                @endif
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-indigo-600 transition-colors pr-4 leading-tight">
                                        {{ $product->name }}
                                    </h3>
                                    
                                    {{-- Bagian Harga & Stok --}}
                                    <div class="flex flex-col items-end min-w-fit">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 whitespace-nowrap">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider font-semibold whitespace-nowrap">
                                            Stok: {{ $product->stock }}
                                        </span>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-600 mb-6 flex-grow leading-relaxed line-clamp-3">
                                    {{ $product->description ?? 'Suku cadang berkualitas tinggi untuk performa kendaraan maksimal Anda.' }}
                                </p>

                                {{-- Tombol Aksi --}}
                                <div class="pt-5 border-t border-gray-50 mt-auto">
                                    @if($product->stock > 0)
                                        <a href="{{ route('customer.orders.create', $product) }}" 
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-bold transition-all transform active:scale-95 justify-center shadow-lg shadow-indigo-100">
                                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                            Beli Produk Sekarang
                                        </a>
                                    @else
                                        <button disabled 
                                            class="w-full inline-flex items-center gap-2 px-4 py-3 bg-gray-200 text-gray-500 rounded-xl text-sm font-bold justify-center cursor-not-allowed">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                                            Produk Tidak Tersedia
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12 animate-fadeSlide">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                {{-- State Kosong --}}
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 flex flex-col items-center animate-fadeSlide shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="package" class="w-10 h-10 text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Produk tidak ditemukan</h3>
                    <p class="text-gray-500 max-w-xs mx-auto">Kami tidak dapat menemukan produk yang Anda cari. Silakan coba kata kunci lain atau reset pencarian Anda.</p>
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

        .animate-fadeSlide {
            animation: fadeSlide 0.6s ease-out forwards;
        }

        .animate-cardFade {
            animation: cardFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</x-app-layout>