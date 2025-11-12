<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i data-lucide="wrench" class="w-7 h-7 text-indigo-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Tambah Layanan</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-indigo-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto animate-fadeIn">

            <!-- Step Indicator -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <i data-lucide="form-input" class="w-5 h-5 text-indigo-600"></i>
                <span class="text-indigo-600 font-semibold">Form Tambah</span>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8 transition-all duration-500 hover:shadow-2xl">

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

                <!-- Form Tambah Layanan -->
                <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nama Layanan -->
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="wrench" class="w-4 h-4 text-indigo-500"></i>
                            Nama Layanan
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 
                                   transition-all duration-300 ease-in-out
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan nama layanan..." required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i>
                            Deskripsi (opsional)
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 
                                   transition-all duration-300 ease-in-out
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Tuliskan deskripsi layanan...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Harga -->
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="wallet" class="w-4 h-4 text-indigo-500"></i>
                            Harga
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" step="1000"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 
                                   transition-all duration-300 ease-in-out
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan harga..." required>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center gap-4 pt-6">
                        <button type="submit"
                            class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-lg shadow-md 
                                   hover:from-indigo-600 hover:to-purple-700 transform hover:scale-[1.03] active:scale-95 transition-all duration-200 font-semibold">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan
                        </button>

                        <a href="{{ route('admin.services.index') }}"
                            class="flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition font-semibold">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Animasi -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
    </style

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
