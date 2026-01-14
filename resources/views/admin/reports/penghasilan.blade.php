<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wallet-cards" class="w-6 h-6 text-blue-600"></i>
            Laporan Penghasilan
        </h2>
    </x-slot>

    <style>
        .fade-slide {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp .6s ease-out forwards;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-row {
            opacity: 0;
            animation: fadeRow .5s ease-out forwards;
        }

        @keyframes fadeRow {
            to {
                opacity: 1;
            }
        }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- ================= SUMMARY CARD ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="p-6 bg-blue-600 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <i data-lucide="calendar-check" class="w-8 h-8 opacity-70"></i>
                        <span class="text-xs uppercase font-bold">Booking</span>
                    </div>
                    <p class="mt-4 text-sm opacity-80">Penghasilan Jasa</p>
                    <p class="text-3xl font-extrabold">
                        Rp {{ number_format($totalBooking, 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-6 bg-green-600 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <i data-lucide="shopping-bag" class="w-8 h-8 opacity-70"></i>
                        <span class="text-xs uppercase font-bold">Produk</span>
                    </div>
                    <p class="mt-4 text-sm opacity-80">Penjualan Produk</p>
                    <p class="text-3xl font-extrabold">
                        Rp {{ number_format($totalOrder, 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-6 bg-indigo-600 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <i data-lucide="trending-up" class="w-8 h-8 opacity-70"></i>
                        <span class="text-xs uppercase font-bold">Total</span>
                    </div>
                    <p class="mt-4 text-sm opacity-80">Total Keseluruhan</p>
                    <p class="text-3xl font-extrabold">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </p>
                </div>

            </div>

            {{-- ================= HISTORY BOOKING ================= --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide">

                {{-- HEADER CARD (CENTER) --}}
                <div class="mb-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800 flex items-left justify-left gap-2">
                        <i data-lucide="history" class="w-5 h-5 text-blue-600"></i>
                        History Booking
                    </h3>
                    <p class="text-left text-gray-500">
                        Riwayat penyelesaian jasa servis
                    </p>
                </div>

                {{-- TABLE (TIDAK CENTER) --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center">ID</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Customer</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Kendaraan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-right">Subtotal</th>
                                <th class="px-4 py-3 text-center">Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bookings as $index => $b)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.08 }}s">

                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-mono text-xs font-bold text-blue-600">
                                        #{{ $b->id }}
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <p class="font-bold text-gray-800">
                                            {{ $b->customer_name ?? ($b->user->name ?? 'User') }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $b->customer_phone ?? '-' }}</p>
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($b->vehicle)
                                            {{ $b->vehicle->brand }} ({{ $b->vehicle->plate_number }})
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <p class="font-semibold text-gray-800">
                                            {{ $b->service->name ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Mekanik: {{ $b->mechanic->name ?? 'Belum Ada' }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-right">
                                        @if($b->service && $b->service->discount_price)
                                            <div class="text-xs text-gray-400 line-through">
                                                Rp {{ number_format($b->service->price, 0, ',', '.') }}
                                            </div>
                                            <div class="font-bold text-rose-600">
                                                Rp {{ number_format($b->service->discount_price, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div class="font-bold text-blue-700">
                                                Rp {{ number_format($b->service->price ?? 0, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center text-xs text-gray-500">
                                        {{ $b->created_at->format('d-m-Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500 italic bg-gray-50">
                                        Belum ada history booking
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ================= HISTORY PRODUK ================= --}}
            <div class="bg-white p-10 rounded-2xl shadow-lg fade-slide text-center">
                <i data-lucide="shopping-cart" class="w-10 h-10 mx-auto text-gray-400 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700">History Produk</h3>
                <p class="text-sm text-gray-500">
                    Riwayat penjualan produk akan tampil di sini
                </p>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
