<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
            Riwayat Pembayaran
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI (Konsisten dengan halaman lain) --}}
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

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="mb-6 p-4 bg-blue-100 text-blue-700 border border-blue-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="info" class="w-5 h-5"></i>
                        {{ session('info') }}
                    </div>
                @endif

                {{-- Header Section dalam Card --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Tagihan</h3>
                        <p class="text-gray-600 text-sm">Kelola pembayaran layanan servis kendaraan Anda</p>
                    </div>
                </div>

                {{-- Table Container --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center w-12">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Antrian</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Service</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Kendaraan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Total</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Tanggal</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($payments as $index => $payment)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">

                                    {{-- NO --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">{{ $index + 1 }}</td>

                                    {{-- ANTRIAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($payment->booking->queue_number)
                                            <span class="inline-block w-8 h-8 rounded-full bg-blue-600 text-white leading-8 shadow-sm font-semibold">
                                                {{ $payment->booking->queue_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- SERVICE --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <span class="font-semibold text-gray-800">{{ $payment->booking->service->name ?? '-' }}</span>
                                    </td>

                                    {{-- KENDARAAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($payment->booking->vehicle)
                                            <span class="font-semibold text-gray-800">{{ $payment->booking->vehicle->plate_number }}</span>
                                            <div class="text-xs text-gray-600">
                                                {{ $payment->booking->vehicle->brand }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>

                                    {{-- TOTAL --}}
                                    <td class="px-4 py-3 border-r border-gray-200 font-bold text-gray-700">
                                        Rp {{ number_format($payment->booking->service->price ?? 0, 0, ',', '.') }}
                                    </td>

                                    {{-- TANGGAL --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($payment->booking->booking_date)->format('d-m-Y') }}
                                    </td>

                                    {{-- STATUS (Badge Style Konsisten) --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @php
                                            $status = strtolower($payment->status);
                                        @endphp

                                        @if($status == 'unpaid')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold border border-red-200 inline-flex items-center gap-1 animate-pulse">
                                                <i data-lucide="alert-circle" class="w-3 h-3"></i> Belum Dibayar
                                            </span>
                                        @elseif($status == 'pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold border border-yellow-200 inline-flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i> Verifikasi
                                            </span>
                                        @elseif(in_array($status, ['paid', 'approved', 'lunas', 'success', 'selesai', 'confirmed']))
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Lunas
                                            </span>
                                        @elseif(in_array($status, ['rejected', 'ditolak', 'gagal']))
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold border border-red-200 inline-flex items-center gap-1">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @php $status = strtolower($payment->status); @endphp

                                        @if($status == 'unpaid')
                                            <a href="{{ route('customer.payment.create', $payment->booking_id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow transition transform hover:scale-105">
                                                <i data-lucide="credit-card" class="w-3 h-3"></i> Bayar
                                            </a>
                                        @elseif(in_array($status, ['rejected', 'ditolak']))
                                            <a href="{{ route('customer.payment.create', $payment->booking_id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 border border-red-200 rounded-lg text-xs font-bold transition">
                                                <i data-lucide="refresh-cw" class="w-3 h-3"></i> Re-Upload
                                            </a>
                                        @elseif($status == 'pending')
                                            <span class="text-xs text-gray-400 italic">Menunggu Admin...</span>
                                        @elseif(in_array($status, ['paid', 'approved', 'lunas', 'success', 'selesai']))
                                            <span class="text-green-600 text-xs font-bold inline-flex items-center gap-1">
                                                <i data-lucide="check-check" class="w-4 h-4"></i> Selesai
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr class="fade-row" style="animation-delay:.3s">
                                    <td colspan="8" class="text-center py-8 text-gray-500 italic bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="receipt" class="w-10 h-10 text-gray-300 mb-2"></i>
                                            <p>Belum ada tagihan pembayaran.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (Jika ada) --}}
                @if(method_exists($payments, 'links'))
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
    
    {{-- Script Lucide --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>