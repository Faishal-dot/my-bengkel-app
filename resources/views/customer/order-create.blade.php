<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Animasi kustom untuk angka yang berubah */
        .value-pop {
            animation: pulse-blue 0.4s ease-out;
        }
        @keyframes pulse-blue {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); color: #2563eb; }
            100% { transform: scale(1); }
        }
    </style>

    <div class="min-h-screen bg-slate-50 py-10 px-4">
        <div class="max-w-6xl mx-auto">

            {{-- Back - Animasi Slide In Left --}}
            <div class="animate__animated animate__fadeInLeft animate__faster">
                <a href="{{ route('customer.products') }}"
                   class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 mb-6 transition-colors group">
                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Katalog
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- LEFT - Animasi Fade In Up --}}
                <div class="lg:col-span-8 space-y-6 animate__animated animate__fadeInUp">

                    {{-- Product --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-7 shadow-sm hover:shadow-md transition-shadow">
                        <span class="inline-block mb-3 px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-600 animate__animated animate__bounceIn animate__delay-1s">
                            Detail Produk
                        </span>

                        <h1 class="text-3xl font-extrabold text-slate-900 mb-3">
                            {{ $product->name }}
                        </h1>

                        <p class="text-slate-600 leading-relaxed mb-6">
                            {{ $product->description ?? 'Produk berkualitas tinggi untuk performa kendaraan Anda.' }}
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 group hover:bg-blue-50 transition-colors">
                                <i data-lucide="boxes" class="w-5 h-5 text-slate-500 group-hover:text-blue-600 transition-colors"></i>
                                <div>
                                    <p class="text-xs text-slate-400">Stok</p>
                                    <p class="font-bold text-slate-800">{{ $product->stock }} pcs</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 group hover:bg-amber-50 transition-colors">
                                <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400 group-hover:scale-110 transition-transform"></i>
                                <div>
                                    <p class="text-xs text-slate-400">Rating</p>
                                    <p class="font-bold text-slate-800">4.9 / 5</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-7 shadow-sm">
                        <label class="block text-sm font-semibold text-slate-700 mb-3 tracking-wide">
                            JUMLAH PEMBELIAN
                        </label>

                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                            <input
                                type="number"
                                name="quantity"
                                form="orderForm"
                                id="quantityInput"
                                value="1"
                                min="1"
                                max="{{ $product->stock }}"
                                class="w-32 text-center text-xl font-bold rounded-xl border-slate-300 focus:ring-blue-500 focus:border-blue-500 transition-all focus:scale-105"
                            >

                            <div class="flex items-start gap-2 text-xs text-blue-700 bg-blue-50 border border-blue-100 rounded-xl p-3 animate__animated animate__headShake animate__delay-2s">
                                <i data-lucide="info" class="w-4 h-4 mt-0.5"></i>
                                Stok saat ini tersedia: {{ $product->stock }} unit.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT - Animasi Fade In Right --}}
                <div class="lg:col-span-4 animate__animated animate__fadeInRight">
                    <div class="sticky top-10 bg-white border border-slate-200 rounded-2xl p-6 shadow-lg shadow-blue-900/5">

                        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i>
                            Ringkasan Pesanan
                        </h2>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between border-b border-slate-50 pb-2">
                                <span class="text-slate-500">Harga Satuan</span>
                                <span class="font-semibold text-slate-800">
                                    Rp {{ number_format($product->price,0,',','.') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Jumlah</span>
                                <span class="font-bold text-blue-600 px-2 py-1 bg-blue-50 rounded-lg transition-all" id="summaryQty">1x</span>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <p class="text-xs text-slate-400 mb-1 tracking-wider uppercase">Total Pembayaran</p>
                                <p class="text-2xl font-black text-slate-900 transition-all" id="totalDisplay">
                                    Rp {{ number_format($product->price,0,',','.') }}
                                </p>
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('customer.orders.store', $product->id) }}"
                              id="orderForm"
                              class="mt-6">
                            @csrf
                            <button id="btnPesan"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold flex justify-center items-center gap-2 transition-all active:scale-95 shadow-md hover:shadow-blue-200">
                                <span id="btnText">Pesan Sekarang</span>
                                <i data-lucide="arrow-right" id="btnIcon" class="w-5 h-5 transition-transform group-hover:translate-x-1"></i>
                                <span id="btnLoading" class="hidden animate-spin">
                                    <i data-lucide="loader-2" class="w-5 h-5"></i>
                                </span>
                            </button>
                        </form>

                        <div class="flex items-center gap-2 justify-center mt-4">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                            <p class="text-[10px] text-slate-400 uppercase tracking-tighter font-semibold">
                                Transaksi Aman & Terenkripsi
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();

            const quantityInput = document.getElementById('quantityInput');
            const summaryQty = document.getElementById('summaryQty');
            const totalDisplay = document.getElementById('totalDisplay');
            const price = {{ $product->price }};

            quantityInput.addEventListener('input', function () {
                let qty = parseInt(this.value) || 0;
                if (qty < 1) qty = 1;
                if (qty > {{ $product->stock }}) qty = {{ $product->stock }};
                
                // Efek animasi saat angka berubah
                summaryQty.classList.remove('value-pop');
                totalDisplay.classList.remove('value-pop');
                void summaryQty.offsetWidth; // Trigger reflow
                summaryQty.classList.add('value-pop');
                totalDisplay.classList.add('value-pop');

                summaryQty.innerText = qty + 'x';
                totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(qty * price);
            });
        });

        document.getElementById("orderForm").addEventListener("submit", function (e) {
            const btnPesan = document.getElementById('btnPesan');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const btnLoading = document.getElementById('btnLoading');

            btnPesan.disabled = true;
            btnPesan.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.innerText = "Memproses...";
            btnIcon.classList.add("hidden");
            btnLoading.classList.remove("hidden");
        });
    </script>
</x-app-layout>