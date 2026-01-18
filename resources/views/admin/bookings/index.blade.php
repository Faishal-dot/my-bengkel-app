<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="calendar-check" class="w-6 h-6 text-blue-600"></i>
            Manajemen Booking
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

            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Alert Error --}}
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg fade-slide" style="animation-delay:.35s">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Booking</h3>
                        <p class="text-gray-600 text-sm">Kelola semua data booking pelanggan di sini (Urutan Berdasarkan Jam Terawal)</p>
                    </div>
                </div>

                {{-- Table Booking --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Antrian</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Customer</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Mekanik</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Tanggal & Jam</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- Mengurutkan data berdasarkan tanggal dan waktu dari yang paling awal --}}
                            @forelse ($bookings->sortBy('booking_date') as $index => $booking)
                                <tr class="{{ $loop->index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $loop->index * 0.12 }}s">

                                    {{-- 1. NO --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">{{ $loop->iteration }}</td>

                                    {{-- 2. ANTRIAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-semibold">
                                        @if($booking->queue_number)
                                            <span class="inline-block w-8 h-8 rounded-full bg-blue-600 text-white leading-8 shadow-sm">
                                                {{ $booking->queue_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-lg">—</span>
                                        @endif
                                    </td>

                                    {{-- 3. CUSTOMER --}}
                                    <td class="px-4 py-4 border-r border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shadow-sm">
                                                {{ substr($booking->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $booking->user->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $booking->vehicle ? $booking->vehicle->plate_number : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 4. LAYANAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($booking->service)
                                            <span class="font-semibold text-gray-800">{{ $booking->service->name }}</span>
                                            <div class="text-xs">
                                                <span class="text-gray-500">Rp {{ number_format($booking->service->discount_price ?? $booking->service->price, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 5. MEKANIK --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($booking->mechanic)
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="wrench" class="w-3 h-3 text-gray-400"></i>
                                                <span class="font-medium text-gray-700 text-xs">{{ $booking->mechanic->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    {{-- 6. TANGGAL & JAM (DESAIN SERAGAM) --}}
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

                                    {{-- 7. STATUS --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @php
                                            $statusClasses = [
                                                'selesai' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                'disetujui' => 'bg-green-100 text-green-700 border-green-200',
                                                'ditolak' => 'bg-red-100 text-red-700 border-red-200',
                                                'proses' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'menunggu' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            ];
                                            $status = $booking->status == 'rejected' ? 'ditolak' : $booking->status;
                                            $class = $statusClasses[$status] ?? $statusClasses['menunggu'];
                                        @endphp
                                        <span class="px-2 py-1 {{ $class }} rounded text-xs font-bold border inline-flex items-center gap-1 capitalize">
                                            @if($status == 'proses')
                                                <i data-lucide="loader" class="w-3 h-3 animate-spin"></i>
                                            @endif
                                            {{ $status }}
                                        </span>
                                    </td>

                                    {{-- 8. AKSI --}}
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center gap-2 justify-center">
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                               class="p-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow transition-all" title="Detail">
                                                <i data-lucide="search" class="w-4 h-4"></i>
                                            </a>

                                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="p-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow transition-all" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-gray-500 italic">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mb-2"></i>
                                            <p>Belum ada data booking yang masuk.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($bookings, 'links'))
                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>