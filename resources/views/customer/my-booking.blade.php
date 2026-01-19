<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="calendar-check" class="w-6 h-6 text-blue-600"></i>
            Histori Booking
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

            {{-- Container Putih (White Card) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Header Section dalam Card --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Booking Saya</h3>
                        <p class="text-gray-600 text-sm">Pantau status servis kendaraan Anda di sini</p>
                    </div>

                    {{-- Tombol Booking Baru --}}
                    <a href="{{ route('customer.booking.create') }}"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105">
                        <i data-lucide="plus" class="w-4 h-4"></i> Booking Baru
                    </a>
                </div>

                {{-- Table Booking --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Antrian</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Kendaraan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Mekanik</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Tanggal & Jam</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bookings as $index => $booking)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">

                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">{{ $index + 1 }}</td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-semibold">
                                        @if($booking->queue_number)
                                            <span class="inline-block w-8 h-8 rounded-full bg-blue-600 text-white leading-8 shadow-sm">
                                                {{ $booking->queue_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-lg">—</span>
                                        @endif
                                    </td>

                                    {{-- Layanan dengan Tampilan Diskon --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <span class="font-semibold text-gray-800">{{ $booking->service->name ?? '-' }}</span>
                                        <div class="text-xs">
                                            @if($booking->service && $booking->service->discount_price)
                                                <span class="text-gray-400 line-through">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</span>
                                                <span class="text-rose-600 font-bold ml-1">Rp {{ number_format($booking->service->discount_price, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-gray-500">Rp {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Detail Kendaraan (Ditambahkan Warna) --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($booking->vehicle)
                                            <span class="font-semibold text-gray-800">{{ $booking->vehicle->plate_number }}</span>
                                            <div class="text-xs text-gray-600">
                                                {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                                            </div>
                                            {{-- Warna Muncul Di Sini --}}
                                            <div class="text-[10px] mt-1 inline-flex items-center px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded border border-gray-200 capitalize font-medium">
                                                Warna: {{ $booking->vehicle->color ?? '-' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada kendaraan</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($booking->mechanic)
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="wrench" class="w-3 h-3 text-gray-400"></i>
                                                <span class="font-medium text-gray-700">{{ $booking->mechanic->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-center whitespace-nowrap">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-semibold border border-gray-200">
                                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                                            </span>
                                            <span class="px-2 py-0.5 bg-blue-600 text-white rounded text-xs font-bold flex items-center gap-1 shadow-sm">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($booking->status == 'selesai')
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-bold border border-indigo-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Selesai
                                            </span>
                                        @elseif($booking->status == 'disetujui')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Disetujui
                                            </span>
                                        @elseif($booking->status == 'rejected' || $booking->status == 'ditolak')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold border border-red-200 inline-flex items-center gap-1">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak
                                            </span>
                                        @elseif($booking->status == 'proses')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold border border-blue-200 inline-flex items-center gap-1">
                                                <i data-lucide="loader" class="w-3 h-3 animate-spin"></i> Dikerjakan
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold border border-yellow-200 inline-flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <a href="{{ route('chat.show', $booking->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-medium shadow transition">
                                            <i data-lucide="message-circle" class="w-3 h-3"></i>
                                            Chat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="fade-row" style="animation-delay:.3s">
                                    <td colspan="8" class="text-center py-8 text-gray-500 italic bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="calendar-x" class="w-10 h-10 text-gray-300 mb-2"></i>
                                            <p>Belum ada riwayat booking.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>