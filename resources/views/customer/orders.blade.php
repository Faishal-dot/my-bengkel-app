<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            {{-- Judul --}}
            <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                Histori Pesanan
            </h2>

            {{-- Tombol pesanan baru --}}
            <a href="{{ route('customer.products') }}"
               class="flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow text-sm font-medium transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Pesan Produk
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2 animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table Pesanan --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-lg transition-all duration-300 animate-fadeIn">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-blue-600 text-white uppercase text-xs">
                            <th class="px-4 py-3 border text-center">No</th>
                            <th class="px-4 py-3 border text-left">Produk</th>
                            <th class="px-4 py-3 border text-center">Jumlah</th>
                            <th class="px-4 py-3 border text-right">Total Harga</th>
                            <th class="px-4 py-3 border text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $index => $order)
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'menunggu' => 'bg-yellow-100 text-yellow-700',
                                    'disetujui' => 'bg-green-100 text-green-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                ];
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'menunggu' => 'clock',
                                    'disetujui' => 'check-circle',
                                    'ditolak' => 'x-circle',
                                ];
                            @endphp

                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition duration-200">
                                <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>

                                <td class="px-4 py-3 border">
                                    {{ $order->product->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 border text-center">
                                    {{ $order->quantity }}
                                </td>

                                <td class="px-4 py-3 border text-right">
                                    Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 border text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1 {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        <i data-lucide="{{ $statusIcons[$order->status] ?? 'info' }}" class="w-4 h-4"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500 italic flex items-center justify-center gap-2">
                                    <i data-lucide="calendar-x" class="w-5 h-5 text-red-500"></i>
                                    Belum ada pesanan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($orders, 'links'))
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Animasi sama seperti Booking --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>

</x-app-layout>