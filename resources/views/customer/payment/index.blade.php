<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
            Riwayat Pembayaran
        </h2>
    </x-slot>

    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }
        .fade-row { opacity:0; animation: fadeRow .6s ease-out forwards; }
        @keyframes fadeRow { to { opacity:1; } }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi Anda</h3>
                    <p class="text-gray-600 text-sm">Pantau status pembayaran dan selesaikan transaksi Anda.</p>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-300 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs tracking-wider">
                                <th class="px-4 py-3 border-r border-blue-500 text-center w-12">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Tipe</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Item / Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Info Detail</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Harga</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @php $globalIteration = 1; @endphp
                            @forelse ($payments as $payment)
                                @php
                                    $status = strtolower($payment->status);
                                    $isBooking = !is_null($payment->booking_id);
                                @endphp

                                {{-- JIKA PRODUK & MEMILIKI DETAIL (KERANJANG) --}}
                                @if(!$isBooking && $payment->order && $payment->order->orderDetails && $payment->order->orderDetails->count() > 0)
                                    @foreach($payment->order->orderDetails as $detail)
                                        <tr class="hover:bg-blue-50 transition fade-row" style="animation-delay: {{ $globalIteration * 0.05 }}s">
                                            <td class="px-4 py-4 text-center text-gray-500 border-r border-gray-200">{{ $globalIteration++ }}</td>
                                            
                                            <td class="px-4 py-4 border-r border-gray-200">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700 border border-purple-200">
                                                    Produk
                                                </span>
                                            </td>

                                            <td class="px-4 py-4 border-r border-gray-200 font-semibold text-gray-800">
                                                {{ $detail->product->name ?? 'Produk Tidak Diketahui' }}
                                            </td>

                                            <td class="px-4 py-4 border-r border-gray-200 text-gray-600">
                                                <div class="flex items-center gap-1 text-xs">
                                                    <i data-lucide="package" class="w-3.5 h-3.5 text-gray-400"></i>
                                                    {{ $detail->quantity }} Item
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-4 border-r border-gray-200">
                                                <p class="font-bold text-gray-800">
                                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                                </p>
                                            </td>

                                            <td class="px-4 py-4 border-r border-gray-200 text-center">
                                                {{-- BADGE STATUS MANUAL --}}
                                                @if ($status === 'unpaid')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200 whitespace-nowrap">
                                                        <i data-lucide="alert-circle" class="w-3 h-3"></i> BELUM BAYAR
                                                    </span>
                                                @elseif ($status === 'pending')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold border border-blue-200 whitespace-nowrap">
                                                        <i data-lucide="clock" class="w-3 h-3"></i> VERIFIKASI
                                                    </span>
                                                @elseif (in_array($status, ['approved', 'paid', 'success', 'lunas', 'selesai']))
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-200 whitespace-nowrap">
                                                        <i data-lucide="check-circle" class="w-3 h-3"></i> LUNAS
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold border border-rose-200 whitespace-nowrap">
                                                        <i data-lucide="x-circle" class="w-3 h-3"></i> DITOLAK
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 text-center">
                                                @if ($status === 'unpaid' || in_array($status, ['rejected', 'failed', 'ditolak']))
                                                    <a href="{{ route('customer.payment.product', $payment->order_id) }}"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-md transition transform hover:scale-105">
                                                        <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                                        {{ $status === 'unpaid' ? 'Bayar' : 'Re-upload' }}
                                                    </a>
                                                @elseif (in_array($status, ['approved', 'paid', 'success', 'lunas', 'selesai']))
                                                    <span class="text-emerald-600 text-[10px] font-bold uppercase tracking-widest italic">Selesai</span>
                                                @else
                                                    <span class="text-gray-400 text-[10px] italic whitespace-nowrap">Diproses</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                {{-- JIKA SERVIS / DATA SINGLE --}}
                                @else
                                    <tr class="hover:bg-blue-50 transition fade-row" style="animation-delay: {{ $globalIteration * 0.05 }}s">
                                        <td class="px-4 py-4 text-center text-gray-500 border-r border-gray-200">{{ $globalIteration++ }}</td>
                                        
                                        <td class="px-4 py-4 border-r border-gray-200">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                {{ $isBooking ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-purple-100 text-purple-700 border border-purple-200' }}">
                                                {{ $isBooking ? 'Servis' : 'Produk' }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 border-r border-gray-200 font-semibold text-gray-800">
                                            {{ $isBooking 
                                                ? ($payment->booking->service->name ?? '-') 
                                                : ($payment->order->product->name ?? '-') }}
                                        </td>

                                        <td class="px-4 py-4 border-r border-gray-200 text-gray-600">
                                            @if($isBooking)
                                                <div class="flex items-center gap-1 text-xs">
                                                    <i data-lucide="car" class="w-3.5 h-3.5 text-gray-400"></i>
                                                    {{ $payment->booking->vehicle->plate_number ?? '-' }}
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1 text-xs">
                                                    <i data-lucide="package" class="w-3.5 h-3.5 text-gray-400"></i>
                                                    {{ $payment->order->quantity ?? 1 }} Item
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td class="px-4 py-4 border-r border-gray-200">
                                            <p class="font-bold text-gray-800">
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            </p>
                                        </td>

                                        <td class="px-4 py-4 border-r border-gray-200 text-center">
                                            @if ($status === 'unpaid')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200 whitespace-nowrap">
                                                    <i data-lucide="alert-circle" class="w-3 h-3"></i> BELUM BAYAR
                                                </span>
                                            @elseif ($status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold border border-blue-200 whitespace-nowrap">
                                                    <i data-lucide="clock" class="w-3 h-3"></i> VERIFIKASI
                                                </span>
                                            @elseif (in_array($status, ['approved', 'paid', 'success', 'lunas', 'selesai']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-200 whitespace-nowrap">
                                                    <i data-lucide="check-circle" class="w-3 h-3"></i> LUNAS
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold border border-rose-200 whitespace-nowrap">
                                                    <i data-lucide="x-circle" class="w-3 h-3"></i> DITOLAK
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-center">
                                            @if ($status === 'unpaid' || in_array($status, ['rejected', 'failed', 'ditolak']))
                                                <a href="{{ $isBooking ? route('customer.payment.create', $payment->booking_id) : route('customer.payment.product', $payment->order_id) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-md transition transform hover:scale-105">
                                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                                    {{ $status === 'unpaid' ? 'Bayar' : 'Re-upload' }}
                                                </a>
                                            @elseif (in_array($status, ['approved', 'paid', 'success', 'lunas', 'selesai']))
                                                <span class="text-emerald-600 text-[10px] font-bold uppercase tracking-widest italic">Selesai</span>
                                            @else
                                                <span class="text-gray-400 text-[10px] italic whitespace-nowrap">Diproses</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16 text-gray-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="bg-gray-100 p-4 rounded-full">
                                                <i data-lucide="receipt" class="w-8 h-8 text-gray-400"></i>
                                            </div>
                                            <span class="font-medium">Belum ada riwayat transaksi ditemukan</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>