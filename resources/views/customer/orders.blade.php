<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
            Histori Pesanan
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI (Konsisten) --}}
    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }

        .fade-row { opacity:0; animation: fadeRow .6s ease-out forwards; }
        @keyframes fadeRow { to { opacity:1; } }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Container Putih (White Card) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Header Section dalam Card --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Pesanan Saya</h3>
                        <p class="text-gray-600 text-sm">Riwayat pembelian sparepart dan produk lainnya</p>
                    </div>

                    {{-- Tombol Pesan Produk --}}
                    <a href="{{ route('customer.products') }}"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105">
                        <i data-lucide="plus" class="w-4 h-4"></i> Pesan Produk
                    </a>
                </div>

                {{-- Table Pesanan --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center w-12">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Produk</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Jumlah</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-right">Total Harga</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $index => $order)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">

                                    {{-- No --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">{{ $index + 1 }}</td>

                                    {{-- Produk --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-500">
                                                <i data-lucide="package" class="w-4 h-4"></i>
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $order->product->name ?? 'Produk Dihapus' }}</span>
                                        </div>
                                    </td>

                                    {{-- Jumlah --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-700 border border-gray-200">
                                            x{{ $order->quantity }}
                                        </span>
                                    </td>

                                    {{-- Total Harga --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-right font-bold text-gray-700">
                                        Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                                    </td>

                                    {{-- Status (Badge Style Konsisten) --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @php
                                            $status = strtolower($order->status);
                                        @endphp

                                        @if($status == 'disetujui' || $status == 'selesai' || $status == 'success')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Disetujui
                                            </span>
                                        @elseif($status == 'pending' || $status == 'menunggu')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold border border-yellow-200 inline-flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i> Menunggu
                                            </span>
                                        @elseif($status == 'ditolak' || $status == 'batal')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold border border-red-200 inline-flex items-center gap-1">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-bold border border-gray-200 inline-flex items-center gap-1">
                                                {{ ucfirst($status) }}
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr class="fade-row" style="animation-delay:.3s">
                                    <td colspan="5" class="text-center py-8 text-gray-500 italic bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="shopping-cart" class="w-10 h-10 text-gray-300 mb-2"></i>
                                            <p>Belum ada riwayat pesanan.</p>
                                        </div>
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
    </div>

    {{-- Script Lucide --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>

</x-app-layout>