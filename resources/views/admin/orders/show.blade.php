<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">
            Detail Pesanan #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur shadow-xl rounded-2xl p-8 space-y-10 border border-gray-100">

                <!-- Informasi Customer -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-blue-700 border-b pb-2">
                        Informasi Customer
                    </h3>
                    <div class="text-gray-700 space-y-1">
                        <p><span class="font-semibold">Nama:</span> {{ $order->user->name ?? '-' }}</p>
                        <p><span class="font-semibold">Email:</span> {{ $order->user->email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Informasi Produk -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-blue-700 border-b pb-2">
                        Detail Produk
                    </h3>
                    <div class="text-gray-700 space-y-2">
                        <p><span class="font-semibold">Nama Produk:</span> {{ $order->product->name ?? '-' }}</p>
                        <p><span class="font-semibold">Deskripsi:</span> {{ $order->product->description ?? 'Tidak ada deskripsi' }}</p>
                        <p><span class="font-semibold">Harga Satuan:</span> Rp {{ number_format($order->product->price ?? 0, 0, ',', '.') }}</p>
                        <p><span class="font-semibold">Jumlah:</span> {{ $order->quantity }}</p>
                    </div>
                </div>

                <!-- Ringkasan -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-blue-700 border-b pb-2">
                        Ringkasan Pesanan
                    </h3>
                    <div class="space-y-3 text-gray-700">
                        <p>
                            <span class="font-semibold">Total Harga:</span> 
                            Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                        </p>
                        <p>
                            <span class="font-semibold">Tanggal Pesanan:</span> {{ $order->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            <span class="font-semibold">Status:</span>
                            @if($order->status == 'disetujui')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    Disetujui
                                </span>
                            @elseif($order->status == 'ditolak')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    Menunggu
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Tombol Kembali -->
                <div class="pt-6 border-t flex justify-end">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-lg hover:scale-105 transform transition text-sm font-medium">
                        Kembali ke Daftar Pesanan
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>