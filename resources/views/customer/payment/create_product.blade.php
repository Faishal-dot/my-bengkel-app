<x-app-layout>
    {{-- Custom CSS untuk Animasi --}}
    <style>
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-enter {
            opacity: 0;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 animate-enter">
            <i data-lucide="package" class="w-7 h-7 text-blue-600"></i>
            Konfirmasi Pembayaran Produk
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. Alert Notifications --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-xl flex items-center gap-3 shadow-sm border border-green-200 animate-enter">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 text-red-800 p-4 rounded-xl shadow-sm border border-red-200 animate-enter">
                    <div class="flex items-center gap-2 mb-2 font-bold">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                        <span>Mohon Perbaiki Kesalahan Berikut:</span>
                    </div>
                    <ul class="list-disc pl-8 space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: INFO DETAIL & REKENING --}}
                <div class="lg:col-span-2 space-y-8 animate-enter" style="animation-delay: 0.1s;">
                    
                    {{-- Card 1: Detail Tagihan --}}
                    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Rincian Pesanan
                            </h3>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium tracking-wide">ORD-{{ $order->id }}</span>
                        </div>

                        <div class="p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-blue-50 p-4 rounded-xl mb-6 border border-blue-100">
                                <div>
                                    <p class="text-gray-500 text-sm">Status Pesanan</p>
                                    <p class="text-xl font-bold text-blue-700 uppercase tracking-tight">{{ $order->status ?? 'Menunggu Pembayaran' }}</p>
                                </div>
                                <div class="mt-2 sm:mt-0 text-right">
                                    <p class="text-gray-500 text-sm">Total Belanja</p>
                                    <p class="text-2xl font-bold text-rose-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Item Produk (FIXED: Membaca dari OrderDetails Database) --}}
                            <div class="space-y-4">
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-bold mb-2">Item yang dibeli:</p>
                                
                                @if($order->orderDetails && $order->orderDetails->count() > 0)
                                    {{-- LOOPING SEMUA PRODUK DARI DATABASE --}}
                                    @foreach($order->orderDetails as $detail)
                                    <div class="flex justify-between items-center p-3 rounded-xl border border-gray-50 bg-gray-50/50 mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-white rounded-lg shadow-sm">
                                                <i data-lucide="box" class="w-5 h-5 text-blue-400"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $detail->product->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $detail->quantity }} Item x Rp {{ number_format($detail->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <p class="font-bold text-gray-700">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</p>
                                    </div>
                                    @endforeach
                                @else
                                    {{-- Fallback jika order_details kosong (untuk data lama) --}}
                                    <div class="flex justify-between items-center p-3 rounded-xl border border-gray-50 bg-gray-50/50">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-white rounded-lg shadow-sm">
                                                <i data-lucide="box" class="w-5 h-5 text-gray-400"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $order->product->name ?? 'Produk' }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->quantity }} Item x Rp {{ number_format(($order->total_price / ($order->quantity ?: 1)), 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <p class="font-bold text-gray-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Info Rekening --}}
                    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i data-lucide="landmark" class="w-5 h-5 text-yellow-600"></i> Rekening Tujuan
                        </h3>
                        
                        <div class="relative w-full max-w-md mx-auto bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-2xl overflow-hidden transform transition hover:scale-[1.02] duration-300">
                            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
                            
                            <div class="flex justify-between items-start mb-8 relative z-10">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Bank Transfer</p>
                                    <h4 class="text-2xl font-bold tracking-wider">BCA</h4>
                                </div>
                                <i data-lucide="wifi" class="w-8 h-8 opacity-50"></i>
                            </div>

                            <div class="mb-8 relative z-10">
                                <p class="text-xs text-gray-400 mb-2">Nomor Rekening</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-3xl font-mono tracking-widest text-yellow-400 font-bold">1234 5678 9012</p>
                                    <button onclick="navigator.clipboard.writeText('123456789012'); alert('No Rekening Disalin!')" class="text-gray-400 hover:text-white transition">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between items-end relative z-10">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase">Atas Nama</p>
                                    <p class="font-semibold text-sm tracking-wide uppercase">BengkelKu Official</p>
                                </div>
                                <i data-lucide="shield-check" class="w-6 h-6 text-blue-400 opacity-80"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM UPLOAD --}}
                <div class="lg:col-span-1 animate-enter" style="animation-delay: 0.2s;">
                    <div class="bg-white shadow-xl rounded-2xl p-8 h-fit border border-gray-100 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2 pb-4 border-b border-gray-100">
                            <i data-lucide="upload-cloud" class="w-5 h-5 text-blue-600"></i> Upload Bukti
                        </h3>

                        <form action="{{ route('customer.payment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank Pengirim</label>
                                <div class="relative group">
                                    <i data-lucide="building-2" class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition"></i>
                                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Contoh: BRI, Mandiri"
                                        class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekening Anda</label>
                                <div class="relative group">
                                    <i data-lucide="hash" class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition"></i>
                                    <input type="number" name="account_number" value="{{ old('account_number') }}" placeholder="Nomor rekening Anda"
                                        class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                <div class="relative group">
                                    <i data-lucide="user" class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition"></i>
                                    <input type="text" name="account_holder" value="{{ old('account_holder') }}" placeholder="Sesuai buku tabungan"
                                        class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Bukti Transfer</label>
                                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer relative group">
                                    <input type="file" name="proof" id="proof-input" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required onchange="handlePreview(this)">
                                    
                                    <div id="upload-placeholder" class="flex flex-col items-center justify-center py-4">
                                        <div class="bg-blue-100 p-3 rounded-full mb-3 text-blue-600 group-hover:scale-110 transition">
                                            <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk upload foto</p>
                                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    </div>

                                    <div id="preview-container" class="hidden">
                                        <img id="image-preview" src="#" alt="Preview" class="w-full rounded-lg shadow-sm border border-gray-200 mb-2">
                                        <div class="flex items-center justify-center gap-2 text-blue-600 text-xs font-bold">
                                            <i data-lucide="refresh-cw" class="w-3 h-3"></i> Ganti Foto
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-5 h-5"></i>
                                Konfirmasi Pembayaran
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> 
        lucide.createIcons(); 

        function handlePreview(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            const dropZone = document.getElementById('drop-zone');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    dropZone.classList.remove('p-4');
                    dropZone.classList.add('p-2');
                    lucide.createIcons();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>