<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="plus-circle" class="w-6 h-6 text-blue-600"></i>
            Tambah Kendaraan Baru
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI (Konsisten dengan halaman lain) --}}
    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm fade-slide" style="animation-delay:.25s">
                    <div class="flex items-center gap-2 font-bold mb-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        Terjadi Kesalahan:
                    </div>
                    <ul class="list-disc pl-6 space-y-1 text-sm">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- White Card Form --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 fade-slide" style="animation-delay:.25s">
                
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Formulir Kendaraan</h3>
                    <p class="text-gray-500 text-sm">Lengkapi data kendaraan untuk memudahkan proses servis.</p>
                </div>

                <form id="vehicleForm" action="{{ route('customer.vehicles.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Plat Nomor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                            <i data-lucide="ticket" class="w-4 h-4 text-blue-500"></i> Plat Nomor
                        </label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                               class="w-full border-gray-300 rounded-lg px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition uppercase font-semibold text-gray-800 tracking-wide"
                               placeholder="Contoh: B 1234 XYZ" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Merk --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                <i data-lucide="copyright" class="w-4 h-4 text-blue-500"></i> Merk
                            </label>
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                   class="w-full border-gray-300 rounded-lg px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Contoh: Toyota, Honda" required>
                        </div>

                        {{-- Model --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                <i data-lucide="car-front" class="w-4 h-4 text-blue-500"></i> Model
                            </label>
                            <input type="text" name="model" value="{{ old('model') }}"
                                   class="w-full border-gray-300 rounded-lg px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Contoh: Avanza, Jazz" required>
                        </div>
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                            <i data-lucide="calendar-range" class="w-4 h-4 text-blue-500"></i> Tahun Pembuatan
                        </label>
                        <input type="number" name="year" value="{{ old('year') }}"
                               min="1990" max="{{ date('Y') + 1 }}"
                               class="w-full border-gray-300 rounded-lg px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Contoh: 2022" required>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                        <a href="{{ route('customer.vehicles.index') }}"
                           class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition font-medium text-sm">
                            Batal
                        </a>
                        <button type="submit"
                                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-md transition transform hover:scale-105 font-medium text-sm">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Kendaraan
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

            // Validasi Sederhana
            if (!plate || !brand || !model || !year) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon isi semua kolom formulir.',
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'rounded-2xl' }
                });
            }

            // Tampilkan Konfirmasi
            Swal.fire({
                title: 'Simpan Kendaraan?',
                html: `
                    <div class="text-left bg-gray-50 p-4 rounded-lg text-sm text-gray-700 space-y-1">
                        <p><strong>Plat:</strong> ${plate.toUpperCase()}</p>
                        <p><strong>Merk/Model:</strong> ${brand} - ${model}</p>
                        <p><strong>Tahun:</strong> ${year}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', // Blue-600
                cancelButtonColor: '#9ca3af', // Gray-400
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Cek Lagi',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-5 py-2.5',
                    cancelButton: 'rounded-lg px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit(); // Lanjutkan submit jika dikonfirmasi
                }
            });
        });
    </script>

</x-app-layout>