<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
            Riwayat Pembayaran
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI --}}
    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }

        .fade-row { opacity:0; animation: fadeRow .6s ease-out forwards; }
        @keyframes fadeRow { to { opacity:1; } }
        
        @media print {
            .no-print { display: none !important; }
        }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Container Putih (White Card) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Header Section dalam Card --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Tagihan & Transaksi</h3>
                        <p class="text-gray-600 text-sm">Kelola pembayaran servis kendaraan dan pembelian produk Anda</p>
                    </div>
                </div>

                {{-- Table Container --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center w-12">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Tipe</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Item / Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Info Tambahan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Total</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($payments as $index => $payment)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.1 }}s">

                                    {{-- NO --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">{{ $index + 1 }}</td>

                                    {{-- TIPE --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($payment->booking_id)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Servis</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Sparepart</span>
                                        @endif
                                    </td>

                                    {{-- ITEM / LAYANAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($payment->booking_id)
                                            <span class="font-semibold text-gray-800">{{ $payment->booking->service->name ?? 'Layanan Servis' }}</span>
                                            <p class="text-[10px] text-gray-500">ID: BK-{{ $payment->booking_id }}</p>
                                        @else
                                            <span class="font-semibold text-gray-800">{{ $payment->order->product->name ?? 'Produk Sparepart' }}</span>
                                            <p class="text-[10px] text-gray-500">ID: ORD-{{ $payment->order_id }}</p>
                                        @endif
                                    </td>

                                    {{-- INFO TAMBAHAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($payment->booking_id)
                                            <div class="flex items-center gap-1 font-medium text-gray-700">
                                                <i data-lucide="car" class="w-3 h-3 text-gray-400"></i>
                                                {{ $payment->booking->vehicle->plate_number ?? '-' }}
                                            </div>
                                            <p class="text-[10px] text-gray-600">{{ $payment->booking->vehicle->brand ?? '' }}</p>
                                        @else
                                            <div class="flex items-center gap-1 text-gray-600 font-medium">
                                                <i data-lucide="package" class="w-3 h-3 text-gray-400"></i>
                                                {{ $payment->order->quantity ?? 0 }} Item
                                            </div>
                                        @endif
                                    </td>

                                    {{-- TOTAL --}}
                                    <td class="px-4 py-3 border-r border-gray-200 font-bold text-gray-800">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @php $status = strtolower($payment->status); @endphp

                                        @if($status == 'unpaid')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[11px] font-bold border border-red-200 inline-flex items-center gap-1 animate-pulse">
                                                <i data-lucide="alert-circle" class="w-3 h-3"></i> Belum Bayar
                                            </span>
                                        @elseif($status == 'pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-[11px] font-bold border border-yellow-200 inline-flex items-center gap-1">
                                                <i data-lucide="loader-2" class="w-3 h-3 animate-spin text-yellow-600"></i> Verifikasi
                                            </span>
                                        @elseif(in_array($status, ['paid', 'approved', 'lunas', 'success', 'selesai']))
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[11px] font-bold border border-green-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Lunas
                                            </span>
                                        @elseif(in_array($status, ['rejected', 'ditolak', 'failed', 'gagal']))
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[11px] font-bold border border-red-200 inline-flex items-center gap-1">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($status == 'unpaid')
                                                @php 
                                                    $route = $payment->booking_id 
                                                        ? route('customer.payment.create', $payment->booking_id) 
                                                        : route('customer.payment.product', $payment->order_id);
                                                @endphp
                                                <a href="{{ $route }}" 
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow transition transform hover:scale-105">
                                                    <i data-lucide="credit-card" class="w-3 h-3"></i> Bayar
                                                </a>

                                                @if(!$payment->booking_id)
                                                <form action="{{ route('customer.payment.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-black text-white rounded-lg text-xs font-bold shadow transition-all duration-300 transform hover:scale-105">
                                                        <i data-lucide="trash-2" class="w-3 h-3"></i> Batal
                                                    </button>
                                                </form>
                                                @endif

                                            @elseif($status == 'pending')
                                                <span class="text-amber-600 text-[10px] font-bold inline-flex items-center gap-1 bg-amber-50 px-2 py-1 rounded border border-amber-100">
                                                    Verifikasi Admin
                                                </span>

                                            @elseif(in_array($status, ['rejected', 'ditolak', 'failed', 'gagal']))
                                                @php 
                                                    $reRoute = $payment->booking_id 
                                                        ? route('customer.payment.create', $payment->booking_id) 
                                                        : route('customer.payment.product', $payment->order_id);
                                                @endphp
                                                <a href="{{ $reRoute }}" 
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold shadow-md transition transform hover:scale-105">
                                                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> Re-Upload
                                                </a>

                                            {{-- BAGIAN YANG DIUBAH: HANYA TULISAN SELESAI WARNA HIJAU --}}
                                            @elseif(in_array($status, ['paid', 'approved', 'lunas', 'success', 'selesai']))
                                                <span class="text-green-600 font-bold text-xs flex items-center gap-1">
                                                    <i data-lucide="check-check" class="w-4 h-4"></i> Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12 text-gray-500 italic bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="receipt" class="w-10 h-10 text-gray-300 mb-2"></i>
                                            <p>Belum ada riwayat transaksi pembayaran.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(method_exists($payments, 'links'))
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>