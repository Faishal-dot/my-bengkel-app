<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i data-lucide="edit" class="w-7 h-7 text-indigo-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Edit Layanan</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-indigo-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto animate-fadeIn">

            <div class="flex items-center justify-center gap-2 mb-8">
                <i data-lucide="form-input" class="w-5 h-5 text-indigo-600"></i>
                <span class="text-indigo-600 font-semibold">Form Perbarui Layanan</span>
            </div>

            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8 transition-all duration-500 hover:shadow-2xl">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                        <ul class="list-disc pl-6 space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.services.update', $service->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="relative transition-all duration-300 hover:scale-[1.01]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="wrench" class="w-4 h-4 text-indigo-500"></i>
                            Nama Layanan
                        </label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
                    </div>

                    <div class="relative transition-all duration-300 hover:scale-[1.01]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i>
                            Deskripsi (opsional)
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">{{ old('description', $service->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative transition-all duration-300 hover:scale-[1.01]">
                            <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                                <i data-lucide="wallet" class="w-4 h-4 text-indigo-500"></i>
                                Harga Layanan (Rp)
                            </label>
                            <input type="number" name="price" value="{{ old('price', $service->price) }}" step="1000"
                                class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
                        </div>

                        <div class="relative transition-all duration-300 hover:scale-[1.01]">
                            <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium text-rose-600">
                                <i data-lucide="tag" class="w-4 h-4 text-rose-500"></i>
                                Harga Diskon (Opsional)
                            </label>
                            <input type="number" name="discount_price" value="{{ old('discount_price', $service->discount_price) }}" step="1000"
                                placeholder="Kosongkan jika tidak ada diskon"
                                class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-rose-400 focus:border-rose-400">
                        </div>
                    </div>

                    <hr class="border-gray-100 my-8">

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 text-gray-700 font-bold text-lg">
                                <i data-lucide="package" class="w-5 h-5 text-indigo-500"></i>
                                Daftar Produk dalam Layanan
                            </label>
                            <button type="button" id="add-product-row"
                                class="flex items-center gap-1 text-sm bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg border border-indigo-200 hover:bg-indigo-100 transition shadow-sm">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Tambah Produk
                            </button>
                        </div>

                        <div id="product-container" class="space-y-3">
                            @forelse($service->products as $selectedProduct)
                                <div class="product-row flex gap-3 animate-fadeIn">
                                    <div class="flex-1">
                                        <select name="products[]" class="w-full border-gray-300 rounded-lg focus:ring-indigo-400 focus:border-indigo-400">
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ $selectedProduct->id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} (Stok: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <input type="number" name="quantities[]" min="1" value="{{ $selectedProduct->pivot->quantity }}" 
                                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-400 focus:border-indigo-400">
                                    </div>
                                    <button type="button" class="remove-row p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="product-row flex gap-3 animate-fadeIn">
                                    <div class="flex-1">
                                        <select name="products[]" class="w-full border-gray-300 rounded-lg focus:ring-indigo-400 focus:border-indigo-400">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <input type="number" name="quantities[]" min="1" value="1" 
                                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-400 focus:border-indigo-400">
                                    </div>
                                    <button type="button" class="remove-row p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-10">
                        <button type="submit"
                            class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-8 py-3 rounded-lg shadow-md hover:from-indigo-600 hover:to-purple-700 transition font-semibold">
                            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                            Perbarui Layanan
                        </button>
                        <a href="{{ route('admin.services.index') }}" class="px-8 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition font-semibold">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        const container = document.getElementById('product-container');
        const addButton = document.getElementById('add-product-row');

        addButton.addEventListener('click', () => {
            const firstRow = container.querySelector('.product-row');
            const row = firstRow.cloneNode(true);
            const select = row.querySelector('select');
            const input = row.querySelector('input');
            
            if(select) select.value = "";
            if(input) input.value = "1";
            
            container.appendChild(row);
            lucide.createIcons();
        });

        container.addEventListener('click', (e) => {
            if (e.target.closest('.remove-row')) {
                if (container.querySelectorAll('.product-row').length > 1) {
                    e.target.closest('.product-row').remove();
                } else {
                    // Jika baris terakhir, cukup kosongkan nilainya saja
                    const row = container.querySelector('.product-row');
                    row.querySelector('select').value = "";
                    row.querySelector('input').value = "1";
                }
            }
        });
    </script>
</x-app-layout>