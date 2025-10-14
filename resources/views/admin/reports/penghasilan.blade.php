<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-blue-700 flex items-center gap-2">
            <i data-lucide="wallet-cards" class="w-7 h-7 text-blue-600"></i>
            Laporan Penghasilan
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card Utama -->
            <div class="bg-white shadow-lg rounded-2xl p-8 space-y-10">

                <!-- Ringkasan -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 text-indigo-600"></i>
                        Ringkasan Penghasilan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Booking -->
                        <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl shadow-md flex flex-col gap-1">
                            <i data-lucide="calendar-check" class="w-6 h-6 opacity-80"></i>
                            <h4 class="text-sm opacity-80">Dari Booking</h4>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalBooking, 0, ',', '.') }}</p>
                        </div>

                        <!-- Produk -->
                        <div class="p-6 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl shadow-md flex flex-col gap-1">
                            <i data-lucide="shopping-bag" class="w-6 h-6 opacity-80"></i>
                            <h4 class="text-sm opacity-80">Dari Penjualan Produk</h4>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalOrder, 0, ',', '.') }}</p>
                        </div>

                        <!-- Total -->
                        <div class="p-6 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 rounded-2xl shadow-md flex flex-col gap-1">
                            <i data-lucide="wallet" class="w-6 h-6 opacity-80"></i>
                            <h4 class="text-sm opacity-80">Total Keseluruhan</h4>
                            <p class="text-2xl font-bold">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tabel Booking -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 text-blue-500"></i>
                        Booking
                    </h3>
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="w-full text-sm border border-gray-200">
                            <thead class="bg-blue-100 text-gray-800 font-medium">
                                <tr>
                                    <th class="p-3 border">ID</th>
                                    <th class="p-3 border">User</th>
                                    <th class="p-3 border">Kendaraan</th>
                                    <th class="p-3 border">Layanan</th>
                                    <th class="p-3 border">Harga</th>
                                    <th class="p-3 border">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $b)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="p-3 border text-center">{{ $b->id }}</td>
                                        <td class="p-3 border">{{ $b->user->name ?? '-' }}</td>
                                        <td class="p-3 border">{{ $b->vehicle_name ?? '-' }}</td>
                                        <td class="p-3 border">{{ $b->service->name ?? '-' }}</td>
                                        <td class="p-3 border">Rp {{ number_format($b->service->price ?? 0, 0, ',', '.') }}</td>
                                        <td class="p-3 border">{{ $b->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-3 border text-center text-gray-500 italic">Tidak ada booking approved</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Orders -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-green-600"></i>
                        Pesanan Produk
                    </h3>
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="w-full text-sm border border-gray-200">
                            <thead class="bg-green-100 text-gray-800 font-medium">
                                <tr>
                                    <th class="p-3 border">ID</th>
                                    <th class="p-3 border">User</th>
                                    <th class="p-3 border">Produk</th>
                                    <th class="p-3 border">Jumlah</th>
                                    <th class="p-3 border">Total Harga</th>
                                    <th class="p-3 border">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $o)
                                    <tr class="hover:bg-green-50 transition">
                                        <td class="p-3 border text-center">{{ $o->id }}</td>
                                        <td class="p-3 border">{{ $o->user->name ?? '-' }}</td>
                                        <td class="p-3 border">{{ $o->product->name ?? '-' }}</td>
                                        <td class="p-3 border text-center">{{ $o->quantity }}</td>
                                        <td class="p-3 border">Rp {{ number_format($o->total_price, 0, ',', '.') }}</td>
                                        <td class="p-3 border">{{ $o->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-3 border text-center text-gray-500 italic">Tidak ada order disetujui</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>