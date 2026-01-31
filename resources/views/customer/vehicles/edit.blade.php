<x-app-layout>
    {{-- Header Slot dengan Animasi Masuk --}}
    <x-slot name="header">
        <div class="flex items-center gap-3 animate-slideDown">
            <div class="p-2 bg-amber-100 rounded-lg shadow-sm hover:rotate-12 transition-transform duration-500">
                <i data-lucide="edit-3" class="w-6 h-6 text-amber-600"></i>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                Edit Kendaraan
            </h2>
        </div>
    </x-slot>

    {{-- CUSTOM CSS ANIMATION (Sama dengan Create) --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        
        .animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .animate-slideDown { animation: slideDown 0.6s ease-out forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>

    {{-- Logika Pecah Plat Nomor (PHP) --}}
    @php
        $parts = explode(' ', $vehicle->plate_number);
        $prefix = $parts[0] ?? '';
        $number = $parts[1] ?? '';
        $suffix = $parts[2] ?? '';
    @endphp

    <div class="py-12 bg-gradient-to-b from-gray-50 via-white to-gray-100 min-h-screen relative overflow-hidden">
        
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-100 rounded-full blur-3xl opacity-40 -mr-16 -mt-16 animate-float"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-100 rounded-full blur-3xl opacity-40 -ml-10 -mb-10 animate-float delay-500"></div>

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl shadow-sm animate-fadeInUp">
                    <div class="flex items-center gap-2 font-bold mb-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        <span>Terjadi Kesalahan</span>
                    </div>
                    <ul class="list-disc pl-9 space-y-1 text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Main Form Card --}}
            <div class="bg-white p-8 rounded-3xl shadow-xl shadow-blue-900/5 border border-white animate-fadeInUp delay-100">
                
                <div class="mb-8 border-b border-gray-100 pb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-black text-gray-800 tracking-tight">Perbarui Data</h3>
                            <p class="text-gray-500 text-sm mt-1">Ubah informasi kendaraan Anda di bawah ini.</p>
                        </div>
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full border border-amber-100">Mode Edit</span>
                    </div>
                </div>

                <form id="vehicleForm" action="{{ route('customer.vehicles.update', $vehicle->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Plat Nomor Grid --}}
                    <div class="group animate-fadeInUp delay-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <div class="p-1.5 bg-yellow-50 rounded text-yellow-600 group-hover:bg-yellow-100 transition-colors">
                                <i data-lucide="ticket" class="w-4 h-4"></i>
                            </div>
                            Plat Nomor
                        </label>
                        
                        <div class="flex gap-3">
                            {{-- Huruf Depan --}}
                            <div class="w-20">
                                <input type="text" id="plate_prefix" maxlength="2" placeholder="B" value="{{ old('prefix', $prefix) }}"
                                    class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-3 py-3.5 shadow-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:bg-white transition-all uppercase font-bold text-center text-gray-800 tracking-widest"
                                    required>
                                <span class="text-[10px] text-gray-400 mt-1 block text-center uppercase font-medium">Wilayah</span>
                            </div>

                            {{-- Nomor Urut --}}
                            <div class="flex-1">
                                <input type="text" id="plate_number_part" maxlength="4" placeholder="1234" value="{{ old('number', $number) }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:bg-white transition-all font-bold text-center text-gray-800 tracking-[0.2em]"
                                    required>
                                <span class="text-[10px] text-gray-400 mt-1 block text-center uppercase font-medium">Nomor Urut</span>
                            </div>

                            {{-- Huruf Belakang --}}
                            <div class="w-28">
                                <input type="text" id="plate_suffix" maxlength="3" placeholder="XYZ" value="{{ old('suffix', $suffix) }}"
                                    class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-3 py-3.5 shadow-sm focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:bg-white transition-all uppercase font-bold text-center text-gray-800 tracking-widest"
                                    required>
                                <span class="text-[10px] text-gray-400 mt-1 block text-center uppercase font-medium">Seri</span>
                            </div>
                        </div>

                        {{-- Hidden Input untuk dikirim ke Controller --}}
                        <input type="hidden" name="plate_number" id="full_plate_number">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Merk --}}
                        <div class="group animate-fadeInUp delay-300">
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <div class="p-1.5 bg-blue-50 rounded text-blue-600 group-hover:bg-blue-100 transition-colors">
                                    <i data-lucide="copyright" class="w-4 h-4"></i>
                                </div>
                                Merk
                            </label>
                            <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                                   class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                                   placeholder="Contoh: Toyota" required>
                        </div>

                        {{-- Model --}}
                        <div class="group animate-fadeInUp delay-300">
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <div class="p-1.5 bg-indigo-50 rounded text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                                    <i data-lucide="car-front" class="w-4 h-4"></i>
                                </div>
                                Model
                            </label>
                            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}"
                                   class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                                   placeholder="Contoh: Avanza" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Warna --}}
                        <div class="group animate-fadeInUp delay-400">
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <div class="p-1.5 bg-purple-50 rounded text-purple-600 group-hover:bg-purple-100 transition-colors">
                                    <i data-lucide="palette" class="w-4 h-4"></i>
                                </div>
                                Warna
                            </label>
                            <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                                   class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                                   placeholder="Contoh: Hitam" required>
                        </div>

                        {{-- Tahun --}}
                        <div class="group animate-fadeInUp delay-400">
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <div class="p-1.5 bg-green-50 rounded text-green-600 group-hover:bg-green-100 transition-colors">
                                    <i data-lucide="calendar-range" class="w-4 h-4"></i>
                                </div>
                                Tahun Pembuatan
                            </label>
                            <input type="number" name="year" value="{{ old('year', $vehicle->year) }}"
                                   min="1990" max="{{ date('Y') + 1 }}"
                                   class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                                   placeholder="Contoh: 2022" required>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-dashed border-gray-200 mt-8 animate-fadeInUp delay-500">
                        <a href="{{ route('customer.vehicles.index') }}"
                           class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all font-bold text-sm flex items-center gap-2">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                        </a>
                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white px-8 py-3 rounded-xl shadow-lg shadow-amber-500/30 hover:-translate-y-1 hover:shadow-amber-500/40 transition-all duration-300 font-bold text-sm group">
                            <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        lucide.createIcons();

        // Konfirmasi SweetAlert (Identik dengan Create)
        document.getElementById('vehicleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const prefix = document.getElementById('plate_prefix').value.trim().toUpperCase();
            const number = document.getElementById('plate_number_part').value.trim();
            const suffix = document.getElementById('plate_suffix').value.trim().toUpperCase();
            
            const fullPlate = `${prefix} ${number} ${suffix}`;
            document.getElementById('full_plate_number').value = fullPlate;

            const brand = document.querySelector('input[name="brand"]').value.trim();
            const model = document.querySelector('input[name="model"]').value.trim();

            Swal.fire({
                title: 'Perbarui Data?',
                html: `
                    <div class="bg-amber-50 p-5 rounded-xl text-left border border-amber-100">
                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-amber-200/50">
                            <span class="text-xs text-amber-500 font-bold uppercase tracking-wider">Update Plat</span>
                            <span class="font-black text-gray-800 text-lg">${fullPlate}</span>
                        </div>
                        <p class="text-sm text-gray-600 text-center">Data <b>${brand} ${model}</b> akan diperbarui.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b', // Amber-500
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Perbarui',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-lg',
                    cancelButton: 'rounded-xl px-6 py-3 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    </script>
</x-app-layout>