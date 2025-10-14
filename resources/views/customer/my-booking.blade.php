<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            {{-- Judul --}}
            <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-6 h-6 text-blue-600"></i>
                Histori Booking
            </h2>

            {{-- Tombol booking --}}
            <a href="{{ route('customer.booking.create') }}"
               class="flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow text-sm font-medium transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Booking Baru
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table Booking --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-blue-600 text-white uppercase text-xs">
                            <th class="px-4 py-3 border text-center">No</th>
                            <th class="px-4 py-3 border text-left">Layanan</th>
                            <th class="px-4 py-3 border text-left">Kendaraan</th>
                            <th class="px-4 py-3 border text-left">Mekanik</th>
                            <th class="px-4 py-3 border text-center">Tanggal</th>
                            <th class="px-4 py-3 border text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $index => $booking)
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                ];
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'approved' => 'check-circle',
                                    'rejected' => 'x-circle',
                                    'completed' => 'award',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'completed' => 'Selesai',
                                ];
                            @endphp
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition">
                                {{-- Nomor --}}
                                <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>

                                {{-- Layanan --}}
                                <td class="px-4 py-3 border">
                                    <span class="font-semibold">{{ $booking->service->name ?? '-' }}</span>
                                    <span class="text-xs text-gray-500 block">
                                        Rp {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Kendaraan --}}
                                <td class="px-4 py-3 border">
                                    @if($booking->vehicle)
                                        <span class="font-semibold">{{ $booking->vehicle->plate_number }}</span><br>
                                        <span class="text-xs text-gray-600">
                                            {{ $booking->vehicle->brand }} - {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ada kendaraan</span>
                                    @endif
                                </td>

                                {{-- Mekanik --}}
                                <td class="px-4 py-3 border">
                                    @if($booking->mechanic)
                                        <p class="font-semibold text-gray-800">{{ $booking->mechanic->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $booking->mechanic->specialization ?? '-' }}
                                        </p>
                                    @else
                                        <span class="text-gray-400 italic">Belum ditugaskan</span>
                                    @endif
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-3 border text-center">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3 border text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1 {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        <i data-lucide="{{ $statusIcons[$booking->status] ?? 'info' }}" class="w-4 h-4"></i>
                                        {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500 italic">
                                    Belum ada booking 😔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>

        </div>
    </div>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>