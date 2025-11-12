<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i data-lucide="plus-circle" class="w-7 h-7 text-blue-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Tambah Kendaraan</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Error (server-side) -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card (initially hidden for animation) -->
            <div id="vehicleCard"
                 class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8
                        opacity-0 translate-y-6 transform
                        will-change-transform will-change-opacity">
                <form id="vehicleForm" action="{{ route('customer.vehicles.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Plat Nomor -->
                    <div class="relative transition-all duration-300 transform hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="ticket" class="w-4 h-4 text-blue-500"></i>
                            Plat Nomor
                        </label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                               class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                      focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                      transition-all duration-300 ease-in-out
                                      hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30 uppercase transform"
                               placeholder="Contoh: B 1234 XYZ" required>
                    </div>

                    <!-- Merk -->
                    <div class="relative transition-all duration-300 transform hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="factory" class="w-4 h-4 text-blue-500"></i>
                            Merk
                        </label>
                        <input type="text" name="brand" value="{{ old('brand') }}"
                               class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                      focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                      transition-all duration-300 ease-in-out
                                      hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30 transform"
                               placeholder="Contoh: Honda, Yamaha..." required>
                    </div>

                    <!-- Model -->
                    <div class="relative transition-all duration-300 transform hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="car" class="w-4 h-4 text-blue-500"></i>
                            Model
                        </label>
                        <input type="text" name="model" value="{{ old('model') }}"
                               class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                      focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                      transition-all duration-300 ease-in-out
                                      hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30 transform"
                               placeholder="Contoh: Supra X, Avanza..." required>
                    </div>

                    <!-- Tahun -->
                    <div class="relative transition-all duration-300 transform hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                            Tahun
                        </label>
                        <input type="number" name="year" value="{{ old('year') }}"
                               min="1980" max="{{ date('Y') + 1 }}"
                               class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                      focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                      transition-all duration-300 ease-in-out
                                      hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30 transform"
                               placeholder="Masukkan tahun kendaraan (misal: 2020)" required>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('customer.vehicles.index') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-300 text-gray-600 
                                  hover:bg-gray-100 transition font-medium transform hover:scale-105 duration-300">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Batal
                        </a>

                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 
                                       rounded-lg shadow-md hover:from-blue-600 hover:to-indigo-700 transition transform hover:scale-105 active:scale-95 duration-300 font-semibold">
                            <i data-lucide="save" class="w-5 h-5"></i> Simpan Kendaraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- KEYFRAMES + small helper styles --}}
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* make sure transitions are smooth */
        #vehicleCard.show {
            animation: fadeUp 450ms cubic-bezier(.2,.9,.2,1) both;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // icons
        lucide.createIcons();

        // animate card in (use class 'show' to trigger keyframes)
        document.addEventListener('DOMContentLoaded', () => {
            const card = document.getElementById('vehicleCard');
            // small delay so browser renders initial styles before animating
            setTimeout(() => {
                card.classList.add('show');
            }, 80);
        });

        // SweetAlert2 confirm before submit (prettier and non-blocking)
        document.getElementById('vehicleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const plate = document.querySelector('input[name="plate_number"]').value.trim();
            const brand = document.querySelector('input[name="brand"]').value.trim();
            const model = document.querySelector('input[name="model"]').value.trim();
            const year = document.querySelector('input[name="year"]').value.trim();

            // basic client-side validation
            if (!plate || !brand || !model || !year) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data belum lengkap',
                    text: 'Mohon isi semua field terlebih dahulu.',
                    confirmButtonText: 'Oke'
                });
                return;
            }

            // show confirmation summary
            Swal.fire({
                title: 'Konfirmasi Tambah Kendaraan',
                html: `
                    <div class="text-left">
                        <p><strong>Plat Nomor:</strong> ${plate}</p>
                        <p><strong>Merk:</strong> ${brand}</p>
                        <p><strong>Model:</strong> ${model}</p>
                        <p><strong>Tahun:</strong> ${year}</p>
                    </div>
                    <p class="mt-3 text-sm text-gray-600">Pastikan data di atas benar.</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Periksa lagi',
                focusCancel: true,
                customClass: {
                  popup: 'rounded-xl'
                }
            }).then((res) => {
                if (res.isConfirmed) {
                    // allow submit
                    e.target.submit();
                }
            });
        });

        // if there is a success message from server, show SweetAlert success
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: {!! json_encode(session('success')) !!},
                    confirmButtonText: 'OK',
                    customClass: { popup: 'rounded-xl' }
                });
            });
        @endif
    </script>
</x-app-layout>