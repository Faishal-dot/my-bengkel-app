<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="wrench" class="w-6 h-6 text-indigo-600"></i>
                Manajemen Layanan
            </h2>
            <div class="flex items-center gap-3">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.services.index') }}" class="relative">
                    <input type="text" name="q" placeholder="Cari layanan..."
                           class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </form>

                <!-- Tambah -->
                <a href="{{ route('admin.services.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition text-sm font-medium">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Pesan sukses -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2 shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Grid Layanan -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                    <div class="bg-white shadow-md hover:shadow-xl rounded-2xl p-5 transition">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $service->name }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ $service->description ?? 'Tidak ada deskripsi.' }}
                        </p>

                        <div class="flex justify-end gap-2">
                            <!-- Edit -->
                            <a href="{{ route('admin.services.edit', $service) }}"
                               class="p-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow"
                               title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>

                            <!-- Hapus -->
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 shadow"
                                        title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500 italic">
                        Belum ada layanan.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-app-layout>