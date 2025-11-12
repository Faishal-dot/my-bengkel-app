<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            {{-- Judul --}}
            <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                Histori Pesanan
            </h2>

            {{-- Tombol pesan produk --}}
            <a href="{{ route('customer.products') }}"
               class="flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 
                      text-white rounded-xl shadow text-sm font-medium transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Pesan Produk
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 
                            rounded-xl flex items-center gap-2 shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table Pesanan dengan animasi --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-lg animate-fade-up">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-blue-600 text-white uppercase text-xs tracking-wider">
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
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} 
                                       hover:bg-blue-50 transition duration-300 
                                       animate-row-fade-up opacity-0"
                                style="animation-delay: {{ $index * 100 }}ms; animation-fill-mode: forwards;">
                                <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 border">{{ $order->product->name ?? '-' }}</td>
                                <td class="px-4 py-3 border text-center">{{ $order->quantity }}</td>
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
                                <td colspan="5" class="text-center py-10 text-gray-500 italic animate-fade-in">
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

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    {{-- 🌟 Animasi hanya untuk tabel --}}
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.8s ease-out forwards;
        }

        @keyframes rowFadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-row-fade-up {
            animation: rowFadeUp 0.7s ease-out forwards;
        }
    </style>
</x-app-layout>