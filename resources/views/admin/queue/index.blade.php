<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="list-checks" class="w-6 h-6 text-blue-600"></i>
            Manajemen Antrian Booking
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI --}}
    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }

        .fade-row { opacity:0; animation: fadeRow .6s ease-out forwards; }
        @keyframes fadeRow { to { opacity:1; } }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Section --}}
            @if(session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 shadow-sm rounded-r-lg fade-slide">
                    <div class="flex items-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 mr-2"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            {{-- Container Putih (White Card) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                {{-- Header Section (Judul & Filter) --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 fade-slide" style="animation-delay:.45s">
                    
                    {{-- Judul Kiri --}}
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Antrian Keseluruhan</h3>
                        <p class="text-gray-600 text-sm">Monitoring antrian tanggal: <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong></p>
                    </div>

                    {{-- Form Filter Tanggal (Kanan) --}}
                    <form action="{{ route('admin.queue.index') }}" method="GET" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input 
                                type="date"
                                name="date"
                                value="{{ $date }}"
                                class="pl-10 pr-3 py-2 rounded-lg border-gray-300 border-0 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 text-sm font-medium text-gray-700"
                            >
                        </div>

                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition flex items-center gap-1"
                        >
                            <i data-lucide="search" class="w-4 h-4"></i> Cari
                        </button>
                    </form>
                </div>

                {{-- Table Antrian --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Customer</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Tanggal / Waktu Booking</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Kendaraan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Mekanik</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">No. Antrian</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($queues as $index => $row)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">
                                    
                                    {{-- No Urut --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">{{ $index + 1 }}</td>

                                    {{-- Customer --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shadow-sm">
                                                {{ substr($row->customer_name ?? $row->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <span class="font-bold text-gray-800">{{ $row->customer_name ?? $row->user->name ?? 'Guest' }}</span>
                                        </div>
                                    </td>

                                    {{-- Tanggal & Waktu --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="flex items-center gap-1.5 text-gray-700 font-medium text-xs">
                                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
                                                {{ \Carbon\Carbon::parse($row->booking_date)->translatedFormat('d M Y') }}
                                            </span>
                                            <span class="flex items-center gap-1.5 text-blue-600 font-bold text-xs">
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                                {{ \Carbon\Carbon::parse($row->booking_date)->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Layanan --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <span class="font-semibold text-gray-800 block mb-1">{{ $row->service->name ?? '-' }}</span>
                                        <div class="text-xs flex items-center gap-2">
                                            @if($row->service && $row->service->discount_price)
                                                {{-- Harga Coret (Abu-abu) --}}
                                                <span class="text-gray-400 line-through">
                                                    Rp {{ number_format($row->service->price, 0, ',', '.') }}
                                                </span>
                                                {{-- Harga Diskon (Merah Rose) --}}
                                                <span class="text-rose-600 font-bold">
                                                    Rp {{ number_format($row->service->discount_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                {{-- Harga Normal jika tidak ada diskon --}}
                                                <span class="text-gray-500 font-medium">
                                                    Rp {{ number_format($row->service->price ?? 0, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    </td>
                                    {{-- Kendaraan --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($row->vehicle)
                                            <span class="font-semibold text-gray-800">{{ $row->vehicle->plate_number }}</span>
                                            <div class="text-xs text-gray-600 uppercase">
                                                {{ $row->vehicle->brand }} {{ $row->vehicle->model }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Mekanik --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($row->mechanic)
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="wrench" class="w-3 h-3 text-blue-500"></i>
                                                <span class="font-medium text-gray-700 text-xs">{{ $row->mechanic->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    {{-- Nomor Antrian --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($row->queue_number)
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white text-lg font-bold shadow-md transform hover:scale-110 transition">
                                                {{ $row->queue_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $status = strtolower($row->status);
                                            $badgeClass = match($status) {
                                                'approved', 'disetujui' => 'bg-green-100 text-green-700 border-green-200',
                                                'proses' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'selesai' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                'rejected', 'ditolak' => 'bg-red-100 text-red-700 border-red-200',
                                                default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 {{ $badgeClass }} rounded text-[10px] font-bold border inline-flex items-center gap-1 uppercase">
                                            @if($status == 'proses')
                                                <i data-lucide="loader" class="w-3 h-3 animate-spin"></i>
                                                Dikerjakan
                                            @elseif($status == 'selesai')
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                Selesai
                                            @elseif($status == 'approved' || $status == 'disetujui')
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                Disetujui
                                            @else
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                {{ $row->status }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-16 text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="calendar-x" class="w-16 h-16 text-gray-300 mb-4"></i>
                                            <p class="text-lg font-bold text-gray-400">Tidak Ada Antrian</p>
                                            <p class="text-sm text-gray-400">Klik "Cari" atau ganti tanggal untuk melihat data.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($queues) && method_exists($queues, 'links'))
                    <div class="mt-6">
                        {{ $queues->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>