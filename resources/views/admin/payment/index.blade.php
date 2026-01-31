<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="credit-card" class="w-6 h-6 text-blue-600"></i>
            Manajemen Pembayaran
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

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Alert Error --}}
                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="x-circle" class="w-5 h-5"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Pembayaran Masuk</h3>
                    <p class="text-gray-600 text-sm">Verifikasi bukti transfer dari pelanggan.</p>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-300 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs tracking-wider">
                                <th class="px-4 py-3 border-r border-blue-500 text-center w-12">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Customer</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Item / Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Total</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Bukti</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($payments as $index => $row)
                                <tr class="hover:bg-blue-50 transition fade-row" style="animation-delay: {{ $index * 0.1 }}s">
                                    <td class="px-4 py-4 text-center text-gray-500 border-r border-gray-200">{{ $loop->iteration }}</td>
                                    
                                    <td class="px-4 py-4 border-r border-gray-200">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $user = $row->booking->user ?? $row->order->user ?? null;
                                                $name = $user->name ?? 'Unknown';
                                            @endphp
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                                {{ substr($name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $name }}</p>
                                                @if($row->booking_id)
                                                    <p class="text-xs text-gray-500">{{ $row->booking->vehicle->brand ?? '-' }} ({{ $row->booking->vehicle->plate_number ?? '-' }})</p>
                                                @else
                                                    <p class="text-[10px] px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded w-fit font-semibold uppercase tracking-tighter">Pembelian Produk</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 border-r border-gray-200 text-gray-700">
                                        @if($row->booking_id)
                                            <span class="font-medium text-blue-700">{{ $row->booking->service->name ?? '-' }}</span>
                                        @elseif($row->order && $row->order->orderDetails->count() > 0)
                                            <div class="space-y-1">
                                                @foreach($row->order->orderDetails as $detail)
                                                    <div class="flex items-start gap-1 border-b border-gray-100 last:border-0 pb-1">
                                                        <span class="text-gray-800 font-medium">· {{ $detail->product->name ?? 'Produk Dihapus' }}</span>
                                                        <span class="text-[10px] bg-gray-100 px-1 rounded text-gray-500">x{{ $detail->quantity }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="font-medium">{{ $row->order->product->name ?? '-' }}</span>
                                            <p class="text-[10px] text-gray-500">Qty: {{ $row->order->quantity ?? 0 }}</p>
                                        @endif
                                    </td>
                                    
                                    <td class="px-4 py-4 border-r border-gray-200">
                                        @php
                                            $isBooking = !is_null($row->booking_id);
                                            $service = $isBooking ? optional($row->booking->service) : null;
                                            $showDiscount = $isBooking && $service && $service->discount_price;
                                        @endphp
                                        <div class="flex flex-col">
                                            @if($showDiscount)
                                                <span class="text-[10px] text-gray-400 line-through">
                                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                                </span>
                                                <p class="font-bold text-rose-600">
                                                    Rp {{ number_format($row->amount, 0, ',', '.') }}
                                                </p>
                                            @else
                                                <p class="font-bold text-gray-800">
                                                    Rp {{ number_format($row->amount, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                                        <a href="{{ route('admin.payments.show', $row->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-semibold flex items-center justify-center gap-1">
                                            <i data-lucide="eye" class="w-3 h-3"></i> Lihat Bukti
                                        </a>
                                    </td>

                                    <td class="px-4 py-4 border-r border-gray-200 text-center">
                                        @php $status = strtolower($row->status); @endphp
                                        @if($status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                <i data-lucide="clock" class="w-3 h-3"></i> Menunggu
                                            </span>
                                        @elseif(in_array($status, ['paid', 'approved', 'lunas', 'success', 'selesai']))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <i data-lucide="check-circle-2" class="w-3 h-3"></i> Lunas
                                            </span>
                                        @elseif(in_array($status, ['rejected', 'ditolak', 'failed']))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600 border border-gray-200">{{ $row->status }}</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @if(strtolower($row->status) === 'pending')
                                            <div class="flex justify-center items-center gap-2">
                                                <form action="{{ route('admin.payments.confirm', $row->id) }}" method="POST" onsubmit="return confirm('Terima pembayaran ini?')">
                                                    @csrf
                                                    <button type="submit" class="group bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-1">
                                                        <i data-lucide="check" class="w-4 h-4"></i> Terima
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.payments.reject', $row->id) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                    @csrf
                                                    <button type="submit" class="group bg-rose-500 hover:bg-rose-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-1">
                                                        <i data-lucide="x" class="w-4 h-4"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif(in_array(strtolower($row->status), ['paid', 'approved', 'lunas', 'success']))
                                            <div class="flex flex-col items-center gap-2">
                                                <span class="text-emerald-600 text-[10px] font-bold flex items-center justify-center gap-1 uppercase tracking-tighter">
                                                    <i data-lucide="check-check" class="w-3 h-3"></i> Terverifikasi
                                                </span>
                                                <a href="{{ route('admin.payments.print', $row->id) }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold shadow-md transition transform hover:scale-105">
                                                    <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Nota
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs italic opacity-50">Tidak ada aksi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12 text-gray-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="bg-gray-100 p-4 rounded-full"><i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i></div>
                                            <span class="font-medium">Belum ada data pembayaran masuk</span>
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