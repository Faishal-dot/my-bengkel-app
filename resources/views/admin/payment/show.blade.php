<x-app-layout>
    {{-- Custom Styles & Animation --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        /* Base Font Override for Premium Feel */
        .font-premium { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Smooth Entry Animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .animate-fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        /* Stagger Delays */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        /* Premium Glass Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        /* Fintech Card Gradient */
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

        /* Status Badge Pulse */
        .status-dot {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>

    <div class="font-premium min-h-screen bg-[#F3F4F6] pb-20 relative">
        
        {{-- Background Decoration (Abstract Blobs) --}}
        <div class="fixed top-0 left-0 w-full h-96 bg-gradient-to-b from-blue-50 to-transparent pointer-events-none z-0"></div>
        <div class="fixed top-20 right-20 w-64 h-64 bg-purple-200/40 rounded-full blur-3xl pointer-events-none z-0"></div>
        <div class="fixed top-40 left-20 w-72 h-72 bg-blue-200/40 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-up">
                <div>
                    <div class="flex items-center gap-3 text-sm text-gray-500 mb-1">
                        <a href="{{ route('admin.payments.index') }}" class="hover:text-blue-600 transition-colors">Payments</a>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <span class="text-gray-800 font-medium">Validation</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                        Verifikasi Pembayaran
                        @if($payment->status == 'pending')
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                            </span>
                        @endif
                    </h1>
                </div>
                
                <div class="flex items-center gap-3">
                     <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col items-end">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Invoice ID</span>
                        <span class="font-mono font-bold text-gray-800">#INV-{{ $payment->booking->id }}</span>
                     </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- LEFT COLUMN (Details) --}}
                <div class="lg:col-span-8 space-y-6">
                    
                    {{-- 1. HERO CARD (Payment Details) - Fintech Style --}}
                    <div class="fintech-card rounded-3xl shadow-2xl shadow-slate-200 p-8 text-white animate-fade-up delay-100 group">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-2">TOTAL TRANSFER</p>
                                    <h2 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white group-hover:scale-[1.02] transition-transform duration-500 origin-left">
                                        <span class="text-emerald-400 text-2xl align-top mr-1">Rp</span>{{ number_format($payment->amount, 0, ',', '.') }}
                                    </h2>
                                </div>
                                <div class="p-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                                    <i data-lucide="wallet" class="w-8 h-8 text-emerald-400"></i>
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

                    {{-- 2. CUSTOMER & SERVICE INFO --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden animate-fade-up delay-200">
                        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i data-lucide="user-circle" class="w-5 h-5 text-blue-600"></i>
                                Data Pemesan
                            </h3>
                            <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Pelanggan Terdaftar</span>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                            {{-- Customer --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg shadow-inner">
                                    {{ substr($payment->booking->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Nama Lengkap</p>
                                    <p class="text-gray-900 font-bold text-lg">{{ $payment->booking->user->name }}</p>
                                    <p class="text-gray-500 text-sm mt-0.5">{{ $payment->booking->user->email }}</p>
                                </div>
                            </div>

                            {{-- Vehicle --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center text-orange-600 shadow-inner">
                                    <i data-lucide="car" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Kendaraan</p>
                                    <p class="text-gray-900 font-bold text-lg">{{ $payment->booking->vehicle->brand }}</p>
                                    <span class="inline-block mt-1 bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded border border-gray-200 font-mono font-bold">
                                        {{ $payment->booking->vehicle->plate_number }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Service Row --}}
                        <div class="bg-blue-50/50 px-8 py-4 border-t border-blue-100/50 flex items-center gap-3">
                            <div class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                <i data-lucide="wrench" class="w-4 h-4"></i>
                            </div>
                            <span class="text-gray-600 text-sm font-medium">Layanan yang dipilih:</span>
                            <span class="text-blue-700 font-bold text-sm">{{ $payment->booking->service->name }}</span>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN (Proof & Actions) --}}
                <div class="lg:col-span-4 space-y-6 animate-fade-up delay-300">
                    
                    {{-- 3. PROOF OF PAYMENT --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-sm">Bukti Transfer</h3>
                            <button onclick="window.open('{{ Storage::url($payment->proof) }}', '_blank')" class="text-xs flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium transition-colors">
                                <i data-lucide="external-link" class="w-3 h-3"></i> Buka Asli
                            </button>
                        </div>
                        
                        <div class="p-2 bg-gray-50">
                            @if($payment->proof)
                                <div class="relative group rounded-2xl overflow-hidden border border-gray-200 shadow-inner aspect-[3/4]">
                                    <img src="{{ Storage::url($payment->proof) }}" 
                                         alt="Bukti Transfer" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    
                                    {{-- Hover Overlay --}}
                                    <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/20 transition-all duration-300 flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                            <span class="bg-white/90 backdrop-blur text-gray-900 text-xs font-bold px-4 py-2 rounded-full shadow-lg flex items-center gap-2">
                                                <i data-lucide="zoom-in" class="w-4 h-4"></i> Zoom
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="aspect-[3/4] flex flex-col items-center justify-center bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 text-gray-400">
                                    <i data-lucide="image-off" class="w-10 h-10 mb-2 opacity-50"></i>
                                    <span class="text-xs font-medium">Tidak ada bukti dilampirkan</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 4. ACTION PANEL (Sticky) --}}
                    <div class="sticky top-6">
                        @if($payment->status == 'pending')
                            <div class="glass-card rounded-3xl p-6 shadow-xl shadow-blue-900/5 relative overflow-hidden">
                                {{-- Background Glow --}}
                                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-blue-400/20 rounded-full blur-2xl"></div>

                                <h3 class="text-lg font-bold text-gray-900 mb-6 relative z-10">Tindakan Validasi</h3>
                                
                                <div class="space-y-3 relative z-10">
                                    {{-- Approve Button --}}
                                    <form action="{{ route('admin.payments.confirm', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full group relative flex items-center justify-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition-all duration-300 transform hover:-translate-y-1 active:translate-y-0">
                                            <div class="bg-white/20 p-1 rounded-full">
                                                <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                            </div>
                                            <span>Konfirmasi & Terima</span>
                                        </button>
                                    </form>

                                    {{-- Reject Button --}}
                                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full group flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-600 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 font-bold py-3.5 rounded-xl transition-all duration-300">
                                            <i data-lucide="x" class="w-4 h-4 transition-transform group-hover:rotate-90"></i>
                                            <span>Tolak Pembayaran</span>
                                        </button>
                                    </form>
                                </div>
                                <p class="text-[10px] text-center text-gray-400 mt-5 leading-relaxed">
                                    Konfirmasi akan memperbarui status booking menjadi <br><strong class="text-emerald-600">Paid/Lunas</strong> secara otomatis.
                                </p>
                            </div>
                        @else
                            {{-- PROCESSED STATE --}}
                            <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-gray-200">
                                @if(in_array(strtolower($payment->status), ['paid', 'approved', 'success', 'lunas']))
                                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                                        <div class="absolute inset-0 bg-emerald-200 rounded-full animate-ping opacity-25"></div>
                                        <i data-lucide="check-check" class="w-10 h-10 text-emerald-600"></i>
                                    </div>
                                    <h4 class="font-bold text-xl text-gray-900">Pembayaran Valid</h4>
                                    <p class="text-sm text-gray-500 mt-2 mb-6">Transaksi ini telah diverifikasi.</p>
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-lg text-xs font-mono text-gray-500">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        {{ $payment->updated_at->format('d M Y • H:i') }}
                                    </div>
                                @else
                                    <div class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i data-lucide="x-circle" class="w-10 h-10 text-rose-600"></i>
                                    </div>
                                    <h4 class="font-bold text-xl text-gray-900">Pembayaran Ditolak</h4>
                                    <p class="text-sm text-gray-500 mt-2">Dana tidak valid atau bukti salah.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Initialize Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>