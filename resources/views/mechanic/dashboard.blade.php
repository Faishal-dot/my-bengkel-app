<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="wrench" class="w-7 h-7 text-blue-600"></i>
            Dashboard Mekanik
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Card Welcome -->
            <div class="bg-white shadow-lg rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-blue-600 mb-2">
                    Selamat Datang
                </h3>
                <p class="text-gray-700 text-lg">
                    Anda login sebagai 
                    <span class="font-semibold text-green-600">{{ auth()->user()->name }}</span>
                    <span class="text-gray-500">(Mekanik)</span>
                </p>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

                <!-- Total Pekerjaan -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-md 
                            flex items-center gap-4 transition-all transform hover:scale-[1.05] 
                            hover:shadow-xl hover:brightness-110 duration-300">
                    <i data-lucide="clipboard-list" class="w-12 h-12 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Pekerjaan</h4>
                        <p class="text-4xl font-bold mt-1">{{ $total_jobs ?? 0 }}</p>
                    </div>
                </div>

                <!-- Selesai Hari Ini -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-md 
                            flex items-center gap-4 transition-all transform hover:scale-[1.05] 
                            hover:shadow-xl hover:brightness-110 duration-300">
                    <i data-lucide="check-circle" class="w-12 h-12 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Selesai Hari Ini</h4>
                        <p class="text-4xl font-bold mt-1">{{ $jobs_done_today ?? 0 }}</p>
                    </div>
                </div>

                <!-- Dalam Proses -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-2xl shadow-md 
                            flex items-center gap-4 transition-all transform hover:scale-[1.05] 
                            hover:shadow-xl hover:brightness-110 duration-300">
                    <i data-lucide="loader" class="w-12 h-12 opacity-90 animate-spin-slow"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Dalam Proses</h4>
                        <p class="text-4xl font-bold mt-1">{{ $jobs_processing ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Akses Cepat -->
            <div class="bg-white p-6 rounded-2xl shadow">
                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="rocket" class="w-5 h-5 text-blue-600"></i> 
                    Akses Cepat
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <!-- Pekerjaan -->
                    <a href="{{ route('mechanic.jobs.index') }}"
                        class="bg-blue-50 p-6 border border-blue-200 rounded-xl shadow-sm group hover:bg-blue-100 
                              transition-all hover:shadow-lg text-center">
                        <i data-lucide="wrench" class="w-10 h-10 text-blue-600 group-hover:scale-110 transition"></i>
                        <p class="font-semibold text-blue-700 mt-3">Pekerjaan</p>
                        <p class="text-sm text-gray-600">Daftar pekerjaan mekanik</p>
                    </a>

                    <!-- Booking Masuk -->
                    <a href="{{ route('mechanic.jobs.index') }}"
                        class="bg-green-50 p-6 border border-green-200 rounded-xl shadow-sm group hover:bg-green-100 
                              transition-all hover:shadow-lg text-center">
                        <i data-lucide="calendar-check" class="w-10 h-10 text-green-600 group-hover:scale-110 transition"></i>
                        <p class="font-semibold text-green-700 mt-3">Booking Masuk</p>
                        <p class="text-sm text-gray-600">Jadwal servis hari ini</p>
                    </a>

                    <!-- Pesanan Sparepart -->
                    <a href="{{ route('mechanic.jobs.index') }}"
                        class="bg-yellow-50 p-6 border border-yellow-200 rounded-xl shadow-sm group hover:bg-yellow-100 
                              transition-all hover:shadow-lg text-center">
                        <i data-lucide="shopping-cart" class="w-10 h-10 text-yellow-600 group-hover:scale-110 transition"></i>
                        <p class="font-semibold text-yellow-600 mt-3">Pesanan Sparepart</p>
                        <p class="text-sm text-gray-600">Item yang perlu disiapkan</p>
                    </a>

                    <!-- Riwayat Servis -->
                    <a href="{{ route('mechanic.jobs.index') }}"
                        class="bg-purple-50 p-6 border border-purple-200 rounded-xl shadow-sm group hover:bg-purple-100 
                              transition-all hover:shadow-lg text-center">
                        <i data-lucide="history" class="w-10 h-10 text-purple-600 group-hover:scale-110 transition"></i>
                        <p class="font-semibold text-purple-700 mt-3">Riwayat Servis</p>
                        <p class="text-sm text-gray-600">Catatan pekerjaan selesai</p>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>