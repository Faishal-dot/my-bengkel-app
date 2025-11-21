<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i data-lucide="shopping-bag" class="w-7 h-7 text-indigo-600 icon-move"></i>
            <h2 class="font-bold text-2xl text-gray-800">Beli Produk</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-50 via-white to-blue-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="relative bg-white/95 border border-indigo-100 shadow-2xl rounded-3xl p-10 
                        backdrop-blur-xl transition hover:shadow-indigo-200 hover:scale-[1.005] duration-300">

                {{-- Header Produk --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 border-b pb-5">

                    {{-- Icon + Nama --}}
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl 
                                    text-white shadow-lg shadow-indigo-200">
                            <i data-lucide="package" class="w-9 h-9"></i>
                        </div>

                        <div>
                            <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                                {{ $product->name }}
                            </h3>

                            {{-- ⭐ Rating --}}
                            <div class="flex items-center gap-1 mt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" 
                                       class="w-5 h-5 {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}">
                                    </i>
                                @endfor
                                <span class="text-sm text-gray-500 ml-1">
                                    ({{ $product->rating ?? 4 }}/5)
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="mt-4 sm:mt-0 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-7 h-7 text-green-600"></i>
                        <p class="text-4xl font-extrabold text-green-600 drop-shadow-sm">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl p-5 mb-8 shadow-sm">
                    <p class="text-gray-700 leading-relaxed text-base">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                {{-- FORM PEMESANAN --}}
                <form 
                    method="POST" 
                    action="{{ route('customer.orders.store', $product->id) }}" 
                    class="space-y-6" 
                    id="orderForm">
                    
                    @csrf

                    {{-- Jumlah --}}
                    <div>
                        <label class="block font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-indigo-600"></i>
                            <span>Jumlah</span>
                        </label>
                        <input 
                            type="number" 
                            name="quantity" 
                            value="1" 
                            min="1"
                            class="w-28 px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
                                   focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 transition duration-200"
                        >
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        
                        {{-- Pesan --}}
                        <button type="submit"
                                id="btnPesan"
                                class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r 
                                       from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 
                                       text-white rounded-xl shadow-md hover:shadow-xl 
                                       transition-all duration-300 font-semibold text-base">

                            <span id="btnText" class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                Pesan Sekarang
                            </span>

                            {{-- Loading Animasi --}}
                            <span id="btnLoading" class="hidden flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" 
                                            stroke="white" stroke-width="4" fill="none" />
                                    <path class="opacity-75" fill="white" 
                                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                                </svg>
                                Memproses...
                            </span>
                        </button>

                        {{-- Kembali --}}
                        <a href="{{ route('customer.products') }}" 
                           class="flex items-center gap-2 px-6 py-3 bg-gray-500 hover:bg-gray-600 
                                  text-white rounded-xl shadow-md hover:shadow-lg 
                                  transition-all duration-300 font-medium">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT ICON + LOADING --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();
        });

        // Loading animasi
        document.getElementById("orderForm").addEventListener("submit", function() {
            const btnPesan = document.getElementById("btnPesan");
            btnPesan.disabled = true;

            document.getElementById("btnText").classList.add("hidden");
            document.getElementById("btnLoading").classList.remove("hidden");

            btnPesan.classList.add("opacity-80", "cursor-not-allowed");
        });
    </script>
</x-app-layout>