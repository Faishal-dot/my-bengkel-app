{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 animate-fadeDown">
            <i data-lucide="layout-dashboard" class="w-7 h-7 text-blue-600"></i>
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Card Selamat Datang -->
            <div class="bg-white shadow-lg rounded-2xl p-8 animate-fadeUpSlow">
                <h3 class="text-2xl font-bold text-blue-600 mb-2">
                    Selamat Datang 🎉
                </h3>
                <p class="text-gray-700 text-lg">
                    Anda login sebagai 
                    <span class="font-semibold text-green-600">{{ auth()->user()->name }}</span>
                    <span class="text-gray-500">({{ auth()->user()->role ?: 'customer' }})</span>
                </p>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                <!-- Total Booking -->
                <div class="stat-card bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-md">
                    <div class="flex items-center gap-3">
                        <i data-lucide="calendar" class="w-10 h-10 opacity-90"></i>
                        <h4 class="text-lg font-semibold">Total Booking</h4>
                    </div>
                    <p class="text-3xl font-bold">{{ $totalBookings ?? 0 }}</p>
                </div>

                <!-- Total Orders -->
                <div class="stat-card bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-md">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-cart" class="w-10 h-10 opacity-90"></i>
                        <h4 class="text-lg font-semibold">Total Orders</h4>
                    </div>
                    <p class="text-3xl font-bold">{{ $totalOrders ?? 0 }}</p>
                </div>

                <!-- Total Layanan -->
                <div class="stat-card bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-md">
                    <div class="flex items-center gap-3">
                        <i data-lucide="wrench" class="w-10 h-10 opacity-90"></i>
                        <h4 class="text-lg font-semibold">Total Layanan</h4>
                    </div>
                    <p class="text-3xl font-bold">{{ $totalServices ?? 0 }}</p>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white p-6 rounded-2xl shadow animate-fadeUp">
                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-600"></i> Aktivitas Terbaru
                </h4>

                <table class="w-full text-sm border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border text-left">Tanggal</th>
                            <th class="p-2 border text-left">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities ?? [] as $activity)
                            <tr class="hover:bg-gray-50 transition animate-fadeUpSmall">
                                <td class="p-2 border">{{ \Carbon\Carbon::parse($activity['created_at'])->format('d/m/Y H:i') }}</td>
                                <td class="p-2 border">{{ $activity['description'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-4 border text-center text-gray-500 animate-fadeUpSmall">
                                    Belum ada aktivitas terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Animasi CSS -->
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUpSlow {
            from { opacity: 0; transform: translateY(25px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUpSmall {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeUp { animation: fadeUp .6s ease-out; }
        .animate-fadeUpSlow { animation: fadeUpSlow .8s ease-out; }
        .animate-fadeDown { animation: fadeDown .6s ease-out; }
        .animate-fadeUpSmall { animation: fadeUpSmall .4s ease-out; }

        /* Animasi card statistik */
        .stat-card {
            opacity: 0;
            animation: fadeUp .7s ease-out forwards;
            transform: translateY(20px);
        }
        .stat-card:nth-child(1) { animation-delay: .1s; }
        .stat-card:nth-child(2) { animation-delay: .25s; }
        .stat-card:nth-child(3) { animation-delay: .4s; }

        .stat-card:hover {
            transform: scale(1.03);
            transition: .3s;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
    </style>

    <!-- Lucide Icon -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>
</x-app-layout>