<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="car" class="w-7 h-7 text-blue-600"></i>
                Daftar Kendaraan
            </h2>

            <a href="{{ route('customer.vehicles.create') }}"
               class="flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow text-sm font-medium transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kendaraan
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2 animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table Kendaraan --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-lg transition-all duration-300 animate-fadeIn">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-blue-600 text-white uppercase text-xs">
                            <th class="px-4 py-3 border text-center">No</th>
                            <th class="px-4 py-3 border text-left">Plat Nomor</th>
                            <th class="px-4 py-3 border text-left">Merk</th>
                            <th class="px-4 py-3 border text-left">Model</th>
                            <th class="px-4 py-3 border text-center">Tahun</th>
                            <th class="px-4 py-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $index => $vehicle)
                            <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition duration-200">
                                <td class="px-4 py-3 border text-center font-medium">{{ $vehicles->firstItem() + $index }}</td>
                                <td class="px-4 py-3 border font-medium text-gray-800">{{ $vehicle->plate_number }}</td>
                                <td class="px-4 py-3 border">{{ $vehicle->brand }}</td>
                                <td class="px-4 py-3 border">{{ $vehicle->model }}</td>
                                <td class="px-4 py-3 border text-center">{{ $vehicle->year }}</td>
                                <td class="px-4 py-3 border text-center">
                                    <form action="{{ route('customer.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-btn text-red-600 hover:text-red-800 transition flex items-center gap-1 mx-auto">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500 italic flex items-center justify-center gap-2">
                                    <i data-lucide="car" class="w-5 h-5 text-red-500"></i>
                                    Belum ada kendaraan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>

    {{-- Animasi FadeIn --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

    {{-- SweetAlert & Lucide --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();

        // Tombol Hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-form');

                Swal.fire({
                    title: 'Yakin hapus kendaraan ini?',
                    text: "Data kendaraan akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>