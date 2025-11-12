<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i data-lucide="shopping-bag" class="w-7 h-7 text-indigo-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Beli Produk</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-blue-50 via-indigo-50 to-white min-h-screen">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="relative bg-white/90 border border-indigo-100 shadow-2xl rounded-3xl p-10 backdrop-blur-md transition hover:shadow-indigo-200 hover:scale-[1.01] duration-300">

                {{-- Header Produk --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 border-b pb-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-2xl text-white shadow-lg">
                            <i data-lucide="package" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ $product->name }}</h3>
                        </div>
                    </div>

                    {{-- Harga Produk --}}
                    <div class="mt-4 sm:mt-0 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-6 h-6 text-green-600"></i>
                        <p class="text-3xl font-bold text-green-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Deskripsi Produk --}}
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl p-5 mb-8">
                    <p class="text-gray-700 leading-relaxed text-sm sm:text-base">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                {{-- Form Pemesanan --}}
                <form method="POST" action="{{ route('customer.orders.store', $product->id) }}" class="space-y-6">
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
                        {{-- Tombol Pesan --}}
                        <button type="submit" 
                            class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 
                                   hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl shadow-md 
                                   hover:shadow-xl transition-all duration-300 font-semibold text-base">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <span>Pesan Sekarang</span>
                        </button>

                        {{-- Tombol Kembali --}}
                        <a href="{{ route('customer.products') }}" 
                           class="flex items-center gap-2 px-6 py-3 bg-gray-500 hover:bg-gray-600 
                                  text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 font-medium">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Icon --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();
        });
    </script>
</x-app-layout>