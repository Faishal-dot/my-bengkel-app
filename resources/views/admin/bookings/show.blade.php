<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 animate-fade-in-down">
            <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i>
            Detail Booking #{{ $booking->id }}
        </h2>
    </x-slot>

    <style>
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

        /* Custom Scrollbar untuk list mekanik */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>

    <div class="py-12 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 animate-fade-up">
            <div class="bg-white/80 backdrop-blur shadow-xl rounded-2xl p-8 space-y-10 border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">

                {{-- Alert sukses --}}
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-200 animate-fade-up">
                        <i data-lucide="check-circle" class="inline w-5 h-5 mr-1"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Informasi Customer --}}
                <div class="animate-fade-up">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Informasi Customer
                    </h3>
                    <div class="text-gray-700 space-y-1">
                        <p><span class="font-semibold">Nama:</span> {{ $booking->customer_name ?? '-' }}</p>
                        <p><span class="font-semibold">Telepon:</span> {{ $booking->customer_phone ?? '-' }}</p>
                        <p><span class="font-semibold">Alamat:</span> {{ $booking->customer_address ?? '-' }}</p>
                        <p><span class="font-semibold">Email Akun:</span> {{ $booking->user->email ?? '-' }}</p>
                    </div>
                </div>

                {{-- Detail Booking --}}
                <div class="animate-fade-up">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="car" class="w-5 h-5"></i>
                        Detail Booking
                    </h3>
                    <div class="space-y-3 text-gray-700">
                        <p>
                            <span class="font-semibold">Nomor Antrian:</span>
                            @if($booking->queue_number)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold text-xs shadow-sm">
                                    #{{ $booking->queue_number }}
                                </span>
                            @else
                                <span class="italic text-gray-400">Belum ada</span>
                            @endif
                        </p>

                        <p>
                            <span class="font-semibold">Kendaraan:</span>
                            @if($booking->vehicle)
                                {{ $booking->vehicle->plate_number }}
                                <span class="text-sm text-gray-500">
                                    ({{ $booking->vehicle->brand ?? '-' }}
                                    {{ $booking->vehicle->model ? '- ' . $booking->vehicle->model : '' }}
                                    {{ $booking->vehicle->year ? '(' . $booking->vehicle->year . ')' : '' }} )
                                </span>
                                {{-- Penambahan Informasi Warna --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 ml-1 capitalize">
                                    Warna: {{ $booking->vehicle->color ?? '-' }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">Tidak ada kendaraan</span>
                            @endif
                        </p>

                        {{-- Layanan & Harga (Sampingan) --}}
                        <p class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold">Layanan:</span>
                            @if($booking->service)
                                <span class="font-medium text-gray-800">{{ $booking->service->name }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-gray-100 text-gray-700 rounded-md text-sm border border-gray-200">
                                    @if($booking->service->discount_price)
                                        <span class="text-gray-400 line-through text-xs">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</span>
                                        <span class="text-rose-600 font-bold">Rp {{ number_format($booking->service->discount_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-bold">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-400 italic">Tidak ada layanan</span>
                            @endif
                        </p>

                        {{-- Tanggal & Jam --}}
                        <p class="flex items-center gap-2">
                            <span class="font-semibold">Jadwal:</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 rounded text-sm text-gray-700 border">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-600 rounded text-sm text-white font-bold shadow-sm">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB
                            </span>
                        </p>

                        <p>
                            <span class="font-semibold">Keluhan:</span>
                            {{ $booking->complaint ?? '-' }}
                        </p>
                    </div>
                </div>

                {{-- Penugasan Mekanik --}}
                <div class="animate-fade-up mt-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                        Penugasan Mekanik
                    </h3>

                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                        <form action="{{ route('admin.bookings.assign', $booking->id) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="relative z-30" id="dropdown-mechanic">
                                <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                    <div class="p-1.5 bg-blue-100 rounded text-blue-600 shadow-sm"><i data-lucide="users" class="w-4 h-4"></i></div>
                                    Pilih/Ganti Mekanik Petugas
                                </label>
                                
                                <input type="hidden" name="mechanic_id" id="mechanic_id_input" required value="{{ old('mechanic_id', $booking->mechanic_id) }}">
                                
                                <button type="button" onclick="toggleDropdown('mechanic')" id="mechanic-button"
                                    class="w-full flex items-center justify-between bg-white border-2 border-blue-100 rounded-2xl px-5 py-4 text-left font-semibold text-gray-700 hover:border-blue-400 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm">
                                    <span id="mechanic-label" class="truncate pr-2 text-sm">
                                        @if($booking->mechanic)
                                            {{ $booking->mechanic->name }} — {{ $booking->mechanic->specialization ?? 'Umum' }}
                                        @else
                                            -- Pilih Mekanik --
                                        @endif
                                    </span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-blue-500 transition-transform duration-300 flex-shrink-0" id="mechanic-icon"></i>
                                </button>
                                
                                <div id="mechanic-list" class="hidden absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-1 ring-1 ring-black ring-opacity-5">
                                    <div onclick="selectOption('mechanic', '', '-- Pilih Mekanik --')"
                                        class="mechanic-item flex items-center px-4 py-3 rounded-xl cursor-pointer transition-all font-medium text-sm text-gray-400 hover:bg-gray-50">
                                        -- Lepas Petugas --
                                    </div>
                                    @foreach ($mechanics as $mechanic)
                                        @php $isSelected = $booking->mechanic_id == $mechanic->id; @endphp
                                        <div onclick="selectOption('mechanic', '{{ $mechanic->id }}', '{{ $mechanic->name }} — {{ $mechanic->specialization ?? 'Umum' }}')"
                                            data-id="{{ $mechanic->id }}"
                                            class="mechanic-item flex items-center px-4 py-3 rounded-xl cursor-pointer transition-all font-medium text-sm {{ $isSelected ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                            {{ $mechanic->name }} — {{ $mechanic->specialization ?? 'Umum' }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full sm:w-auto flex justify-center items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3.5 rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 font-bold text-sm group">
                                <i data-lucide="save" class="w-4 h-4"></i> Simpan Penugasan
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="animate-fade-up">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="bookmark-check" class="w-5 h-5"></i>
                        Ringkasan Booking
                    </h3>

                    <div class="space-y-3 text-gray-700">
                        <p class="flex items-center gap-2">
                            <span class="font-semibold">Status:</span>
                            @php
                                $status = strtolower($booking->status);
                                $statusClasses = [
                                    'disetujui' => 'bg-green-100 text-green-700 border-green-200',
                                    'proses'    => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'selesai'   => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'ditolak'   => 'bg-red-100 text-red-700 border-red-200',
                                    'menunggu'  => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                ];
                                $statusIcons = [
                                    'disetujui' => 'check-circle',
                                    'proses'    => 'loader',
                                    'selesai'   => 'check-circle',
                                    'ditolak'   => 'x-circle',
                                    'menunggu'  => 'clock',
                                    'pending'   => 'clock',
                                ];
                            @endphp
                            <span class="px-3 py-1 {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }} rounded text-xs font-bold border inline-flex items-center gap-1.5 capitalize shadow-sm">
                                <i data-lucide="{{ $statusIcons[$status] ?? 'info' }}" class="w-3.5 h-3.5 {{ $status == 'proses' ? 'animate-spin' : '' }}"></i>
                                {{ $status }}
                            </span>
                        </p>

                        <p>
                            <span class="font-semibold">Dibuat pada:</span>
                            {{ $booking->created_at->translatedFormat('d F Y H:i') }} WIB
                        </p>
                    </div>
                </div>

                {{-- Tombol Kembali --}}
                <div class="mt-6 flex flex-wrap gap-3 animate-fade-up">
                    <a href="{{ route('admin.bookings.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-transform duration-300 hover:scale-105 font-medium shadow flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali ke Daftar
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function toggleDropdown(type) {
            const list = document.getElementById(type + '-list');
            const icon = document.getElementById(type + '-icon');
            list.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function selectOption(type, id, label) {
            document.getElementById(type + '_id_input').value = id;
            document.getElementById(type + '-label').innerText = label;
            
            const items = document.querySelectorAll('.' + type + '-item');
            items.forEach(item => {
                item.classList.remove('bg-blue-50', 'text-blue-700');
                item.classList.add('text-gray-600');
                if (item.getAttribute('data-id') == id) {
                    item.classList.add('bg-blue-50', 'text-blue-700');
                    item.classList.remove('text-gray-600');
                }
            });

            toggleDropdown(type);
        }

        window.onclick = function(event) {
            if (!event.target.closest('#dropdown-mechanic')) {
                const list = document.getElementById('mechanic-list');
                const icon = document.getElementById('mechanic-icon');
                if (list && !list.classList.contains('hidden')) {
                    list.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }
        }
    </script>
</x-app-layout>