<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i>
            Detail Booking #{{ $booking->id }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur shadow-xl rounded-2xl p-8 space-y-10 border border-gray-100">

                {{-- ✅ Alert sukses --}}
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-200">
                        <i data-lucide="check-circle" class="inline w-5 h-5 mr-1"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ✅ Informasi Customer --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Informasi Customer
                    </h3>
                    <div class="text-gray-700 space-y-1">
                        <p><span class="font-semibold">Nama:</span> {{ $booking->user->name ?? '-' }}</p>
                        <p><span class="font-semibold">Email:</span> {{ $booking->user->email ?? '-' }}</p>
                    </div>
                </div>

                {{-- ✅ Detail Booking --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="car" class="w-5 h-5"></i>
                        Detail Booking
                    </h3>
                    <div class="space-y-3 text-gray-700">
                        <p>
                            <span class="font-semibold">Kendaraan:</span>
                            @if($booking->vehicle)
                                {{ $booking->vehicle->plate_number }}
                                <span class="text-sm text-gray-500">
                                    ({{ $booking->vehicle->brand ?? '-' }}
                                    {{ $booking->vehicle->model ? '- ' . $booking->vehicle->model : '' }}
                                    {{ $booking->vehicle->year ? '(' . $booking->vehicle->year . ')' : '' }})
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

                        <p><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                    </div>
                </div>

                {{-- ✅ Penugasan Mekanik --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="wrench" class="w-5 h-5"></i>
                        Penugasan Mekanik
                    </h3>

                    @if($booking->mechanic)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="font-medium text-gray-800">
                                <i data-lucide="user-cog" class="inline w-5 h-5 text-blue-600 mr-1"></i>
                                Mekanik yang ditugaskan:
                                <span class="text-blue-700 font-semibold">{{ $booking->mechanic->name }}</span>
                                <span class="text-gray-500 text-sm">
                                    ({{ $booking->mechanic->specialization ?? 'Umum' }})
                                </span>
                            </p>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Belum ada mekanik yang ditugaskan.</p>
                    @endif
                </div>

                {{-- ✅ Ringkasan & Status --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700 border-b pb-2">
                        <i data-lucide="bookmark-check" class="w-5 h-5"></i>
                        Ringkasan Booking
                    </h3>
                    <div class="space-y-3 text-gray-700">
                        <p>
                            <span class="font-semibold">Status:</span>
                            @if($booking->status == 'approved')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Disetujui
                                </span>
                            @elseif($booking->status == 'rejected')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    <i data-lucide="hourglass" class="w-4 h-4"></i> Menunggu
                                </span>
                            @endif
                        </p>

                        <p><span class="font-semibold">Dibuat pada:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                {{-- ✅ Tombol Aksi --}}
                <div class="mt-6 flex flex-wrap gap-3">
                    @if($booking->status == 'pending')
                        {{-- Tombol Setujui --}}
                        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit"
                                class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                <i data-lucide="check" class="inline w-4 h-4 mr-1"></i>
                                Setujui
                            </button>
                        </form>

                        {{-- Tombol Tolak --}}
                        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                <i data-lucide="x" class="inline w-4 h-4 mr-1"></i>
                                Tolak
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('admin.bookings.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i data-lucide="arrow-left" class="inline w-4 h-4 mr-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-app-layout>