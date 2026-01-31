<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="car-front" class="w-6 h-6 text-blue-600"></i>
            Daftar Kendaraan
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI (Konsisten dengan Booking) --}}
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

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Header Section dalam Card --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Kendaraan Saya</h3>
                        <p class="text-gray-600 text-sm">Kelola data kendaraan Anda untuk memudahkan proses booking</p>
                    </div>

                    {{-- Tombol Tambah Kendaraan --}}
                    <a href="{{ route('customer.vehicles.create') }}"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Kendaraan
                    </a>
                </div>

                {{-- Table Kendaraan --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Plat Nomor</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Merk & Model</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Warna</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Tahun</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($vehicles as $index => $vehicle)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">

                                    {{-- No --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium text-gray-600">
                                        {{ $vehicles->firstItem() + $index }}
                                    </td>

                                    {{-- Plat Nomor (Gaya Badge) --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <span class="inline-block px-3 py-1 font-bold text-gray-800 bg-gray-200 rounded-md border border-gray-300 shadow-sm tracking-wider">
                                            {{ $vehicle->plate_number }}
                                        </span>
                                    </td>

                                    {{-- Merk & Model --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <div class="font-bold text-gray-800">{{ $vehicle->brand }}</div>
                                        <div class="text-xs text-gray-500">{{ $vehicle->model }}</div>
                                    </td>

                                    {{-- Warna --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded border border-gray-200 text-xs font-medium">
                                            {{ $vehicle->color }}
                                        </span>
                                    </td>

                                    {{-- Tahun --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200">
                                            <i data-lucide="calendar" class="w-3 h-3"></i> {{ $vehicle->year }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- TOMBOL EDIT --}}
                                            <a href="{{ route('customer.vehicles.edit', $vehicle->id) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold transition border border-blue-200 shadow-sm">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                            </a>

                                            {{-- TOMBOL HAPUS --}}
                                            <form action="{{ route('customer.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                    class="delete-btn inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition border border-red-200 shadow-sm">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="fade-row" style="animation-delay:.3s">
                                    <td colspan="6" class="text-center py-12 text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="p-4 bg-gray-100 rounded-full mb-3">
                                                <i data-lucide="car" class="w-10 h-10 text-gray-300"></i>
                                            </div>
                                            <p class="font-medium">Belum ada kendaraan.</p>
                                            <p class="text-xs text-gray-400">Silahkan tambah kendaraan Anda terlebih dahulu.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($vehicles->hasPages())
                    <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        {{ $vehicles->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Script SweetAlert & Lucide --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();

        // Script SweetAlert untuk konfirmasi hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-form');

                Swal.fire({
                    title: 'Hapus Kendaraan?',
                    text: "Data ini akan dihapus permanen dari sistem!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg px-5 py-2.5',
                        cancelButton: 'rounded-lg px-5 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>