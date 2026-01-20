<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - #{{ $payment->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        body { font-family: 'Inter', sans-serif; }

        @media print {
            .no-print { 
                display: none !important; 
            }
            
            body { 
                background-color: white !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            .print-area { 
                border: none !important; 
                box-shadow: none !important; 
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 10mm !important;
            }

            @page {
                margin: 5mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 py-10">
    
    <div class="max-w-xl mx-auto bg-white shadow-2xl border border-gray-200 print-area rounded-xl overflow-hidden">
        
        {{-- Toolbar Atas --}}
        <div class="no-print bg-slate-800 px-6 py-4 flex justify-between items-center">
            {{-- Logika Tombol Kembali Otomatis --}}
            @php
                $backUrl = auth()->user()->role === 'admin' 
                            ? route('admin.payments.index') 
                            : route('customer.payment.index');
            @endphp
            
            <a href="{{ $backUrl }}" class="text-slate-300 hover:text-white flex items-center gap-2 text-sm font-semibold transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
            
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-lg hover:bg-blue-500 transition uppercase tracking-wider">
                Cetak Nota
            </button>
        </div>

        <div class="p-10">
            {{-- Header Nota --}}
            <div class="text-center border-b-2 border-slate-50 pb-8 mb-8">
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tighter uppercase italic">
                    BENGKEL<span class="text-blue-600">KU</span>
                </h1>
                <p class="text-sm text-slate-400 mt-1 font-medium italic">Professional Auto Care Solution</p>
                <div class="mt-4 text-[11px] text-slate-400 uppercase tracking-widest font-bold space-y-1">
                    <p>Jl. Raya Bengkel No. 123, Indonesia</p>
                    <p>Telp: 0812-3456-7890</p>
                </div>
            </div>

            {{-- Info Transaksi --}}
            <div class="flex justify-between text-sm mb-10 bg-slate-50 p-4 rounded-lg">
                <div>
                    <p class="text-slate-400 uppercase text-[10px] font-black tracking-widest mb-1">Pelanggan</p>
                    <p class="font-bold text-slate-800 text-lg capitalize">
                        @if($payment->booking)
                            {{ $payment->booking->user->name ?? 'Pelanggan' }}
                        @elseif($payment->order)
                            {{ $payment->order->user->name ?? 'Pelanggan' }}
                        @else
                            {{ $payment->user->name ?? 'Pelanggan' }}
                        @endif
                    </p>
                </div>
                <div class="text-right border-l border-slate-200 pl-4">
                    <p class="text-slate-400 uppercase text-[10px] font-black tracking-widest mb-1">Invoice Info</p>
                    <p class="font-bold text-slate-800">{{ $payment->created_at->format('d M Y') }}</p>
                    <p class="text-[10px] text-slate-400 font-mono mt-1 font-bold">#{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            {{-- Detail Item --}}
            <table class="w-full text-sm mb-10">
                <thead>
                    <tr class="border-b-2 border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                        <th class="text-left py-3">Deskripsi Layanan / Produk</th>
                        <th class="text-right py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr>
                        <td class="py-6 pr-4">
                            @if($payment->booking_id && $payment->booking)
                                <p class="font-bold text-slate-800 text-base leading-tight">{{ $payment->booking->service->name }}</p>
                                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1 font-medium">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-bold uppercase">{{ $payment->booking->vehicle->plate_number ?? '-' }}</span>
                                    {{ $payment->booking->vehicle->brand ?? '-' }}
                                </p>
                            @elseif($payment->order_id && $payment->order)
                                <p class="font-bold text-slate-800 text-base leading-tight">{{ $payment->order->product->name }}</p>
                                <p class="text-xs text-slate-500 mt-2 font-medium">Kuantitas: <span class="font-bold text-slate-700">{{ $payment->order->quantity }} item</span></p>
                            @else
                                <p class="font-bold text-red-400 italic text-xs tracking-tight">Data transaksi tidak ditemukan</p>
                            @endif
                        </td>
                        <td class="py-6 text-right align-top">
                            @php
                                $originalPrice = 0;
                                // Cek apakah ini booking atau order untuk mengambil harga asli
                                if($payment->booking_id && $payment->booking) {
                                    $originalPrice = $payment->booking->service->price;
                                } elseif($payment->order_id && $payment->order) {
                                    $originalPrice = $payment->order->product->price * $payment->order->quantity;
                                }
                            @endphp

                            {{-- Tampilkan harga coret jika harga asli lebih besar dari jumlah yang dibayar --}}
                            @if($originalPrice > $payment->amount)
                                <p class="text-xs text-slate-400 line-through decoration-red-400 decoration-2">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </p>
                            @endif
                            
                            <p class="font-bold text-slate-800 text-base">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-800">
                        <td class="pt-6 pb-2 text-slate-400 font-bold uppercase text-[10px] tracking-widest">Grand Total Bayar</td>
                        <td class="pt-6 pb-2 text-right text-2xl font-extrabold text-blue-600 italic">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            {{-- Footer Nota --}}
            <div class="text-center pt-8 border-t border-dashed border-slate-200">
                <div class="inline-block bg-green-50 text-green-600 border border-green-100 px-6 py-1 rounded-full text-[11px] font-black uppercase mb-6 tracking-[0.2em] transform -rotate-1 shadow-sm">
                    LUNAS
                </div>
                <p class="text-[11px] text-slate-400 font-medium tracking-tight">
                    Simpan nota digital ini sebagai tanda bukti resmi.<br>
                    Terima kasih telah mempercayakan kendaraan Anda pada <span class="font-bold text-slate-600">BengkelKu</span>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>