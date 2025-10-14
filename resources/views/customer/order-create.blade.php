<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            🛍️ Beli Produk
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-8">
                
                <!-- Nama Produk -->
                <h3 class="text-2xl font-bold text-blue-600 mb-2">{{ $product->name }}</h3>
                
                <!-- Deskripsi Produk -->
                <p class="mb-3 text-gray-600 leading-relaxed">
                    {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                </p>
                
                <!-- Harga Produk -->
                <p class="mb-6 text-green-600 font-extrabold text-lg">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <!-- Form Pemesanan -->
                <form method="POST" action="{{ route('customer.orders.store', $product->id) }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="quantity" value="1" min="1"
                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 transition">
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Tombol Pesan -->
                        <button type="submit" 
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                            ✅ Pesan Sekarang
                        </button>

                        <!-- Tombol Kembali -->
                        <a href="{{ route('customer.products') }}" 
                           class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-md transition font-medium">
                            ⬅️ Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>