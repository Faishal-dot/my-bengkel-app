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
        /* Style tambahan untuk preview */
        .preview-container img {
            max-height: 250px;
            margin: 0 auto;
            border-radius: 0.75rem;
            display: block;
        }
        /* Menghilangkan spinner pada input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 animate-enter">
            <i data-lucide="credit-card" class="w-7 h-7 text-blue-600"></i>
            Konfirmasi Pembayaran
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
                                <i data-lucide="file-text" class="w-5 h-5"></i> Rincian Tagihan
                            </h3>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium tracking-wide">INV-{{ $booking->id }}</span>
                        </div>

                        <div class="p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-blue-50 p-4 rounded-xl mb-6 border border-blue-100">
                                <div>
                                    <p class="text-gray-500 text-sm">Nomor Antrian</p>
                                    <p class="text-3xl font-bold text-blue-700">#{{ $booking->queue_number }}</p>
                                </div>
                                <div class="mt-2 sm:mt-0 text-right">
                                    <p class="text-gray-500 text-sm">Total Biaya</p>
                                    <div class="flex flex-col items-end">
                                        @if($booking->service->discount_price)
                                            <span class="text-sm text-gray-400 line-through">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</span>
                                            <p class="text-2xl font-bold text-rose-600">Rp {{ number_format($booking->service->discount_price, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 font-bold mb-1">Customer</p>
                                    <p class="font-semibold text-gray-800 text-lg">{{ $booking->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 font-bold mb-1">Layanan</p>
                                    <p class="font-semibold text-gray-800 text-lg">{{ $booking->service->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 font-bold mb-1">Kendaraan</p>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-800 text-lg">{{ $booking->vehicle->brand ?? '-' }}</span>
                                        <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded font-mono">
                                            {{ $booking->vehicle->plate_number ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 font-bold mb-1">Tanggal Booking</p>
                                    <p class="font-semibold text-gray-800 text-lg">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Info Rekening Tujuan --}}
                    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i data-lucide="landmark" class="w-5 h-5 text-yellow-600"></i> Rekening Tujuan Transfer
                        </h3>
                        
                        <div class="relative w-full max-w-md mx-auto bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-2xl overflow-hidden transform transition hover:scale-[1.02] duration-300">
                            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
                            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-blue-500 opacity-10 rounded-full blur-2xl"></div>

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
                                    <p class="text-3xl font-mono tracking-widest text-yellow-400 font-bold" id="rek-num">1234 5678 9012</p>
                                    <button onclick="navigator.clipboard.writeText('123456789012'); alert('No Rekening Disalin!')" class="text-gray-400 hover:text-white transition" title="Salin">
                                        <i data-lucide="copy" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between items-end relative z-10">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase">Atas Nama</p>
                                    <p class="font-semibold text-sm tracking-wide">BENGKEL OTO SURABAYA</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 uppercase">Admin Kontak</p>
                                    <p class="font-semibold text-sm">0812-3456-7890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM UPLOAD --}}
                <div class="lg:col-span-1 animate-enter" style="animation-delay: 0.2s;">
                    <div class="bg-white shadow-xl rounded-2xl p-8 h-fit border border-gray-100 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2 pb-4 border-b border-gray-100">
                            <i data-lucide="upload-cloud" class="w-5 h-5 text-blue-600"></i> Form Konfirmasi
                        </h3>

                        <form action="{{ route('customer.payment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bank Anda (Pengirim)</label>
                                <div class="relative group">
                                    <i data-lucide="building-2" class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition"></i>
                                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Contoh: BRI, Mandiri, BCA"
                                        class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekening Anda</label>
                                <div class="relative group">
                                    <i data-lucide="hash" class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition"></i>
                                    {{-- Modifikasi di sini: Ditambahkan maxlength 16 dan oninput untuk membatasi input --}}
                                    <input type="number" name="account_number" value="{{ old('account_number') }}" 
                                        oninput="if (this.value.length > 16) this.value = this.value.slice(0, 16);" 
                                        placeholder="Maksimal 16 digit"
                                        class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-sm" required>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">* Maksimal 16 digit angka</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                <div class="relative group">
                                    <i data-lucide="user" class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition"></i>
                                    <input type="text" name="account_holder" value="{{ old('account_holder') }}" placeholder="Sesuai nama di ATM/Buku Tabungan"
                                        class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah Bukti Transfer</label>
                                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer relative group overflow-hidden">
                                    <input type="file" name="proof" id="proof-input" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required onchange="handlePreview(this)">
                                    
                                    <div id="upload-placeholder" class="flex flex-col items-center justify-center py-4">
                                        <div class="bg-blue-100 p-3 rounded-full mb-3 text-blue-600 group-hover:scale-110 transition">
                                            <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Pilih Foto Bukti</p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter font-bold text-rose-500">Maksimal 2MB (JPG/PNG)</p>
                                    </div>

                                    <div id="preview-container" class="hidden">
                                        <img id="image-preview" src="#" alt="Preview" class="w-full rounded-lg shadow-sm border border-gray-200 mb-2 object-contain bg-gray-50" style="max-height: 200px;">
                                        <div class="flex items-center justify-center gap-2 text-blue-600 text-xs font-bold bg-white/80 py-1 rounded-md">
                                            <i data-lucide="refresh-cw" class="w-3 h-3 animate-spin-slow"></i> Ganti Foto
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="check-square" class="w-5 h-5"></i>
                                Kirim Konfirmasi
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

        // Fungsi Preview Gambar & Validasi Ukuran
        function handlePreview(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            const dropZone = document.getElementById('drop-zone');

            if (input.files && input.files[0]) {
                const fileSize = input.files[0].size / 1024 / 1024; // MB
                
                if (fileSize > 2) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = "";
                    return;
                }

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