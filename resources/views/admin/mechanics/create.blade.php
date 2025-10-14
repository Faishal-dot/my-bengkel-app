<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-7 h-7 text-indigo-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Tambah Mekanik</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-indigo-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto">

            <!-- Step Indicator -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <i data-lucide="user-plus" class="w-5 h-5 text-indigo-600"></i>
                <span class="text-indigo-600 font-semibold">Form Tambah</span>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8">

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

                <!-- Form -->
                <form method="POST" action="{{ route('admin.mechanics.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nama -->
                    <div class="relative">
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                               placeholder=" " required>
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all 
                                     peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Nama Mekanik
                        </label>
                    </div>

                    <!-- Telepon -->
                    <div class="relative">
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                               placeholder=" ">
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all 
                                     peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Telepon (opsional)
                        </label>
                    </div>

                    <!-- Spesialisasi -->
                    <div class="relative">
                        <input type="text" name="specialization" value="{{ old('specialization') }}"
                               class="peer w-full border-gray-300 rounded-lg px-4 pt-5 pb-2 shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                               placeholder=" ">
                        <label class="absolute left-4 top-2 text-gray-500 text-sm transition-all 
                                     peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base">
                            Spesialisasi (opsional)
                        </label>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center gap-4 pt-6">
                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-lg shadow-md hover:from-indigo-600 hover:to-purple-700 transition font-semibold">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan
                        </button>
                        <a href="{{ route('admin.mechanics.index') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
