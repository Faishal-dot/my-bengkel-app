<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full animate-fadeIn">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="wrench" class="w-6 h-6 text-indigo-600"></i>
                Manajemen Layanan
            </h2>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.services.index') }}" class="relative">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Cari layanan..."
                        value="{{ request('q') }}"
                        class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm 
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none 
                               transition-all duration-300 hover:shadow-md"
                    >
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </form>

                <a href="{{ route('admin.services.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 
                          text-white px-4 py-2 rounded-xl shadow 
                          hover:from-indigo-700 hover:to-purple-700 
                          transform hover:scale-[1.03] active:scale-95 
                          transition-all duration-200 text-sm font-medium">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 animate-fadeIn">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2 shadow-sm animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(request('q'))
                <div class="mb-6 p-4 bg-blue-100 text-blue-700 border border-blue-200 rounded-xl flex items-center gap-2 shadow-sm animate-fadeIn">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    Menampilkan hasil untuk:
                    <span class="font-semibold">"{{ request('q') }}"</span>

                    <a href="{{ route('admin.services.index') }}" 
                       class="ml-auto text-sm text-blue-600 hover:underline">
                        Reset
                    </a>
                </div>
            @endif

            @php
                // Logika pengurutan: Bundle di atas, Sisanya di bawah
                $bundleServices = $services->filter(fn($s) => $s->products && $s->products->count() > 0);
                $regularServices = $services->filter(fn($s) => !($s->products && $s->products->count() > 0));
                $sortedServices = $bundleServices->merge($regularServices);
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($sortedServices as $service)
                    <div class="bg-white shadow-md hover:shadow-xl rounded-2xl p-5 
                                 transition-all duration-300 animate-card hover:scale-[1.02] relative">

                        <div class="absolute -top-2 -right-2 z-10 flex items-center gap-2">
                            {{-- Paket Bundle ditaruh di sebelah kiri diskon --}}
                            @if($service->products && $service->products->count() > 0)
                                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md flex items-center gap-1">
                                    <i data-lucide="package" class="w-3 h-3"></i>
                                    Paket Bundle
                                </span>
                            @endif

                            {{-- Diskon ditaruh paling kanan dengan teks 'Diskon' --}}
                            @if($service->discount_price)
                            <span class="inline-flex items-center bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-md">
                                <i data-lucide="badge-percent" class="w-3 h-3 mr-1"></i>
                                DISKON -{{ round((($service->price - $service->discount_price) / $service->price) * 100) }}%
                            </span>
                        @endif
                        </div>
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-semibold text-lg text-gray-800 pr-4">
                                {{ $service->name }}
                            </h3>

                            {{-- Bagian Harga --}}
                            <div class="flex flex-col items-end min-w-fit"> {{-- Tambahkan min-w-fit --}}
                                @if($service->discount_price)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 whitespace-nowrap"> {{-- Tambahkan whitespace-nowrap --}}
                                        Rp {{ number_format($service->discount_price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 line-through mt-1 whitespace-nowrap">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 whitespace-nowrap"> {{-- Tambahkan whitespace-nowrap --}}
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ $service->description ?? 'Tidak ada deskripsi.' }}
                        </p>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.services.edit', $service) }}"
                               class="p-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow transition">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>

                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 shadow transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500 italic animate-fadeIn">
                        @if(request('q'))
                            Tidak ditemukan hasil untuk "<strong>{{ request('q') }}</strong>"
                        @else
                            Belum ada layanan.
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $services->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-card {
            animation: cardIn 0.5s ease-out;
        }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>