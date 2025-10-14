<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-7 h-7 text-blue-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Tambah Kendaraan</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto">

            <!-- Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8">

                <form action="{{ route('customer.vehicles.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Plat Nomor -->
                    <div class="relative">
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400" placeholder=" " required>
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Plat Nomor
                        </label>
                    </div>

                    <!-- Merk -->
                    <div class="relative">
                        <input type="text" name="brand" value="{{ old('brand') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400" placeholder=" " required>
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Merk
                        </label>
                    </div>

                    <!-- Model -->
                    <div class="relative">
                        <input type="text" name="model" value="{{ old('model') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400" placeholder=" " required>
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Model
                        </label>
                    </div>

                    <!-- Tahun -->
                    <div class="relative">
                        <input type="number" name="year" value="{{ old('year') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400" placeholder=" " required>
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Tahun
                        </label>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center gap-4 pt-6">
                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:from-blue-600 hover:to-indigo-700 transition font-semibold">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan
                        </button>
                        <a href="{{ route('customer.vehicles.index') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-app-layout>