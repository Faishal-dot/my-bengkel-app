<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            {{-- Judul --}}
            <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="list-checks" class="w-6 h-6 text-blue-600"></i>
                Antrian Booking
            </h2>

            {{-- Filter tanggal --}}
            <form action="{{ route('customer.queue.index') }}" method="GET" class="flex items-center gap-2">
                <input type="date" name="date"
                       value="{{ request('date', now()->toDateString()) }}"
                       class="px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring focus:ring-blue-200 text-sm">

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-xl shadow transition">
                    Cari
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Table Booking - selalu muncul --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-lg transition-all duration-300 animate-fadeIn">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-blue-600 text-white uppercase text-xs">
                            <th class="px-4 py-3 border text-center">No</th>
                            <th class="px-4 py-3 border">Customer</th>
                            <th class="px-4 py-3 border">Layanan</th>
                            <th class="px-4 py-3 border">Kendaraan</th>
                            <th class="px-4 py-3 border">Mekanik</th>
                            <th class="px-4 py-3 border text-center">Antrian</th>
                            <th class="px-4 py-3 border text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($queues as $index => $row)
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'working' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-gray-200 text-gray-700',
                                ];

                                $statusIcons = [
                                    'pending' => 'clock',
                                    'approved' => 'check-circle',
                                    'working' => 'loader',
                                    'completed' => 'check',
                                ];

                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'working' => 'Dikerjakan',
                                    'completed' => 'Selesai',
                                ];
                            @endphp

                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition">
                                <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>

                                <td class="px-4 py-3 border">
                                    <span class="font-semibold">{{ $row->user->name }}</span>
                                </td>

                                <td class="px-4 py-3 border">
                                    <span class="font-semibold">{{ $row->service->name ?? '-' }}</span>
                                    <span class="text-xs text-gray-500 block">
                                        Rp {{ number_format($row->service->price ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 border">
                                    @if($row->vehicle)
                                        <span class="font-semibold">{{ $row->vehicle->plate_number }}</span><br>
                                        <span class="text-xs text-gray-600">
                                            {{ $row->vehicle->brand }} - {{ $row->vehicle->model }} ({{ $row->vehicle->year }})
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada kendaraan</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 border">
                                    @if($row->mechanic)
                                        <span class="font-semibold">{{ $row->mechanic->name }}</span><br>
                                        <span class="text-xs text-gray-500">{{ $row->mechanic->specialization ?? '-' }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ditugaskan</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 border text-center font-bold text-blue-700">
                                    {{ $row->queue_number ?? '-' }}
                                </td>

                                <td class="px-4 py-3 border text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1
                                        {{ $statusClasses[$row->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        <i data-lucide="{{ $statusIcons[$row->status] ?? 'info' }}" class="w-4 h-4"></i>
                                        {{ $statusLabels[$row->status] ?? ucfirst($row->status) }}
                                    </span>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 italic">
                                    <i data-lucide="calendar-x" class="w-5 h-5 inline text-red-500"></i>
                                    Tidak ada antrian ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Animasi FadeIn --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

</x-app-layout>