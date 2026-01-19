<x-app-layout>
    {{-- Custom Styles & Animation --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .font-premium { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeDown {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        .animate-fade-in-down { animation: fadeDown 0.6s ease-out forwards; }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .fintech-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }
        .fintech-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            transform: rotate(30deg);
        }
        .modal-active { overflow: hidden; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 animate-fade-in-down">
            <i data-lucide="shield-check" class="w-6 h-6 text-blue-600"></i>
            Verifikasi Pembayaran
        </h2>
    </x-slot>

    <div class="font-premium py-12 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BREADCRUMB --}}
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-4 px-1 animate-fade-in-down">
                <a href="{{ route('admin.payments.index') }}" class="hover:text-blue-600 transition-colors">Payments</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gray-800 font-medium">Validation Center</span>
            </div>

            {{-- HEADER CARD --}}
            <div class="bg-white/80 backdrop-blur shadow-xl rounded-2xl p-8 mb-8 border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 flex flex-col md:flex-row md:items-center justify-between gap-6 animate-fade-up">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                            Konfirmasi Validasi
                            @if(strtolower($payment->status) == 'pending')
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                                </span>
                            @endif
                        </h1>
                        <p class="text-gray-500 text-sm mt-1 font-medium italic">Silahkan periksa bukti transfer di bawah ini.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                     <div class="px-5 py-3 bg-blue-50 rounded-2xl border border-blue-100 flex flex-col items-end shadow-sm">
                        <span class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-1">Invoice ID</span>
                        <span class="font-mono font-bold text-blue-800 text-lg">
                            #INV-{{ $payment->booking_id ? $payment->booking->id : $payment->order_id }}
                        </span>
                     </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-8 space-y-6">
                    
                    {{-- FINTECH CARD --}}
                    <div class="fintech-card rounded-3xl shadow-2xl p-8 text-white animate-fade-up transition-transform hover:-translate-y-1 duration-500">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-2">TOTAL YANG DIBAYARKAN</p>
                                    <h2 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white">
                                        <span class="text-emerald-400 text-2xl align-top mr-1">Rp</span>
                                        {{ number_format($payment->amount, 0, ',', '.') }}
                                    </h2>
                                </div>

                                {{-- STATUS BADGE --}}
                                <div class="flex flex-col items-end animate-fade-in-down">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Status</span>
                                    <div class="px-4 py-2 rounded-xl border {{ in_array(strtoupper($payment->status), ['PAID', 'CONFIRMED', 'LUNAS']) ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400' : (strtolower($payment->status) == 'pending' ? 'bg-yellow-500/20 border-yellow-500/50 text-yellow-400' : 'bg-rose-500/20 border-rose-500/50 text-rose-400') }} flex items-center gap-2 backdrop-blur-md">
                                        <i data-lucide="{{ in_array(strtoupper($payment->status), ['PAID', 'CONFIRMED', 'LUNAS']) ? 'check-circle' : 'alert-circle' }}" class="w-4 h-4"></i>
                                        <span class="text-xs font-black uppercase tracking-wider">{{ $payment->status }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-white/10">
                                <div>
                                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Bank Pengirim</p>
                                    <p class="text-lg font-bold text-white tracking-wide">{{ $payment->bank_name }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">No. Rekening</p>
                                    <p class="text-lg font-mono text-white tracking-wider opacity-90">{{ $payment->account_number }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Atas Nama</p>
                                    <p class="text-lg font-bold text-white truncate">{{ $payment->account_holder }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DATA PEMESAN --}}
                    <div class="bg-white/80 backdrop-blur rounded-3xl shadow-xl border border-gray-100 overflow-hidden animate-fade-up hover:shadow-2xl transition-all duration-500">
                        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i data-lucide="user-circle" class="w-5 h-5 text-blue-600"></i>
                                Data Pelanggan & Detail Pesanan
                            </h3>
                        </div>
                        <div class="p-8">
                            @php
                                $user = $payment->booking->user ?? $payment->order->user ?? null;
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                        {{ substr($user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Nama Lengkap</p>
                                        <p class="text-gray-900 font-bold text-lg">{{ $user->name ?? 'Unknown' }}</p>
                                        <p class="text-gray-500 text-sm">{{ $user->email ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- Kondisional Tampilan: Kendaraan (Booking) atau Tipe Pesanan (Product) --}}
                                @if($payment->booking_id)
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                            <i data-lucide="car" class="w-6 h-6"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Kendaraan</p>
                                            <p class="text-gray-900 font-bold text-lg">{{ $payment->booking->vehicle->brand ?? '-' }}</p>
                                            <span class="text-xs font-mono font-bold text-gray-500 uppercase">{{ $payment->booking->vehicle->plate_number ?? '-' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Tipe Transaksi</p>
                                            <p class="text-gray-900 font-bold text-lg">Pembelian Produk</p>
                                            <span class="text-xs font-bold text-purple-500 uppercase tracking-tighter">Marketplace Order</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- INFO LAYANAN / PRODUK --}}
                            <div class="bg-blue-50/50 rounded-2xl px-6 py-5 border border-blue-100/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <i data-lucide="{{ $payment->booking_id ? 'wrench' : 'package' }}" class="w-5 h-5 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-[10px] font-bold uppercase block">Item yang dibayar:</span>
                                        <span class="text-blue-900 font-bold text-lg">
                                            @if($payment->booking_id)
                                                {{ $payment->booking->service->name ?? 'Layanan Tidak Ditemukan' }}
                                            @else
                                                {{ $payment->order->product->name ?? 'Produk Tidak Ditemukan' }} 
                                                <span class="text-sm font-normal text-blue-600">(x{{ $payment->order->quantity }})</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-blue-50">
                                    <i data-lucide="info" class="w-4 h-4 text-blue-400"></i>
                                    <span class="text-xs text-blue-600 font-bold">Data Terverifikasi Sistem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-4 space-y-6 animate-fade-up">
                    {{-- BUKTI TRANSFER --}}
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col transition-all hover:shadow-2xl duration-500">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-sm">Bukti Transfer</h3>
                            <i data-lucide="image" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <div class="p-3">
                            <div onclick="openModal('{{ Storage::url($payment->proof) }}')" class="relative group rounded-2xl overflow-hidden border border-gray-200 aspect-[3/4] cursor-zoom-in">
                                <img src="{{ Storage::url($payment->proof) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center backdrop-blur-[2px]">
                                    <span class="bg-white text-gray-900 text-xs font-bold px-4 py-2 rounded-full shadow-lg">Perbesar Gambar</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TINDAKAN ADMIN --}}
                    <div class="sticky top-6">
                        @if(strtolower($payment->status) == 'pending')
                            <div class="glass-card rounded-3xl p-6 shadow-xl border border-blue-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                    <i data-lucide="check-square" class="w-5 h-5 text-blue-600"></i>
                                    Tindakan Admin
                                </h3>
                                <div class="space-y-3">
                                    <form action="{{ route('admin.payments.confirm', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold py-4 rounded-xl shadow-lg transition-all hover:-translate-y-1 hover:shadow-emerald-200">
                                            <i data-lucide="check" class="w-5 h-5"></i>
                                            <span>Konfirmasi Terima</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-600 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 font-bold py-3.5 rounded-xl transition-all">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                            <span>Tolak Pembayaran</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        <a href="{{ route('admin.payments.index') }}" class="mt-4 flex items-center justify-center gap-2 text-gray-500 hover:text-blue-600 transition-colors font-semibold text-sm">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL LIGHTBOX --}}
    <div id="imageModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/95 backdrop-blur-md" onclick="closeModal()">
        <div class="relative max-w-4xl w-full flex justify-center">
            <img id="modalImage" src="" class="max-h-[90vh] rounded-xl shadow-2xl transition-all duration-300 transform scale-95 opacity-0">
            <button class="absolute -top-12 right-0 text-white flex items-center gap-2 font-bold">
                <i data-lucide="x" class="w-6 h-6"></i> Tutup
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> 
        lucide.createIcons(); 
        function openModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = src;
            modal.classList.remove('hidden');
            setTimeout(() => {
                img.classList.remove('scale-95', 'opacity-0');
                img.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.body.classList.add('modal-active');
        }
        function closeModal() {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            document.body.classList.remove('modal-active');
        }
    </script>
</x-app-layout>