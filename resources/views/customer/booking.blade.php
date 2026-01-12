<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <div class="flex items-center gap-3 animate-slideDown">
            <div class="p-2 bg-blue-100 rounded-lg shadow-sm hover:rotate-12 transition-transform duration-500">
                <i data-lucide="calendar-plus" class="w-6 h-6 text-blue-600"></i>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Buat Booking Baru</h2>
        </div>
    </x-slot>

    {{-- CSS Custom Animation --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .animate-slideDown { animation: slideDown 0.6s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        /* Delays */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS ALERT --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm animate-fadeInUp">
                    <div class="p-1 bg-emerald-100 rounded-full">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ERROR ALERT --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl shadow-sm animate-fadeInUp">
                    <div class="flex items-center gap-2 mb-2 font-bold">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>Terjadi Kesalahan</span>
                    </div>
                    <ul class="list-disc pl-9 space-y-1 text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- MAIN FORM CARD --}}
            <div class="bg-white shadow-2xl shadow-blue-900/10 rounded-3xl border border-white p-8 relative overflow-hidden animate-fadeInUp delay-100">
                
                {{-- Background Blob Decoration --}}
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full blur-2xl -mr-10 -mt-10 z-0 pointer-events-none animate-float opacity-60"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-blue-50 to-purple-50 rounded-full blur-xl -ml-10 -mb-10 z-0 pointer-events-none animate-float delay-500 opacity-60"></div>

                <form id="bookingForm" action="{{ route('customer.booking.store') }}" method="POST" class="space-y-6 relative z-10">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- LAYANAN --}}
        <div class="group animate-fadeInUp delay-200">
            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                </div>
                Pilih Layanan
            </label>

            <div class="relative transition-transform duration-300 focus-within:scale-[1.02]">
                {{-- TAMBAHAN: class 'bg-none' untuk menghilangkan panah bawaan plugin --}}
                <select name="service_id" id="serviceSelect" required
                    class="w-full border-gray-200 bg-gray-50/50 bg-none rounded-xl px-4 py-3.5 shadow-sm appearance-none
                           focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white focus:shadow-md transition-all cursor-pointer text-gray-700 font-medium">
                    <option value="">-- Pilih Layanan Servis --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @if(request('service_id') == $service->id) selected @endif>
                            {{ $service->name }} — Rp{{ number_format($service->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                
                {{-- Icon Custom Kita --}}
                <i data-lucide="chevron-down" class="absolute right-4 top-4 w-5 h-5 text-gray-400 pointer-events-none group-focus-within:text-blue-500 group-focus-within:rotate-180 transition-all"></i>
            </div>
        </div>

        {{-- KENDARAAN --}}
        <div class="group animate-fadeInUp delay-300">
            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors">
                    <i data-lucide="car" class="w-4 h-4"></i>
                </div>
                Pilih Kendaraan
            </label>
            <div class="relative transition-transform duration-300 focus-within:scale-[1.02]">
                {{-- TAMBAHAN: class 'bg-none' --}}
                <select name="vehicle_id" required
                    class="w-full border-gray-200 bg-gray-50/50 bg-none rounded-xl px-4 py-3.5 shadow-sm appearance-none
                           focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white focus:shadow-md transition-all cursor-pointer text-gray-700 font-medium">
                    <option value="">-- Pilih Kendaraan Anda --</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">
                            {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                        </option>
                    @endforeach
                </select>
                
                {{-- Icon Custom Kita --}}
                <i data-lucide="chevron-down" class="absolute right-4 top-4 w-5 h-5 text-gray-400 pointer-events-none group-focus-within:text-blue-500 group-focus-within:rotate-180 transition-all"></i>
            </div>
        </div>
    </div>

    {{-- TANGGAL --}}
    <div class="group animate-fadeInUp delay-400">
        <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
            <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
            </div>
            Rencana Tanggal Booking
        </label>
        <div class="relative transition-transform duration-300 focus-within:scale-[1.02]">
            <input type="date" name="booking_date" required
                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm 
                       focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white focus:shadow-md transition-all text-gray-700 font-medium">
        </div>
    </div>

    {{-- KELUHAN (OPSIONAL) --}}
    <div class="group animate-fadeInUp delay-500">
        <label class="flex items-center justify-between mb-2">
            <span class="flex items-center gap-2 text-gray-700 font-bold text-sm">
                <div class="p-1.5 bg-orange-50 rounded text-orange-500 group-hover:bg-orange-100 transition-colors">
                    <i data-lucide="message-square-warning" class="w-4 h-4"></i>
                </div>
                Keluhan / Catatan
            </span>
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 bg-gray-100 px-2 py-1 rounded-md border border-gray-200">Opsional</span>
        </label>
        
        <div class="relative transition-transform duration-300 focus-within:scale-[1.01]">
            <textarea name="complaint" rows="3"
                placeholder="Contoh: Rem belakang bunyi mencicit, tolong cek oli juga..."
                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 shadow-sm 
                       focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white focus:shadow-md transition-all placeholder:text-gray-400 resize-none text-gray-700"></textarea>
        </div>
        
        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5 ml-1">
            <i data-lucide="info" class="w-3.5 h-3.5"></i>
            Info ini akan disampaikan langsung ke mekanik.
        </p>
    </div>

    {{-- BUTTONS --}}
    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 pt-8 border-t border-dashed border-gray-200 animate-fadeInUp delay-500">
        <a href="{{ route('customer.booking.index') }}"
           class="w-full sm:w-auto flex justify-center items-center gap-2 px-6 py-3.5 rounded-xl border border-gray-200 
                  text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-300 transition-all font-bold text-sm group">
            <i data-lucide="x" class="w-4 h-4 group-hover:rotate-90 transition-transform"></i>
            Batal
        </a>

        <button type="submit"
            class="w-full sm:w-auto flex justify-center items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500
                   text-white px-8 py-3.5 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 
                   hover:-translate-y-1 active:scale-95 transition-all duration-300 font-bold text-sm group">
            <i data-lucide="send" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
            Kirim Booking
        </button>
    </div>
</form>

            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>