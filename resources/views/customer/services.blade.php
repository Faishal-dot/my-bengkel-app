<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full animate-fadeSlide">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="wrench" class="w-7 h-7 text-indigo-600"></i>
                Daftar Layanan Bengkel
            </h2>

            <!-- Search -->
            <form method="GET" action="{{ route('customer.services') }}" class="relative">
                <input type="text" name="q" placeholder="Cari layanan..."
                       class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       value="{{ request('q') }}">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hasil pencarian -->
            @if(request('q'))
                <div class="mb-6 p-4 bg-blue-100 text-blue-700 border border-blue-200 rounded-xl flex items-center gap-2 shadow-sm animate-fadeSlide">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    Menampilkan hasil untuk: 
                    <span class="font-semibold">"{{ request('q') }}"</span>
                    <a href="{{ route('customer.services') }}" 
                       class="ml-auto text-sm text-blue-600 hover:underline">
                        Reset
                    </a>
                </div>
            @endif

            @if($services->count())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white shadow-md rounded-2xl p-5 transition transform 
                                    hover:-translate-y-2 hover:shadow-2xl animate-cardFade flex flex-col">
                            
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $service->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 flex-grow">
                                {{ $service->description ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <a href="{{ route('customer.booking.create') }}" 
                               class="mt-auto inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium text-center transition">
                                <i data-lucide="car" class="w-4 h-4"></i>
                                Booking Layanan
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 animate-fadeSlide">
                    {{ $services->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-10 text-gray-500 flex flex-col items-center animate-fadeSlide">
                    <i data-lucide="frown" class="w-12 h-12 text-gray-400 mb-3"></i>
                    <p>Belum ada layanan yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Lucide --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    {{-- ANIMASI TAILWIND CUSTOM --}}
    <style>
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes cardFade {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }

        .animate-fadeSlide {
            animation: fadeSlide 0.6s ease forwards;
        }

        .animate-cardFade {
            animation: cardFade 0.5s ease forwards;
        }
    </style>
</x-app-layout>