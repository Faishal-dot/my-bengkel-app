<x-app-layout> 
    <x-slot name="header">
        <div class="flex items-center gap-3 animate-fadeIn">
            <i data-lucide="calendar-plus" class="w-7 h-7 text-blue-600"></i>
            <h2 class="font-bold text-2xl text-gray-800">Buat Booking Baru</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-2 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm animate-fadeIn">
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-8 
                        transition-all duration-500 ease-out animate-form">

                <form id="bookingForm" action="{{ route('customer.booking.store') }}" method="POST" class="space-y-8">
                    @csrf

                    {{-- Layanan --}}
                    <div class="group transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="wrench" class="w-4 h-4 text-blue-500"></i>
                            Layanan
                        </label>
                        <select name="service_id" required
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"
                                    data-service-name="{{ $service->name }}"
                                    data-service-price="{{ number_format($service->price, 0, ',', '.') }}">
                                    {{ $service->name }} - Rp{{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kendaraan --}}
                    <div class="group transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="truck" class="w-4 h-4 text-blue-500"></i>
                            Kendaraan
                        </label>
                        <select name="vehicle_id" required
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                            <option value="">Pilih Kendaraan</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}"
                                    data-vehicle="{{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})">
                                    {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mekanik --}}
                    <div class="group transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="user-cog" class="w-4 h-4 text-blue-500"></i>
                            Mekanik (Opsional)
                        </label>
                        <select name="mechanic_id"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                            <option value="">Pilih Mekanik</option>
                            @foreach($mechanics as $m)
                                <option value="{{ $m->id }}" data-mechanic="{{ $m->name }}">
                                    {{ $m->name }} ({{ $m->specialization ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div class="group transition-all duration-300 hover:scale-[1.02]">
                        <label class="flex items-center gap-2 mb-2 text-gray-600 font-medium">
                            <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                            Tanggal Booking
                        </label>
                        <input type="date" name="booking_date" required
                            class="w-full border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('customer.booking.index') }}"
                           class="flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition font-medium">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Batal
                        </a>

                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl shadow-md hover:scale-105 active:scale-95 transition font-semibold">
                            <i data-lucide="send" class="w-5 h-5"></i> Kirim Booking
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ANIMASI --}}
    <style>
        @keyframes slideFade {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-form {
            animation: slideFade .6s ease-out;
        }
        .animate-fadeIn {
            animation: fadeIn .5s ease-out;
        }
    </style>

    {{-- SCRIPT PREMIUM --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;

    const serviceSelect = form.querySelector('select[name="service_id"]');
    const vehicleSelect = form.querySelector('select[name="vehicle_id"]');
    const mechanicSelect = form.querySelector('select[name="mechanic_id"]');
    const date = form.querySelector('input[name="booking_date"]').value;

    const s = serviceSelect.selectedOptions[0] ?? {};
    const v = vehicleSelect.selectedOptions[0] ?? {};
    const m = mechanicSelect.selectedOptions[0] ?? {};

    const serviceName = s.dataset?.serviceName ?? "-";
    const servicePrice = s.dataset?.servicePrice ?? "-";
    const vehicleInfo = v.dataset?.vehicle ?? "-";
    const mechanicName = m.dataset?.mechanic ?? "Tidak dipilih";

    Swal.fire({
        width: "420px",
        padding: "1.8rem",

        html: `
            <div style="max-width:350px; margin:0 auto;">

                <div style="
                    width: 80px; height: 80px;
                    display:flex; align-items:center; justify-content:center;
                    border-radius:50%;
                    background:#eef4ff;
                    border:1px solid #d0defd;
                    margin:0 auto 18px auto;
                ">
                    <i class="fa-solid fa-clipboard-check" style="color:#2563eb; font-size:32px;"></i>
                </div>

                <h2 style="text-align:center; font-size:18px; font-weight:600; margin-bottom:18px;">
                    Konfirmasi Booking
                </h2>

                <div style="font-size:15px; color:#374151; line-height:1.5;">
                    <p><b>Layanan:</b> ${serviceName} <span style="color:#2563eb">(Rp${servicePrice})</span></p>
                    <p><b>Kendaraan:</b> ${vehicleInfo}</p>
                    <p><b>Mekanik:</b> ${mechanicName}</p>
                    <p><b>Tanggal:</b> ${date}</p>
                </div>

                <p style="margin-top:12px; font-size:13px; color:#6b7280;">
                    Pastikan semua data telah benar sebelum dikirim.
                </p>

            </div>
        `,

        showCancelButton: true,
        confirmButtonText: "Kirim",
        cancelButtonText: "Batal",
        buttonsStyling: false,

        customClass: {
            popup: "rounded-2xl shadow-md border border-gray-200",
            confirmButton: "px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold mx-1",
            cancelButton: "px-6 py-2.5 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold mx-1",
        }
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

</x-app-layout>