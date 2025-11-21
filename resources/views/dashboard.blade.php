{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="layout-dashboard" class="w-7 h-7 text-blue-600"></i>
            Dashboard Customer
        </h2>
    </x-slot>

    <!-- ANIMASI GLOBAL -->
    <style>
        .fade-slide {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.7s ease-out forwards;
        }
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .fade {
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn { to { opacity: 1; } }
        .fade-row {
            opacity: 0;
            animation: fadeRow 0.5s ease-out forwards;
        }
        @keyframes fadeRow { to { opacity: 1; } }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- ===========================
                    CARD WELCOME
            ============================ -->
            <div class="bg-white shadow-lg rounded-2xl p-8 fade-slide" style="animation-delay: .1s">
                <h3 class="text-2xl font-bold text-blue-600 mb-2">
                    Selamat Datang 🎉
                </h3>
                <p class="text-gray-700 text-lg">
                    Anda login sebagai 
                    <span class="font-semibold text-green-600">{{ auth()->user()->name }}</span>
                    <span class="text-gray-500">
                        ({{ auth()->user()->role ?: 'customer' }})
                    </span>
                </p>
            </div>


            <!-- ===========================
                    STATISTIK CUSTOMER
            ============================ -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

                <!-- Total Booking -->
                <a href="{{ route('customer.booking.index') }}"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4 
                    transition-all transform hover:scale-[1.05] hover:shadow-xl hover:brightness-110 duration-300 fade-slide"
                    style="animation-delay: .2s">
                    <i data-lucide="calendar" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Booking</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalBookings ?? 0 }}</p>
                    </div>
                </a>

                <!-- Total Orders -->
                <a href="{{ route('customer.orders.index') }}"
                    class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4 
                    transition-all transform hover:scale-[1.05] hover:shadow-xl hover:brightness-110 duration-300 fade-slide"
                    style="animation-delay: .3s">
                    <i data-lucide="shopping-cart" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Orders</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalOrders ?? 0 }}</p>
                    </div>
                </a>

                <!-- Total Layanan -->
                <a href="{{ route('customer.services') }}"
                    class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4 
                    transition-all transform hover:scale-[1.05] hover:shadow-xl hover:brightness-110 duration-300 fade-slide"
                    style="animation-delay: .4s">
                    <i data-lucide="wrench" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Layanan</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalServices ?? 0 }}</p>
                    </div>
                </a>
            </div>


            <!-- ===========================
                    AKTIVITAS TERBARU
            ============================ -->
            <div class="bg-white p-6 rounded-2xl shadow fade-slide" style="animation-delay: .5s">

                <!-- JUDUL DICENTER -->
                <h4 class="text-lg font-semibold mb-4 flex items-start justify-start gap-2 text-start">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-600"></i> 
                    Aktivitas Terbaru
                </h4>

                <table class="w-full text-sm border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border text-center">Tanggal</th>
                            <th class="p-2 border text-center">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities ?? [] as $index => $activity)
                            <tr class="hover:bg-gray-50 fade-row"
                                style="animation-delay: {{ $index * 0.15 }}s">
                                <td class="p-2 border text-center">
                                    {{ \Carbon\Carbon::parse($activity['created_at'])->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-2 border text-center">{{ $activity['description'] }}</td>
                            </tr>
                        @empty
                            <tr class="fade-row">
                                <td colspan="2" class="p-4 border text-center text-gray-500">
                                    Belum ada aktivitas terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>

</x-app-layout>