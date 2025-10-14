<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-7 h-7 text-blue-600"></i>
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Card Welcome -->
            <div class="bg-white shadow-lg rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-blue-600 mb-2">
                    Selamat Datang 🎉
                </h3>
                <p class="text-gray-700 text-lg">
                    Anda login sebagai 
                    <span class="font-semibold text-green-600">{{ auth()->user()->name }}</span> 
                    <span class="text-gray-500">({{ auth()->user()->role }})</span>
                </p>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Produk -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4">
                    <i data-lucide="package" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Produk</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalProducts ?? 0 }}</p>
                    </div>
                </div>

                <!-- Layanan -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4">
                    <i data-lucide="wrench" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Layanan</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalServices ?? 0 }}</p>
                    </div>
                </div>

                <!-- Pesanan -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4">
                    <i data-lucide="shopping-cart" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Pesanan</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalOrders ?? 0 }}</p>
                    </div>
                </div>

                <!-- Customer -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4">
                    <i data-lucide="users" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Customer</h4>
                        <p class="text-3xl font-bold mt-1">{{ $totalCustomers ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white p-6 rounded-2xl shadow">
                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-600"></i> Aktivitas Terbaru
                </h4>
                <table class="w-full text-sm border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Tanggal</th>
                            <th class="p-2 border">Aktivitas</th>
                            <th class="p-2 border">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 border">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-2 border">Pesanan #{{ $activity->id }} dibuat</td>
                                <td class="p-2 border">{{ $activity->user->name ?? 'Unknown' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 border text-center text-gray-500">
                                    Belum ada aktivitas terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lucide Script -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>