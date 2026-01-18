<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full animate-fadeIn">

            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                Manajemen Produk
            </h2>

            <div class="flex items-center gap-3">

                <form method="GET" action="{{ route('admin.products.index') }}" class="relative">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Cari nama atau SKU..."
                        value="{{ request('q') }}"
                        class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm 
                               focus:ring-2 focus:ring-blue-500 focus:outline-none 
                               transition-all duration-300 hover:shadow-md"
                    >
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </form>

                <button 
                    onclick="showListView()"
                    id="btnListMode"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl shadow 
                           hover:bg-blue-700 transform hover:scale-[1.03] active:scale-95 transition-all duration-200 text-sm font-medium">
                    <i data-lucide="list" class="w-5 h-5"></i>
                    Detail List
                </button>

                <button 
                    onclick="showCardView()"
                    id="btnCardMode"
                    class="hidden inline-flex items-center gap-2 bg-gray-500 text-white px-4 py-2 rounded-xl shadow 
                           hover:bg-gray-600 transform hover:scale-[1.03] active:scale-95 transition-all duration-200 text-sm font-medium">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    Kembali
                </button>

                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 
                        text-white px-4 py-2 rounded-xl shadow 
                        hover:from-indigo-700 hover:to-purple-700 
                        transform hover:scale-[1.03] active:scale-95 
                        transition-all duration-200 text-sm font-medium">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 animate-fadeIn">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2 shadow-sm animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5"></i> 
                    {{ session('success') }}
                </div>
            @endif

            <div id="cardView" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($products as $product)
                @php $laba = $product->price - $product->purchase_price; @endphp

                <div class="bg-white shadow-md hover:shadow-xl rounded-2xl p-5 
                            transition-all duration-300 animate-card hover:scale-[1.02]">

                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                SKU: {{ $product->sku ?? '-' }}
                            </span>
                            <h3 class="font-semibold text-lg text-gray-800 mt-1">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            Stok: {{ $product->stock }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                        {{ $product->description ?? 'Tidak ada deskripsi.' }}
                    </p>

                    <p class="text-xl font-bold text-blue-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <p class="text-sm mt-1 {{ $laba < 0 ? 'text-red-600' : 'text-blue-600' }} font-semibold flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                        Laba: Rp {{ number_format($laba, 0, ',', '.') }}
                    </p>

                    <div class="flex justify-end gap-2 mt-4">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="p-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow transition">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 shadow transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @empty
                    <div class="col-span-full text-center py-10 text-gray-500 italic animate-fadeIn">
                        Belum ada produk.
                    </div>
                @endforelse
            </div>

            <div id="paginationSection" class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>


            <div id="listView" 
                 class="hidden bg-white rounded-xl shadow p-6 mt-8 animate-slideRight">

                <h3 class="text-lg font-semibold flex items-center gap-2 mb-4">
                    <i data-lucide="list" class="w-5 h-5 text-blue-600"></i>
                    Detail List Produk
                </h3>

                <div class="overflow-auto">
                    <table class="w-full text-sm border">
                        <thead>
                            <tr class="bg-gray-100 border-b text-left text-gray-600">
                                <th class="p-2">SKU</th>
                                <th class="p-2">Produk</th>
                                <th class="p-2">Modal</th>
                                <th class="p-2">Harga</th>
                                <th class="p-2">Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productsAll as $p)
                            @php $laba = $p->price - $p->purchase_price; @endphp
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-2 font-mono text-xs text-blue-600">{{ $p->sku ?? '-' }}</td>
                                <td class="p-2 font-medium">{{ $p->name }}</td>
                                <td class="p-2">Rp {{ number_format($p->purchase_price,0,',','.') }}</td>
                                <td class="p-2">Rp {{ number_format($p->price,0,',','.') }}</td>
                                <td class="p-2 font-semibold {{ $laba < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format($laba,0,',','.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        function showListView() {
            document.getElementById("cardView").classList.add("hidden");
            document.getElementById("paginationSection").classList.add("hidden");

            let listView = document.getElementById("listView");
            listView.classList.remove("hidden");

            // restart animation
            listView.classList.remove("animate-slideRight");
            void listView.offsetWidth;
            listView.classList.add("animate-slideRight");

            document.getElementById("btnListMode").classList.add("hidden");
            document.getElementById("btnCardMode").classList.remove("hidden");
        }

        function showCardView() {
            document.getElementById("cardView").classList.remove("hidden");
            document.getElementById("paginationSection").classList.remove("hidden");
            document.getElementById("listView").classList.add("hidden");

            document.getElementById("btnCardMode").classList.add("hidden");
            document.getElementById("btnListMode").classList.remove("hidden");
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out; }

        @keyframes cardIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-card { animation: cardIn 0.5s ease-out; }

        @keyframes slideRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slideRight {
            animation: slideRight 0.5s ease-out;
        }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>

</x-app-layout>