<x-app-layout>
    {{-- Header Halaman (Navigasi Atas) --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                
                {{-- LOGIKA TOMBOL BALIK (UPDATED) --}}
                @php
                    $backUrl = match(auth()->user()->role) {
                        'mechanic' => route('mechanic.jobs.index'),   // <--- MEKANIK BALIK KE LIST PEKERJAAN
                        'admin'    => route('admin.bookings.index'),  // Admin balik ke List Booking
                        default    => route('customer.booking.index'), // Customer balik ke Riwayat Booking
                    };
                @endphp

                <a href="{{ $backUrl }}" class="group p-2.5 bg-white rounded-xl border border-gray-200 hover:border-blue-400 hover:text-blue-600 text-gray-500 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                
                <div>
                    <h2 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                        Diskusi Perbaikan
                        <span class="bg-blue-50 text-blue-600 text-[11px] px-2.5 py-0.5 rounded-full border border-blue-100 font-extrabold tracking-wide">
                            #{{ $booking->id }}
                        </span>
                    </h2>
                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                        <span class="flex items-center gap-1 bg-gray-100 px-2 py-0.5 rounded text-xs font-medium">
                            <i data-lucide="car" class="w-3 h-3"></i> {{ $booking->vehicle->brand }}
                        </span>
                        <span class="text-gray-300">|</span>
                        <span class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $booking->vehicle->plate_number }}</span>
                    </div>
                </div>
            </div>

            {{-- TOMBOL CONTROL (Khusus Admin/Mekanik) --}}
            @if(auth()->user()->role == 'mechanic' || auth()->user()->role == 'admin')
                <form action="{{ route('chat.toggle', $booking->id) }}" method="POST">
                    @csrf
                    @if($booking->is_chat_active)
                        <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 hover:border-red-300 transition font-bold text-xs shadow-sm uppercase tracking-wider">
                            <i data-lucide="lock" class="w-4 h-4"></i> Tutup Sesi
                        </button>
                    @else
                        <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-bold text-xs shadow-lg shadow-emerald-500/30 uppercase tracking-wider">
                            <i data-lucide="unlock" class="w-4 h-4"></i> Buka Sesi
                        </button>
                    @endif
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8 min-h-screen bg-[#F1F5F9]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- CONTAINER UTAMA CHAT --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200 border border-gray-200 overflow-hidden flex flex-col h-[75vh] md:h-[80vh] relative">
                
                {{-- 1. HEADER LAYANAN (WARNA BIRU) --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-5 text-white flex justify-between items-center shadow-md z-10 relative overflow-hidden">
                    
                    {{-- Dekorasi Background --}}
                    <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-5 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-2 -ml-2 w-20 h-20 bg-blue-400 opacity-10 rounded-full blur-xl"></div>

                    <div class="flex items-center gap-4 relative z-10">
                        <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl border border-white/20 shadow-inner">
                            <i data-lucide="wrench" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-100 uppercase font-bold tracking-widest mb-0.5 opacity-80">Jenis Layanan</p>
                            <p class="font-bold text-lg text-white tracking-wide leading-tight">{{ $booking->service->name }}</p>
                        </div>
                    </div>

                    <div class="text-right relative z-10">
                        @if($booking->is_chat_active)
                            <div class="flex items-center gap-2 bg-emerald-500/20 border border-emerald-400/30 px-3 py-1.5 rounded-full backdrop-blur-md shadow-sm">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                                </span>
                                <span class="text-xs font-bold text-emerald-50 tracking-wide">ONLINE</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 bg-slate-900/30 border border-slate-500/30 px-3 py-1.5 rounded-full backdrop-blur-md">
                                <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-300"></i>
                                <span class="text-xs font-bold text-slate-200 tracking-wide">TERKUNCI</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 2. ISI PESAN (SCROLL AREA) --}}
                <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 bg-[#F8FAFC]" id="chatContainer">
                    
                    {{-- Penanda Tanggal --}}
                    <div class="flex justify-center my-4">
                        <span class="bg-gray-200 text-gray-500 text-[10px] font-bold px-3 py-1 rounded-full border border-gray-300/50 shadow-sm">
                            Dimulai {{ $booking->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    @forelse($booking->messages as $msg)
                        @php
                            $isMe = $msg->user_id == auth()->id();
                            
                            // Warna Badge Role
                            $roleLabel = match($msg->user->role) {
                                'admin' => 'Admin',
                                'mechanic' => 'Mekanik',
                                'customer' => 'Customer',
                                default => 'User'
                            };

                            $roleBadgeColor = match($msg->user->role) {
                                'admin' => 'bg-rose-100 text-rose-700 border-rose-200',
                                'mechanic' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'customer' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                default => 'bg-gray-100 text-gray-700'
                            };

                            // Inisial untuk Avatar
                            $initials = collect(explode(' ', $msg->user->name))->map(function($w) { return strtoupper(substr($w, 0, 1)); })->take(2)->join('');
                        @endphp

                        <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }} items-end gap-3 group">
                            
                            {{-- AVATAR (Hanya untuk Lawan Bicara) --}}
                            @if(!$isMe)
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500 shadow-sm mb-4">
                                    {{ $initials }}
                                </div>
                            @endif

                            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[85%] md:max-w-[70%]">
                                
                                {{-- NAMA & ROLE --}}
                                <div class="flex items-center gap-2 mb-1.5 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                                    <span class="text-xs font-bold text-gray-600">{{ $isMe ? 'Anda' : $msg->user->name }}</span>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded border uppercase font-bold tracking-wider {{ $roleBadgeColor }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>

                                {{-- BUBBLE PESAN --}}
                                <div class="px-5 py-3.5 shadow-sm relative text-sm leading-relaxed transition-all
                                    {{ $isMe 
                                        ? 'bg-blue-600 text-white rounded-2xl rounded-tr-sm shadow-blue-500/10' 
                                        : 'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-tl-sm' 
                                    }}">
                                    
                                    {{ $msg->message }}

                                    {{-- Waktu --}}
                                    <div class="text-[10px] font-medium mt-1.5 text-right opacity-70 {{ $isMe ? 'text-blue-100' : 'text-gray-400' }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 opacity-60">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="message-square" class="w-10 h-10 text-gray-300"></i>
                            </div>
                            <p class="text-sm font-medium">Belum ada pesan.</p>
                            <p class="text-xs">Jadilah yang pertama mengirim pesan.</p>
                        </div>
                    @endforelse

                </div>

                {{-- 3. INPUT AREA --}}
                <div class="p-4 bg-white border-t border-gray-100 z-20">
                    @if($booking->is_chat_active)
                        <form action="{{ route('chat.send', $booking->id) }}" method="POST" class="flex items-end gap-3 relative">
                            @csrf
                            
                            {{-- Input Text --}}
                            <div class="flex-1 relative group">
                                <textarea name="message" rows="1" required placeholder="Tulis pesan..." 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 pl-5 pr-12 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 resize-none transition-all text-sm shadow-inner text-gray-700 placeholder-gray-400"></textarea>
                                
                                {{-- Ikon Dekoratif di dalam input --}}
                                <div class="absolute right-3 bottom-3 text-gray-400">
                                    <i data-lucide="edit-3" class="w-4 h-4 opacity-50"></i>
                                </div>
                            </div>

                            {{-- Tombol Kirim --}}
                            <button type="submit" class="p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-lg shadow-blue-500/30 transition-all hover:scale-105 active:scale-95 flex items-center justify-center group">
                                <i data-lucide="send-horizontal" class="w-5 h-5 group-hover:translate-x-0.5 transition-transform"></i>
                            </button>
                        </form>
                    @else
                        {{-- Tampilan Terkunci --}}
                        <div class="flex flex-col items-center justify-center gap-3 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-500">
                            <div class="p-2 bg-slate-200 rounded-full">
                                <i data-lucide="lock" class="w-5 h-5 text-slate-500"></i>
                            </div>
                            <div class="text-center">
                                <span class="block text-sm font-bold text-slate-700">Sesi chat ditutup</span>
                                <span class="text-xs text-slate-400">Pesan tidak dapat dikirim saat ini.</span>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Fitur Auto Scroll ke Pesan Terbawah
        const chatContainer = document.getElementById('chatContainer');
        if(chatContainer) {
            // Scroll halus ke bawah saat halaman dimuat
            setTimeout(() => {
                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }, 100);
        }
    </script>
</x-app-layout>