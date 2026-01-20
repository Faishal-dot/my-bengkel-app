<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i data-lucide="pencil-line" class="w-7 h-7 text-blue-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Edit Produk</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto animate-fadeIn">

            <div class="flex items-center justify-center gap-2 mb-8">
                <i data-lucide="edit-3" class="w-5 h-5 text-blue-600"></i>
                <span class="text-blue-600 font-semibold">Form Edit</span>
            </div>

            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                        <ul class="list-disc pl-6 space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="barcode" class="w-4 h-4 text-blue-500"></i>
                            Kode Produk (SKU)
                        </label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Contoh: BRG-001..." required>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="package" class="w-4 h-4 text-blue-500"></i>
                            Nama Produk
                        </label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan nama produk..." required>
                    </div>

                    {{-- TAMBAHAN: FIELD KATEGORI UNTUK EDIT --}}
                    <div>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="layers" class="w-4 h-4 text-blue-500"></i>
                            Kategori Produk
                        </label>
                        <select name="category" required
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400">
                            <option value="Sparepart" {{ old('category', $product->category) == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
                            <option value="Cairan" {{ old('category', $product->category) == 'Cairan' ? 'selected' : '' }}>Oli & Cairan</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i>
                            Deskripsi
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Masukkan deskripsi produk...">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                                <i data-lucide="wallet" class="w-4 h-4 text-blue-500"></i>
                                Harga Beli
                            </label>
                            <input type="number" id="harga_beli" name="purchase_price"
                                   value="{{ old('purchase_price', $product->purchase_price) }}"
                                class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400"
                                placeholder="Harga beli..." required>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                                <i data-lucide="credit-card" class="w-4 h-4 text-blue-500"></i>
                                Harga Jual
                            </label>
                            <input type="number" id="harga_jual" name="price"
                                   value="{{ old('price', $product->price) }}"
                                class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400"
                                placeholder="Harga jual..." required>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="badge-dollar-sign" class="w-4 h-4 text-blue-500"></i>
                            Laba Estimasi
                        </label>

                        <input type="number" id="laba_otomatis" disabled
                            class="w-full bg-gray-100 border-gray-300 rounded-lg px-4 py-3 shadow-sm font-bold text-blue-600">

                        <div id="warning_rugi" class="mt-2 flex items-center text-red-600 font-semibold gap-2 hidden animate-pulse">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            <span>Harga jual lebih kecil dari harga beli! Kamu rugi.</span>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="boxes" class="w-4 h-4 text-blue-500"></i>
                            Stok
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Jumlah stok..." required>
                    </div>

                    <div class="flex items-center gap-4 pt-6">
                        <button type="submit"
                                class="flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-lg shadow-md hover:bg-blue-700 transition-all active:scale-95">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center gap-2 px-8 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-all">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function hitungLaba() {
            let beli = parseFloat(document.getElementById('harga_beli').value) || 0;
            let jual = parseFloat(document.getElementById('harga_jual').value) || 0;

            let laba = jual - beli;
            document.getElementById('laba_otomatis').value = laba;

            let warning = document.getElementById('warning_rugi');
            if (jual < beli && jual !== 0) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        document.getElementById('harga_beli').addEventListener('input', hitungLaba);
        document.getElementById('harga_jual').addEventListener('input', hitungLaba);

        // Langsung hitung saat halaman diedit terbuka
        window.addEventListener('load', hitungLaba);
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>

</x-app-layout>