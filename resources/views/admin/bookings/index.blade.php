<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="calendar-check" class="w-6 h-6 text-blue-600"></i>
            Manajemen Booking
        </h2>
    </x-slot>

    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }

        .fade-row { opacity:0; animation: fadeRow .6s ease-out forwards; }
        @keyframes fadeRow { to { opacity:1; } }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Booking</h3>
                        <p class="text-gray-600 text-sm">Kelola semua data booking pelanggan di sini</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border">No</th>
                                <th class="px-4 py-3 border">Antrian</th>
                                <th class="px-4 py-3 border text-left">Customer</th>
                                <th class="px-4 py-3 border text-left">Kendaraan</th>
                                <th class="px-4 py-3 border text-left">Layanan</th>
                                <th class="px-4 py-3 border text-left">Mekanik</th>
                                <th class="px-4 py-3 border">Tanggal</th>
                                <th class="px-4 py-3 border">Status</th>
                                <th class="px-4 py-3 border">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bookings as $index => $booking)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">

                                    <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>

                                    {{-- QUEUE NUMBER --}}
                                    <td class="px-4 py-3 border text-center font-semibold">
                                        {{ $booking->queue_number ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 border">{{ $booking->user->name ?? 'User' }}</td>

                                    <td class="px-4 py-3 border">
                                        @if($booking->vehicle)
                                            <span class="font-semibold">{{ $booking->vehicle->plate_number }}</span><br>
                                            <span class="text-xs text-gray-600">
                                                {{ $booking->vehicle->brand }} - {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada kendaraan</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border">
                                        @if($booking->service)
                                            <span class="font-semibold text-gray-800">{{ $booking->service->name }}</span>
                                            <span class="text-xs text-gray-500 block">
                                                Rp {{ number_format($booking->service->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada layanan</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border">
                                        @if($booking->mechanic)
                                            <span class="font-semibold text-gray-800">{{ $booking->mechanic->name }}</span>
                                        @else
                                            <span class="text-gray-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border text-center whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}
                                    </td>

                                    <td class="px-4 py-3 border text-center">
                                        @if($booking->status == 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i> Disetujui
                                            </span>
                                        @elseif($booking->status == 'rejected')
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="clock" class="w-4 h-4"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border">
                                        <div class="flex flex-col md:flex-row md:items-center gap-2">

                                            {{-- Detail --}}
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                               class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-xs font-medium shadow flex items-center gap-1">
                                                <i data-lucide="search" class="w-4 h-4"></i> Detail
                                            </a>

                                            {{-- Status Update --}}
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <select name="status" onchange="this.form.submit()"
                                                    class="border border-gray-300 rounded-lg px-2 py-1 pr-8 text-xs bg-white focus:ring focus:ring-blue-200 focus:border-blue-400">
                                                    <option value="pending" {{ $booking->status=='pending'?'selected':'' }}>Menunggu</option>
                                                    <option value="approved" {{ $booking->status=='approved'?'selected':'' }}>Disetujui</option>
                                                    <option value="rejected" {{ $booking->status=='rejected'?'selected':'' }}>Ditolak</option>
                                                </select>
                                            </form>

                                            {{-- Hapus --}}
                                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-xs shadow flex items-center gap-1">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="fade-row" style="animation-delay:.3s">
                                    <td colspan="9" class="text-center py-6 text-gray-500 italic">
                                        Belum ada booking
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>