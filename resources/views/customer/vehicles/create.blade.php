<x-app-layout>
    {{-- Header Slot dengan Animasi Masuk --}}
    <x-slot name="header">
        <div class="flex items-center gap-3 animate-slideDown">
            <div class="p-2 bg-blue-100 rounded-lg shadow-sm hover:rotate-180 transition-transform duration-700">
                <i data-lucide="plus-circle" class="w-6 h-6 text-blue-600"></i>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                Tambah Kendaraan Baru
            </h2>
        </div>
    </x-slot>

    {{-- CUSTOM CSS ANIMATION --}}
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

    <div class="py-12 bg-gradient-to-b from-gray-50 via-white to-gray-100 min-h-screen relative overflow-hidden">
        
        {{-- Background Decoration (Blobs) --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-40 -mr-16 -mt-16 animate-float"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-100 rounded-full blur-3xl opacity-40 -ml-10 -mb-10 animate-float delay-500"></div>

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
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Formulir Kendaraan</h3>
                    <p class="text-gray-500 text-sm mt-1">Lengkapi data kendaraan untuk memudahkan proses servis.</p>
                </div>

                <form id="vehicleForm" action="{{ route('customer.vehicles.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Plat Nomor --}}
                    <div class="group animate-fadeInUp delay-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <div class="p-1.5 bg-yellow-50 rounded text-yellow-600 group-hover:bg-yellow-100 transition-colors">
                                <i data-lucide="ticket" class="w-4 h-4"></i>
                            </div>
                            Plat Nomor
                        </label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                               class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all uppercase font-bold text-gray-800 tracking-wider placeholder:font-normal placeholder:capitalize"
                               placeholder="Contoh: B 1234 XYZ" required>
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
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                   class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                                   placeholder="Contoh: Toyota, Honda" required>
                        </div>

                        {{-- Model --}}
                        <div class="group animate-fadeInUp delay-300">
                            <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <div class="p-1.5 bg-indigo-50 rounded text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                                    <i data-lucide="car-front" class="w-4 h-4"></i>
                                </div>
                                Model
                            </label>
                            <input type="text" name="model" value="{{ old('model') }}"
                                   class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                                   placeholder="Contoh: Avanza, Jazz" required>
                        </div>
                    </div>

                    {{-- Tahun --}}
                    <div class="group animate-fadeInUp delay-400">
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <div class="p-1.5 bg-green-50 rounded text-green-600 group-hover:bg-green-100 transition-colors">
                                <i data-lucide="calendar-range" class="w-4 h-4"></i>
                            </div>
                            Tahun Pembuatan
                        </label>
                        <input type="number" name="year" value="{{ old('year') }}"
                               min="1990" max="{{ date('Y') + 1 }}"
                               class="w-full border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:bg-white transition-all font-medium text-gray-700"
                               placeholder="Contoh: 2022" required>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-dashed border-gray-200 mt-8 animate-fadeInUp delay-500">
                        <a href="{{ route('customer.vehicles.index') }}"
                           class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all font-bold text-sm flex items-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i> Batal
                        </a>
                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-1 hover:shadow-blue-500/40 transition-all duration-300 font-bold text-sm group">
                            <i data-lucide="save" class="w-4 h-4 group-hover:scale-110 transition-transform"></i> Simpan Kendaraan
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

        // SweetAlert Confirmation saat Submit
        document.getElementById('vehicleForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Tahan submit asli

            const plate = document.querySelector('input[name="plate_number"]').value.trim();
            const brand = document.querySelector('input[name="brand"]').value.trim();
            const model = document.querySelector('input[name="model"]').value.trim();
            const year  = document.querySelector('input[name="year"]').value.trim();

            // Validasi Client Side
            if (!plate || !brand || !model || !year) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon isi semua kolom formulir.',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Oke, Saya Lengkapi',
                    customClass: { 
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                });
            }

            // Tampilkan Konfirmasi Cantik
            Swal.fire({
                title: 'Simpan Kendaraan?',
                html: `
                    <div class="bg-blue-50 p-5 rounded-xl text-left border border-blue-100">
                        <div class="flex items-center justify-between mb-2 pb-2 border-b border-blue-200/50">
                            <span class="text-xs text-blue-500 font-bold uppercase tracking-wider">Plat Nomor</span>
                            <span class="font-black text-gray-800 text-lg">${plate.toUpperCase()}</span>
                        </div>
                        <div class="space-y-1 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Kendaraan:</span>
                                <span class="font-bold text-gray-800">${brand} ${model}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tahun:</span>
                                <span class="font-bold text-gray-800">${year}</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 text-gray-500 text-sm">Pastikan data di atas sudah benar.</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Indigo-600
                cancelButtonColor: '#9ca3af',  // Gray-400
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Cek Lagi',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl',
                    title: 'text-xl font-bold text-gray-800',
                    confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-lg shadow-indigo-500/30',
                    cancelButton: 'rounded-xl px-6 py-3 font-medium hover:bg-gray-100 text-gray-600 bg-white border border-gray-200'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    </script>
</x-app-layout>