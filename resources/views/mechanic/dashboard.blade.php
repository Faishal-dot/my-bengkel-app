<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="wrench" class="w-6 h-6 text-blue-600"></i>
            Dashboard Mekanik
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Selamat datang, <span class="text-blue-600">{{ Auth::user()->name }}</span> 👋
                </h3>

                <p class="text-gray-600">
                    Ini adalah halaman dashboard khusus untuk mekanik.  
                    Di sini kamu bisa melihat jadwal servis, status booking, dan pekerjaan yang sedang berlangsung.
                </p>

                <div class="mt-6 border-t pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <p class="text-sm text-gray-500">Total Pekerjaan</p>
                            <p class="text-2xl font-bold text-blue-700">0</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                            <p class="text-sm text-gray-500">Selesai Hari Ini</p>
                            <p class="text-2xl font-bold text-green-700">0</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                            <p class="text-sm text-gray-500">Dalam Proses</p>
                            <p class="text-2xl font-bold text-yellow-700">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
