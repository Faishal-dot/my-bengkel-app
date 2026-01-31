<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 animate-slideDown">
            <i data-lucide="wrench" class="w-7 h-7 text-indigo-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Tambah Mekanik</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-indigo-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto animate-fadeIn">

            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300">

                {{-- ERROR BAG --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm animate-pop">
                        <ul class="pl-6 space-y-1 list-disc">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.mechanics.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="role" value="mechanic">

                    {{-- INPUT COMPONENT --}}
                    @php
                        $inputs = [
                            ['label' => 'Nama Mekanik', 'icon' => 'user', 'name' => 'name', 'type' => 'text', 'required' => true, 'max' => null],
                            // KTP dibatasi 16 digit
                            ['label' => 'Nomor KTP', 'icon' => 'id-card', 'name' => 'ktp', 'type' => 'number', 'required' => true, 'max' => 16],
                            ['label' => 'Email Login', 'icon' => 'mail', 'name' => 'email', 'type' => 'email', 'required' => true, 'max' => null],
                            ['label' => 'Password Login', 'icon' => 'lock', 'name' => 'password', 'type' => 'password', 'required' => true, 'max' => null],
                            ['label' => 'Konfirmasi Password', 'icon' => 'shield-check', 'name' => 'password_confirmation', 'type' => 'password', 'required' => true, 'max' => null],
                            // Telepon dibatasi 15 digit
                            ['label' => 'Telepon', 'icon' => 'phone', 'name' => 'phone', 'type' => 'number', 'required' => false, 'max' => 15],
                            ['label' => 'Alamat Rumah', 'icon' => 'map-pin', 'name' => 'address', 'type' => 'text', 'required' => false, 'max' => null],
                        ];
                    @endphp

                    @foreach ($inputs as $input)
                        <div class="transition-all duration-300 transform hover:scale-[1.015] animate-fadeInUp">
                            <label class="block mb-1 text-gray-600 font-medium flex items-center gap-1">
                                <i data-lucide="{{ $input['icon'] }}" class="w-4 h-4 text-indigo-500"></i>
                                {{ $input['label'] }}
                            </label>

                            <input
                                type="{{ $input['type'] }}"
                                name="{{ $input['name'] }}"
                                value="{{ old($input['name']) }}"
                                @if($input['required']) required @endif
                                @if($input['max']) 
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    maxlength="{{ $input['max'] }}" 
                                @endif
                                class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       transition-all duration-300 bg-white hover:bg-indigo-50/20"
                            >
                        </div>
                    @endforeach

                    {{-- CUSTOM DROPDOWN FOR SPECIALIZATION --}}
                    <div class="transition-all duration-300 transform hover:scale-[1.015] animate-fadeInUp">
                        <label class="block mb-1 text-gray-600 font-medium flex items-center gap-1">
                            <i data-lucide="settings" class="w-4 h-4 text-indigo-500"></i>
                            Spesialisasi
                        </label>
                        
                        <select 
                            name="specialization" 
                            required 
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                   transition-all duration-300 bg-white hover:bg-indigo-50/20 text-gray-700"
                        >
                            <option value="" disabled selected>-- Pilih Spesialisasi --</option>
                            <option value="Mesin" {{ old('specialization') == 'Mesin' ? 'selected' : '' }}>Mekanik Mesin</option>
                            <option value="Kelistrikan" {{ old('specialization') == 'Kelistrikan' ? 'selected' : '' }}>Mekanik Kelistrikan</option>
                            <option value="Kaki-kaki" {{ old('specialization') == 'Kaki-kaki' ? 'selected' : '' }}>Mekanik Kaki-kaki</option>
                            <option value="AC Mobil" {{ old('specialization') == 'AC Mobil' ? 'selected' : '' }}>Spesialis AC</option>
                            <option value="Body Repair" {{ old('specialization') == 'Body Repair' ? 'selected' : '' }}>Body Repair</option>
                            <option value="Tune Up" {{ old('specialization') == 'Tune Up' ? 'selected' : '' }}>Service Berkala (Tune Up)</option>
                        </select>
                    </div>

                    {{-- BUTTON AREA --}}
                    <div class="flex items-center gap-4 pt-6">
                        <button type="submit"
                            class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-xl shadow-md font-semibold
                                    hover:from-indigo-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-[1.03] active:scale-95 transition-all duration-200 flex items-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i> Simpan
                        </button>

                        <a href="{{ route('admin.mechanics.index') }}"
                           class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition font-semibold flex items-center gap-2">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- ANIMATIONS --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pop {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out; }
        .animate-fadeInUp { animation: fadeInUp 0.6s ease-out; }
        .animate-slideDown { animation: slideDown 0.6s ease-out; }
        .animate-pop { animation: pop 0.3s ease-out; }
        
        /* Menghilangkan panah pada input type number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-app-layout>