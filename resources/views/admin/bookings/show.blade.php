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
                        <p><span class="font-semibold">Nama:</span> {{ $booking->user->name ?? '-' }}</p>
                        <p><span class="font-semibold">Email:</span> {{ $booking->user->email ?? '-' }}</p>
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
                            @else
                                <span class="text-gray-400 italic">Tidak ada kendaraan</span>
                            @endif
                        </p>

                        <p>
                            <span class="font-semibold">Layanan:</span>
                            @if($booking->service)
                                {{ $booking->service->name }}
                                <span class="text-sm text-gray-500">(Rp {{ number_format($booking->service->price, 0, ',', '.') }})</span>
                            @else
                                <span class="text-gray-400 italic">Tidak ada layanan</span>
                            @endif
                        </p>

                        <p>
                            <span class="font-semibold">Tanggal:</span>
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                        </p>

                    </div>
                </div>

                {{-- Penugasan Mekanik oleh Admin --}}
                <div class="animate-fade-up mt-8">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                        Penugasan Mekanik
                    </h3>

                    <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md transition-all duration-300">

                        @if($booking->mechanic)
                            <p class="text-gray-800 font-medium flex items-center gap-2 mb-3">
                                <i data-lucide="user-check" class="w-5 h-5 text-green-600"></i>
                                Mekanik saat ini:
                                <span class="text-blue-700 font-semibold">{{ $booking->mechanic->name }}</span>
                            </p>
                        @endif

                        <form action="{{ route('admin.bookings.assign', $booking->id) }}" method="POST" class="space-y-3">
                            @csrf

                            <label class="font-semibold text-gray-700">Pilih Mekanik</label>

                            <select name="mechanic_id"
                                class="w-full border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Mekanik --</option>
                                @foreach ($mechanics as $mechanic)
                                    <option value="{{ $mechanic->id }}"
                                        {{ $booking->mechanic_id == $mechanic->id ? 'selected' : '' }}>
                                        {{ $mechanic->name }} — {{ $mechanic->specialization ?? 'Umum' }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-transform duration-300 hover:scale-105 font-medium shadow">
                                <i data-lucide="save" class="inline w-4 h-4 mr-1"></i>
                                Simpan Mekanik
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
                        <p>
                            <span class="font-semibold">Status:</span>
                            @if($booking->status == 'pending')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold shadow-sm">
                                    <i data-lucide="hourglass" class="w-4 h-4"></i> Menunggu
                                </span>
                            @elseif($booking->status == 'approved')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold shadow-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Disetujui
                                </span>
                            @elseif($booking->status == 'proses')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold shadow-sm">
                                    <i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Dikerjakan
                                </span>
                            @elseif($booking->status == 'selesai')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold shadow-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Selesai
                                </span>
                            @elseif($booking->status == 'rejected')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold shadow-sm">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Ditolak
                                </span>
                            @endif
                        </p>

                        <p>
                            <span class="font-semibold">Dibuat pada:</span>
                            {{ $booking->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                {{-- Tombol Kembali --}}
                <div class="mt-6 flex flex-wrap gap-3 animate-fade-up">
                    <a href="{{ route('admin.bookings.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-transform duration-300 hover:scale-105 font-medium shadow">
                        <i data-lucide="arrow-left" class="inline w-4 h-4 mr-1"></i>
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-app-layout>