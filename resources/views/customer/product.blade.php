<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="package" class="w-6 h-6 text-indigo-600"></i>
                Daftar Produk Bengkel
            </h2>

            <!-- Search -->
            <form method="GET" action="{{ route('customer.products') }}" class="relative">
                <input type="text" name="q" placeholder="Cari produk..."
                       class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       value="{{ request('q') }}">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($products->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white shadow-md hover:shadow-xl rounded-2xl p-5 transition flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $product->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 flex-grow">
                                {{ $product->description ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-4
                                {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Habis' }}
                            </span>

                            @if($product->stock > 0)
                                <a href="{{ route('customer.orders.create', $product) }}" 
                                   class="mt-auto inline-flex items-center gap-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium text-center transition">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                    Beli Produk
                                </a>
                            @else
                                <span class="mt-auto inline-flex items-center gap-1 px-4 py-2 bg-gray-400 text-white rounded-xl text-sm text-center">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    Habis
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-10 text-gray-500">
                    <i data-lucide="frown" class="text-5xl mb-3"></i>
                    <p>Belum ada produk yang tersedia.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>