<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-6 h-6 text-blue-600"></i>
            Buat Booking Baru
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-6">

                {{-- Notifikasi sukses --}}
                @if (session('success'))
                    <div class="mb-4 flex items-center gap-2 p-3 bg-green-100 text-green-700 rounded-xl">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Form Booking --}}
                <form action="{{ route('customer.booking.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Pilih Layanan --}}
                    <div>
                        <label class="block font-semibold mb-1">
                            <i data-lucide="tool" class="w-4 h-4 inline"></i> Layanan
                        </label>
                        <select name="service_id"
                                class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                                required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - Rp{{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Kendaraan --}}
                    <div>
                        <label class="block font-semibold mb-1">
                            <i data-lucide="truck" class="w-4 h-4 inline"></i> Kendaraan
                        </label>
                        <select name="vehicle_id"
                                class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                                required>
                            <option value="">-- Pilih Kendaraan --</option>
                            @forelse($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                                </option>
                            @empty
                                <option value="">❌ Belum ada kendaraan</option>
                            @endforelse
                        </select>
                        @error('vehicle_id')
                            <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Mekanik (Opsional) --}}
                    <div>
                        <label class="block font-semibold mb-1">
                            <i data-lucide="user-cog" class="w-4 h-4 inline"></i> Pilih Mekanik (Opsional)
                        </label>
                        <select name="mechanic_id"
                                class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                            <option value="">-- Biarkan kosong jika ingin sistem memilih --</option>
                            @foreach($mechanics as $m)
                                <option value="{{ $m->id }}" {{ old('mechanic_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }} ({{ $m->specialization ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                        @error('mechanic_id')
                            <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Booking --}}
                    <div>
                        <label class="block font-semibold mb-1">
                            <i data-lucide="calendar" class="w-4 h-4 inline"></i> Tanggal Booking
                        </label>
                        <input type="date" name="booking_date" value="{{ old('booking_date') }}"
                               class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                               required>
                        @error('booking_date')
                            <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit & Kembali --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('customer.booking.index') }}"
                           class="flex items-center gap-1 px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl shadow text-sm font-medium transition">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                        </a>

                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition flex items-center gap-1">
                            <i data-lucide="send" class="w-4 h-4"></i> Kirim Booking
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>