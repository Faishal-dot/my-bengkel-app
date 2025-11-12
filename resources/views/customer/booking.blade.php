<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i data-lucide="calendar-plus" class="w-7 h-7 text-blue-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Buat Booking Baru</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-2 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8 
                        transition-all duration-500 ease-out 
                        animate-[fadeIn_0.6s_ease-out,slideUp_0.6s_ease-out]">

                <form id="bookingForm" action="{{ route('customer.booking.store') }}" method="POST" class="space-y-8">
                    @csrf

                    {{-- Pilih Layanan --}}
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="wrench" class="w-4 h-4 text-blue-500"></i>
                            Layanan
                        </label>
                        <select name="service_id" required
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                   transition-all duration-300 ease-in-out
                                   hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"
                                    data-service-name="{{ $service->name }}"
                                    data-service-price="{{ number_format($service->price, 0, ',', '.') }}"
                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - Rp{{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Kendaraan --}}
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="truck" class="w-4 h-4 text-blue-500"></i>
                            Kendaraan
                        </label>
                        <select name="vehicle_id" required
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                   transition-all duration-300 ease-in-out
                                   hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30">
                            <option value="">Pilih Kendaraan</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}"
                                    data-vehicle="{{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Mekanik --}}
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="user-cog" class="w-4 h-4 text-blue-500"></i>
                            Mekanik (Opsional)
                        </label>
                        <select name="mechanic_id"
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                   transition-all duration-300 ease-in-out
                                   hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30">
                            <option value="">Pilih Mekanik</option>
                            @foreach($mechanics as $m)
                                <option value="{{ $m->id }}" data-mechanic="{{ $m->name }}"
                                    {{ old('mechanic_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }} ({{ $m->specialization ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Booking --}}
                    <div class="relative transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                            Tanggal Booking
                        </label>
                        <input type="date" name="booking_date" value="{{ old('booking_date') }}" required
                            class="w-full border-gray-300 rounded-lg px-4 py-3 shadow-sm 
                                   focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                                   transition-all duration-300 ease-in-out
                                   hover:border-blue-300 hover:shadow-md hover:bg-blue-50/30">
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('customer.booking.index') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-300 text-gray-600 
                                  hover:bg-gray-100 transition font-medium transform hover:scale-105 duration-300">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Batal
                        </a>

                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 
                                       rounded-lg shadow-md hover:from-blue-600 hover:to-indigo-700 
                                       transform hover:scale-105 active:scale-95 
                                       transition-all duration-300 font-semibold">
                            <i data-lucide="send" class="w-5 h-5"></i> Kirim Booking
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- STYLE ANIMASI --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(15px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>

    {{-- SCRIPT ICON & ALERT --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        lucide.createIcons();

        // konfirmasi sebelum kirim booking
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const service = e.target.querySelector('select[name="service_id"]');
            const vehicle = e.target.querySelector('select[name="vehicle_id"]');
            const mechanic = e.target.querySelector('select[name="mechanic_id"]');
            const date = e.target.querySelector('input[name="booking_date"]').value;

            const selectedService = service.selectedOptions[0];
            const selectedVehicle = vehicle.selectedOptions[0];
            const selectedMechanic = mechanic.selectedOptions[0];

            const serviceName = selectedService.dataset.serviceName || '-';
            const servicePrice = selectedService.dataset.servicePrice || '-';
            const vehicleInfo = selectedVehicle.dataset.vehicle || '-';
            const mechanicName = selectedMechanic.dataset.mechanic || 'Tidak dipilih';

            Swal.fire({
                title: 'Konfirmasi Booking',
                html: `
                    <div class='text-left space-y-2'>
                        <p><strong>Layanan:</strong> ${serviceName} (Rp${servicePrice})</p>
                        <p><strong>Kendaraan:</strong> ${vehicleInfo}</p>
                        <p><strong>Mekanik:</strong> ${mechanicName}</p>
                        <p><strong>Tanggal Booking:</strong> ${date}</p>
                    </div>
                    <p class='mt-4 text-gray-600'>Pastikan semua data sudah benar sebelum melanjutkan.</p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Periksa lagi',
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    </script>
</x-app-layout>