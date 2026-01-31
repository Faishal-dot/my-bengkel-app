<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wallet-cards" class="w-6 h-6 text-blue-600"></i>
            Laporan Penghasilan
        </h2>
    </x-slot>

    @php
        /* RE-CALCULATE TOTALS WITH DISCOUNT
           Kita hitung ulang agar Summary Card di atas sinkron dengan harga diskon di tabel
        */
        
        // 1. Hitung Total Booking (Jasa)
        $totalBookingWithDiscount = 0;
        foreach($bookings as $b) {
            $price = ($b->service && $b->service->discount_price > 0) 
                     ? (float)$b->service->discount_price 
                     : (float)($b->service->price ?? 0);
            $totalBookingWithDiscount += $price;
        }

        // 2. Hitung Total Order (Produk)
        $totalOrderWithDiscount = 0;
        foreach($orders as $o) {
            $price = ($o->product && $o->product->discount_price > 0) 
                     ? (float)$o->product->discount_price 
                     : (float)($o->product->price ?? 0);
            $totalOrderWithDiscount += ($price * ($o->quantity ?? 1));
        }

        // 3. Grand Total
        $grandTotal = $totalBookingWithDiscount + $totalOrderWithDiscount;
    @endphp

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

            {{-- ================= SUMMARY CARD (SUDAH DISKON) ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="p-6 bg-blue-600 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <i data-lucide="calendar-check" class="w-8 h-8 opacity-70"></i>
                        <span class="text-xs uppercase font-bold">Booking</span>
                    </div>
                    <p class="mt-4 text-sm opacity-80">Penghasilan Jasa (Net)</p>
                    <p class="text-3xl font-extrabold">
                        Rp {{ number_format($totalBookingWithDiscount, 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-6 bg-green-600 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <i data-lucide="shopping-bag" class="w-8 h-8 opacity-70"></i>
                        <span class="text-xs uppercase font-bold">Produk</span>
                    </div>
                    <p class="mt-4 text-sm opacity-80">Penjualan Produk (Net)</p>
                    <p class="text-3xl font-extrabold">
                        Rp {{ number_format($totalOrderWithDiscount, 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-6 bg-indigo-600 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <i data-lucide="trending-up" class="w-8 h-8 opacity-70"></i>
                        <span class="text-xs uppercase font-bold">Total</span>
                    </div>
                    <p class="mt-4 text-sm opacity-80">Total Pendapatan Bersih</p>
                    <p class="text-3xl font-extrabold">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </p>
                </div>

            </div>

            {{-- ================= HISTORY BOOKING ================= --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide">

                <div class="mb-6 text-left">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5 text-blue-600"></i>
                        History Booking (Jasa)
                    </h3>
                    <p class="text-gray-500">
                        Riwayat penyelesaian jasa servis
                    </p>
                </div>

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
                                @php
                                    $hargaAsli = (float)($b->service->price ?? 0);
                                    $hargaDiskon = ($b->service && $b->service->discount_price > 0) ? (float)$b->service->discount_price : null;
                                @endphp
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.08 }}s">

                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-mono text-xs font-bold text-blue-600">
                                        #{{ $b->id }}
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <p class="font-bold text-gray-800">{{ $b->customer_name ?? ($b->user->name ?? 'User') }}</p>
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
                                        <p class="font-semibold text-gray-800">{{ $b->service->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">Mekanik: {{ $b->mechanic->name ?? 'Belum Ada' }}</p>
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-right">
                                        @if($hargaDiskon && $hargaDiskon < $hargaAsli)
                                            <div class="text-[10px] text-gray-400 line-through">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</div>
                                            <div class="font-bold text-rose-600">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</div>
                                        @else
                                            <div class="font-bold text-blue-700">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center text-xs text-gray-500">
                                        {{ $b->created_at->format('d-m-Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-12 text-center text-gray-500 italic bg-gray-50">Belum ada history booking</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ================= HISTORY PRODUK ================= --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide">

                <div class="mb-6 text-left">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-green-600"></i>
                        History Penjualan Produk
                    </h3>
                    <p class="text-gray-500"> Riwayat transaksi penjualan sparepart / oli </p>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-green-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-green-500 text-center">ID</th>
                                <th class="px-4 py-3 border-r border-green-500 text-left">Customer</th>
                                <th class="px-4 py-3 border-r border-green-500 text-left">Produk</th>
                                <th class="px-4 py-3 border-r border-green-500 text-center">Qty</th>
                                <th class="px-4 py-3 border-r border-green-500 text-right">Subtotal</th>
                                <th class="px-4 py-3 text-center">Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $index => $o)
                                @php
                                    $priceOriginal = (float)($o->product->price ?? 0);
                                    $priceDiscount = ($o->product && $o->product->discount_price > 0) ? (float)$o->product->discount_price : null;
                                    $qty = $o->quantity ?? 1;
                                    $finalUnitPrice = ($priceDiscount && $priceDiscount < $priceOriginal) ? $priceDiscount : $priceOriginal;
                                @endphp
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-green-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.08 }}s">

                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-mono text-xs font-bold text-green-600">#{{ $o->id }}</td>
                                    
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <p class="font-bold text-gray-800">{{ $o->user->name ?? 'User' }}</p>
                                        <p class="text-xs text-gray-500">{{ $o->user->email ?? '-' }}</p>
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <p class="font-semibold text-gray-800">{{ $o->product->name ?? '-' }}</p>
                                        @if($priceDiscount)
                                            <span class="text-[9px] bg-red-100 text-red-600 px-1 rounded font-bold uppercase italic">Diskon</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-bold">{{ $qty }}</td>

                                    <td class="px-4 py-3 border-r border-gray-200 text-right">
                                        @if($priceDiscount && $priceDiscount < $priceOriginal)
                                            <div class="text-[10px] text-gray-400 line-through">Rp {{ number_format($priceOriginal * $qty, 0, ',', '.') }}</div>
                                            <div class="font-bold text-rose-600">Rp {{ number_format($finalUnitPrice * $qty, 0, ',', '.') }}</div>
                                        @else
                                            <div class="font-bold text-green-700">Rp {{ number_format($priceOriginal * $qty, 0, ',', '.') }}</div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center text-xs text-gray-500">{{ $o->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-12 text-center text-gray-500 italic bg-gray-50">Belum ada history penjualan produk</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>