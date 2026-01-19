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
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }
        .animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .animate-slideDown { animation: slideDown 0.6s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-150 { animation-delay: 0.15s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-250 { animation-delay: 0.25s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS ALERT --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm animate-fadeInUp">
                    <div class="p-1 bg-emerald-100 rounded-full"><i data-lucide="check-circle-2" class="w-5 h-5"></i></div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ERROR ALERT --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl shadow-sm animate-fadeInUp">
                    <div class="flex items-center gap-2 mb-2 font-bold"><i data-lucide="alert-circle" class="w-5 h-5"></i><span>Terjadi Kesalahan</span></div>
                    <ul class="list-disc pl-9 space-y-1 text-sm">
                        @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-2xl shadow-blue-900/10 rounded-3xl border border-white p-8 relative overflow-visible animate-fadeInUp delay-100">
                
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full blur-2xl -mr-10 -mt-10 z-0 pointer-events-none animate-float opacity-60"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-blue-50 to-purple-50 rounded-full blur-xl -ml-10 -mb-10 z-0 pointer-events-none animate-float delay-500 opacity-60"></div>

                <form id="bookingForm" action="{{ route('customer.booking.store') }}" method="POST" class="space-y-6 relative z-10">
                    @csrf

                    {{-- SECTION 1: DATA DIRI --}}
                    <div class="space-y-6 relative z-10">
                        <h3 class="text-sm font-black uppercase tracking-widest text-blue-600 flex items-center gap-2">
                            Data Diri Pelanggan
                        </h3>

                        <div class="group animate-fadeInUp delay-150">
                            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors"><i data-lucide="user" class="w-4 h-4"></i></div>
                                Nama Lengkap
                            </label>
                            <input type="text" name="customer_name" required 
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('customer_name', Auth::user()->name) }}"
                                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all text-gray-700 font-medium shadow-sm">
                        </div>

                        <div class="group animate-fadeInUp delay-200">
                            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors"><i data-lucide="phone" class="w-4 h-4"></i></div>
                                Nomor Telepon / WhatsApp
                            </label>
                            <input type="number" name="customer_phone" required placeholder="Contoh: 08123456789"
                                value="{{ old('customer_phone') }}"
                                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all text-gray-700 font-medium shadow-sm">
                        </div>

                        <div class="group animate-fadeInUp delay-250">
                            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors"><i data-lucide="map-pin" class="w-4 h-4"></i></div>
                                Alamat Lengkap
                            </label>
                            <textarea name="customer_address" rows="2" required placeholder="Alamat lengkap..."
                                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all text-gray-700 resize-none shadow-sm">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>

                    <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-200 to-transparent my-8"></div>

                    {{-- SECTION 2: DETAIL BOOKING --}}
                    <div class="space-y-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-indigo-600 flex items-center gap-2">
                            Detail Layanan & Kendaraan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- CUSTOM DROPDOWN LAYANAN --}}
                            <div class="relative z-30 animate-fadeInUp delay-300" id="dropdown-service">
                                <label class="flex items-center justify-between mb-2 text-gray-700 font-bold text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-blue-100 rounded text-blue-600 shadow-sm"><i data-lucide="wrench" class="w-4 h-4"></i></div>
                                        Pilih Layanan
                                    </div>
                                </label>
                                <input type="hidden" name="service_id" id="service_id_input" required value="{{ old('service_id', request('service_id')) }}">
                                
                                <button type="button" 
                                    @if(!request('service_id')) onclick="toggleDropdown('service')" @endif 
                                    id="service-button"
                                    class="w-full flex items-center justify-between bg-white border-2 {{ request('service_id') ? 'border-gray-200 bg-gray-50/50 cursor-not-allowed' : 'border-blue-100 hover:border-blue-400' }} rounded-2xl px-5 py-4 text-left font-semibold text-gray-700 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm">
                                    <span id="service-label" class="truncate pr-2 text-sm flex items-center gap-2">
                                        @if(old('service_id') || request('service_id'))
                                            @php 
                                                $s = $services->firstWhere('id', old('service_id', request('service_id')));
                                                $displayPrice = $s->discount_price ?? $s->price;
                                            @endphp
                                            @if(request('service_id')) <i data-lucide="lock" class="w-3 h-3 text-gray-400"></i> @endif
                                            {{ $s->name }} — Rp{{ number_format($displayPrice, 0, ',', '.') }}
                                        @else
                                            -- Pilih Layanan --
                                        @endif
                                    </span>
                                    {{-- PANAH HANYA MUNCUL JIKA TIDAK ADA REQUEST SERVICE_ID (MANUAL) --}}
                                    @if(!request('service_id'))
                                        <i data-lucide="chevron-down" class="w-5 h-5 text-blue-500 transition-transform duration-300 flex-shrink-0" id="service-icon"></i>
                                    @endif
                                </button>
                                
                                @if(!request('service_id'))
                                <div id="service-list" class="hidden absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-1 animate-fadeInUp ring-1 ring-black ring-opacity-5">
                                    @foreach($services as $service)
                                        @php 
                                            $finalPrice = $service->discount_price ?? $service->price;
                                            $isSelected = old('service_id', request('service_id')) == $service->id;
                                        @endphp
                                        <div onclick="selectOption('service', '{{ $service->id }}', '{{ $service->name }} — Rp{{ number_format($finalPrice, 0, ',', '.') }}')"
                                            data-id="{{ $service->id }}"
                                            class="service-item flex items-center justify-between px-4 py-3 rounded-xl cursor-pointer transition-all font-medium text-sm {{ $isSelected ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                            <span>{{ $service->name }}</span>
                                            <div class="text-right">
                                                @if($service->discount_price)
                                                    <span class="text-[10px] text-gray-400 line-through block">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                                                    <span class="text-rose-600">Rp{{ number_format($service->discount_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span>Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            {{-- CUSTOM DROPDOWN KENDARAAN --}}
                            <div class="relative z-30 animate-fadeInUp delay-400" id="dropdown-vehicle">
                                <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                    <div class="p-1.5 bg-indigo-100 rounded text-indigo-600 shadow-sm"><i data-lucide="car" class="w-4 h-4"></i></div>
                                    Pilih Kendaraan
                                </label>
                                <input type="hidden" name="vehicle_id" id="vehicle_id_input" required value="{{ old('vehicle_id') }}">
                                <button type="button" onclick="toggleDropdown('vehicle')" id="vehicle-button"
                                    class="w-full flex items-center justify-between bg-white border-2 border-indigo-100 rounded-2xl px-5 py-4 text-left font-semibold text-gray-700 hover:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm">
                                    <span id="vehicle-label" class="truncate pr-2 text-sm">
                                        @if(old('vehicle_id'))
                                            @php $v = $vehicles->firstWhere('id', old('vehicle_id')); @endphp
                                            {{ $v->brand }} {{ $v->model }} ({{ $v->plate_number }})
                                        @else
                                            -- Pilih Kendaraan --
                                        @endif
                                    </span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-indigo-500 transition-transform duration-300 flex-shrink-0" id="vehicle-icon"></i>
                                </button>
                                
                                <div id="vehicle-list" class="hidden absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-1 animate-fadeInUp ring-1 ring-black ring-opacity-5">
                                    @foreach($vehicles as $vehicle)
                                        @php $isSelectedVeh = old('vehicle_id') == $vehicle->id; @endphp
                                        <div onclick="selectOption('vehicle', '{{ $vehicle->id }}', '{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})')"
                                            data-id="{{ $vehicle->id }}"
                                            class="vehicle-item flex items-center px-4 py-3 rounded-xl cursor-pointer transition-all font-medium text-sm {{ $isSelectedVeh ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                                            {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        {{-- TANGGAL BOOKING --}}
                        <div class="relative z-10 group animate-fadeInUp delay-500">
                            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                <div class="p-1.5 bg-blue-50 rounded text-blue-500 group-hover:bg-blue-100 transition-colors">
                                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                                </div>
                                Rencana Tanggal & Jam Booking
                            </label>
                            <input type="datetime-local" name="booking_date" required 
                                min="{{ date('Y-m-d\TH:i') }}"
                                value="{{ old('booking_date') }}"
                                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all text-gray-700 font-medium">
                            <p class="mt-1 text-xs text-gray-500 italic">*Silakan tentukan jam kedatangan Anda.</p>
                        </div>

                        {{-- KELUHAN --}}
                        <div class="group animate-fadeInUp delay-500">
                            <label class="flex items-center gap-2 mb-2 text-gray-700 font-bold text-sm">
                                <div class="p-1.5 bg-amber-50 rounded text-amber-500 group-hover:bg-amber-100 transition-colors">
                                    <i data-lucide="message-square" class="w-4 h-4"></i>
                                </div>
                                Keluhan / Catatan Tambahan
                            </label>
                            <textarea name="complaint" rows="3" placeholder="Ceritakan kendala pada kendaraan Anda..."
                                class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:bg-white transition-all text-gray-700 resize-none shadow-sm">{{ old('complaint') }}</textarea>
                        </div>

                    {{-- BUTTONS --}}
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 pt-8 border-t border-dashed border-gray-200 animate-fadeInUp delay-500 relative z-0">
                        <a href="{{ route('customer.booking.index') }}"
                           class="w-full sm:w-auto flex justify-center items-center gap-2 px-6 py-3.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all font-bold text-sm">
                            <i data-lucide="x" class="w-4 h-4"></i> Batal
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto flex justify-center items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3.5 rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 font-bold text-sm group">
                            <i data-lucide="send" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i> Kirim Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function toggleDropdown(type) {
            const list = document.getElementById(type + '-list');
            if(!list) return;

            const icon = document.getElementById(type + '-icon');
            
            const otherType = type === 'service' ? 'vehicle' : 'service';
            const otherList = document.getElementById(otherType + '-list');
            const otherIcon = document.getElementById(otherType + '-icon');

            if(otherList) otherList.classList.add('hidden');
            if(otherIcon) otherIcon.classList.remove('rotate-180');

            list.classList.toggle('hidden');
            if(icon) icon.classList.toggle('rotate-180');
        }

        function selectOption(type, id, label) {
            document.getElementById(type + '_id_input').value = id;
            document.getElementById(type + '-label').innerText = label;
            
            const items = document.querySelectorAll('.' + type + '-item');
            items.forEach(item => {
                if (item.getAttribute('data-id') == id) {
                    if(type === 'service') {
                        item.classList.add('bg-blue-50', 'text-blue-700');
                        item.classList.remove('text-gray-600');
                    } else {
                        item.classList.add('bg-indigo-50', 'text-indigo-700');
                        item.classList.remove('text-gray-600');
                    }
                } else {
                    item.classList.remove('bg-blue-50', 'text-blue-700', 'bg-indigo-50', 'text-indigo-700');
                    item.classList.add('text-gray-600');
                }
            });

            const btn = document.getElementById(type + '-button');
            if(type === 'service') btn.classList.add('border-blue-500');
            else btn.classList.add('border-indigo-500');

            toggleDropdown(type);
        }

        window.onclick = function(event) {
            if (!event.target.closest('#dropdown-service') && !event.target.closest('#dropdown-vehicle')) {
                const sList = document.getElementById('service-list');
                const vList = document.getElementById('vehicle-list');
                const sIcon = document.getElementById('service-icon');
                const vIcon = document.getElementById('vehicle-icon');

                if(sList) sList.classList.add('hidden');
                if(vList) vList.classList.add('hidden');
                if(sIcon) sIcon.classList.remove('rotate-180');
                if(vIcon) vIcon.classList.remove('rotate-180');
            }
        }
    </script>
</x-app-layout>