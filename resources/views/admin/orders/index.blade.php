<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
            Manajemen Pesanan
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-2xl shadow-lg">
                {{-- Alert success --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Heading --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Pesanan</h3>
                        <p class="text-gray-600 text-sm">Kelola semua pesanan produk pelanggan di sini</p>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border">No</th>
                                <th class="px-4 py-3 border text-left">Customer</th>
                                <th class="px-4 py-3 border text-left">Produk</th>
                                <th class="px-4 py-3 border text-center">Jumlah</th>
                                <th class="px-4 py-3 border text-left">Total</th>
                                <th class="px-4 py-3 border text-center">Tanggal</th>
                                <th class="px-4 py-3 border text-center">Status</th>
                                <th class="px-4 py-3 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $index => $order)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition">
                                    <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border">{{ $order->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border">{{ $order->product->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border text-center">{{ $order->quantity }}</td>
                                    <td class="px-4 py-3 border font-semibold">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 border text-center whitespace-nowrap">
                                        {{ $order->created_at->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-3 border text-center">
                                        @if($order->status == 'disetujui')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i> Disetujui
                                            </span>
                                        @elseif($order->status == 'ditolak')
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
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                               class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-xs font-medium shadow flex items-center gap-1">
                                                <i data-lucide="search" class="w-4 h-4"></i> Detail
                                            </a>

                                            {{-- Status Update --}}
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="relative">
                                                @csrf
                                                @method('PATCH')
                                                <select 
                                                    name="status" 
                                                    onchange="this.form.submit()" 
                                                    class="border border-gray-300 rounded-lg px-2 py-1 pr-8 text-xs appearance-none bg-white focus:ring focus:ring-blue-200 focus:border-blue-400">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                                    <option value="disetujui" {{ $order->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                    <option value="ditolak" {{ $order->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs">▼</span>
                                            </form>

                                            {{-- Hapus --}}
                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-xs font-medium shadow flex items-center gap-1">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-6 text-gray-500 italic">
                                        Belum ada pesanan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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