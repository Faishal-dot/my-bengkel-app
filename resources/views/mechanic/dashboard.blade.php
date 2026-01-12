<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wrench" class="w-7 h-7 text-blue-600"></i>
            Dashboard Mekanik
        </h2>
    </x-slot>

    <style>
        /* Fade + slide */
        .fade-slide {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.7s ease-out forwards;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fade-in biasa */
        .fade {
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        /* Baris tabel muncul satu-satu */
        .fade-row {
            opacity: 0;
            animation: fadeRow 0.5s ease-out forwards;
        }

        @keyframes fadeRow {
            to { opacity: 1; }
        }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white shadow-lg rounded-2xl p-8 fade-slide" style="animation-delay: .1s">
                <h3 class="text-2xl font-bold text-blue-600 mb-2">
                    Selamat Datang
                </h3>
                <p class="text-gray-700 text-lg">
                    Anda login sebagai 
                    <span class="font-semibold text-green-600">{{ auth()->user()->name }}</span> 
                    <span class="text-gray-500">(Mekanik)</span>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4 
                    transition-all transform hover:scale-[1.05] hover:shadow-xl hover:brightness-110 duration-300 fade-slide"
                    style="animation-delay: .2s">
                    <i data-lucide="clipboard-list" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Total Pekerjaan</h4>
                        <p class="text-3xl font-bold mt-1">{{ $total_jobs ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4 
                    transition-all transform hover:scale-[1.05] hover:shadow-xl hover:brightness-110 duration-300 fade-slide"
                    style="animation-delay: .3s">
                    <i data-lucide="loader" class="w-10 h-10 opacity-90 animate-spin"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Sedang Proses</h4>
                        <p class="text-3xl font-bold mt-1">{{ $jobs_processing ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-md flex items-center gap-4 
                    transition-all transform hover:scale-[1.05] hover:shadow-xl hover:brightness-110 duration-300 fade-slide"
                    style="animation-delay: .4s">
                    <i data-lucide="check-circle-2" class="w-10 h-10 opacity-90"></i>
                    <div>
                        <h4 class="text-lg font-semibold">Selesai Hari Ini</h4>
                        <p class="text-3xl font-bold mt-1">{{ $jobs_done_today ?? 0 }}</p>
                    </div>
                </div>

            </div>

            <div class="bg-white p-6 rounded-2xl shadow fade-slide" style="animation-delay: .6s">
                <h4 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-blue-600"></i> Panel Kontrol Pekerjaan
                </h4>
                
                <div class="flex flex-col md:flex-row items-center justify-between bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div class="mb-4 md:mb-0">
                        <h5 class="text-xl font-bold text-gray-800">Siap Mengerjakan Servis?</h5>
                        <p class="text-gray-500 mt-1">Anda memiliki <span class="font-bold text-blue-600">{{ $total_jobs ?? 0 }}</span> antrian pekerjaan aktif.</p>
                    </div>

                    <a href="{{ route('mechanic.jobs.index') }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105">
                        <i data-lucide="list-todo" class="w-5 h-5"></i>
                        Buka Daftar Pekerjaan
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