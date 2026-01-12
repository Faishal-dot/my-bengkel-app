<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 animate-fadeSlideDown">
            <i data-lucide="pencil-line" class="w-7 h-7 text-indigo-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">
                Edit Mekanik
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-indigo-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto">

            <div class="flex items-center justify-center gap-2 mb-8 animate-fadeSlideUp">
                <i data-lucide="edit" class="w-5 h-5 text-indigo-600"></i>
                <span class="text-indigo-600 font-semibold text-lg tracking-wide">
                    Formulir Edit Mekanik
                </span>
            </div>

            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8 animate-fadeIn">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm animate-shake">
                        <ul class="list-disc pl-6 space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('admin.mechanics.update', $mechanic) }}"
                    class="space-y-6 animate-fadeIn delay-150"
                >
                    @csrf
                    @method('PUT')

                    <div class="group transition-all duration-300">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="user-cog" class="w-4 h-4 text-indigo-500 group-hover:rotate-12 transition"></i>
                            Nama Mekanik
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $mechanic->name) }}"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                   transition-all duration-300 bg-white
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan nama mekanic..."
                            required
                        >
                        @error('name')
                        <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group transition-all duration-300">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="id-card" class="w-4 h-4 text-indigo-500 group-hover:rotate-12 transition"></i>
                            Nomor KTP
                        </label>
                        <input
                            type="text"
                            name="ktp"
                            value="{{ old('ktp', $mechanic->ktp) }}"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                   transition-all duration-300 bg-white
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan nomor KTP..."
                            required
                        >
                        @error('ktp')
                        <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group transition-all duration-300">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="phone" class="w-4 h-4 text-indigo-500 group-hover:rotate-12 transition"></i>
                            Telepon
                        </label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $mechanic->phone) }}"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                   transition-all duration-300
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan nomor telepon..."
                        >
                        @error('phone')
                        <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group transition-all duration-300">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="map-pin" class="w-4 h-4 text-indigo-500 group-hover:rotate-12 transition"></i>
                            Alamat Rumah
                        </label>
                        <textarea
                            name="address"
                            rows="3"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                   transition-all duration-300
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan alamat lengkap..."
                        >{{ old('address', $mechanic->address) }}</textarea>
                        @error('address')
                        <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group transition-all duration-300">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="wrench" class="w-4 h-4 text-indigo-500 group-hover:rotate-12 transition"></i>
                            Spesialisasi
                        </label>
                        <input
                            type="text"
                            name="specialization"
                            value="{{ old('specialization', $mechanic->specialization) }}"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                   transition-all duration-300
                                   hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30"
                            placeholder="Masukkan bidang spesialisasi..."
                        >
                        @error('specialization')
                        <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-6">
                        <button
                            type="submit"
                            class="flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-xl shadow-md 
                                   hover:from-indigo-600 hover:to-purple-700 transform hover:scale-[1.04] active:scale-95
                                   transition-all duration-200 font-semibold">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.mechanics.index') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 text-gray-600
                                  hover:bg-gray-100 hover:shadow transition font-semibold">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        .animate-fadeIn { animation: fadeIn .7s ease-out; }
        .animate-fadeSlideDown { animation: fadeSlideDown .7s ease-out; }
        .animate-fadeSlideUp { animation: fadeSlideUp .7s ease-out; }
        .animate-shake { animation: shake .4s ease-in-out; }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>