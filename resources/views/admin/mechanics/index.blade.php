<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full animate-fadeIn">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="users" class="w-6 h-6 text-indigo-600"></i>
                Manajemen Mekanik
            </h2>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.mechanics.index') }}" class="relative">
                    <input 
                        type="text"
                        name="q"
                        placeholder="Cari mekanik..."
                        value="{{ request('q') }}"
                        class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 border-gray-300"
                    >
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </form>

                <a href="{{ route('admin.mechanics.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition text-sm font-medium animate-fadeIn">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl flex items-center gap-2 shadow-sm animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8"> 
                @forelse($mechanics as $mechanic)
                    <div class="bg-white shadow-lg hover:shadow-2xl rounded-2xl p-5 transition transform hover:-translate-y-1 animate-card border border-gray-100"> 
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-md">
                                <span class="text-lg font-bold">{{ strtoupper(substr($mechanic->name, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-xl text-gray-800 truncate">
                                    {{ $mechanic->name }}
                                </h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                                    {{ $mechanic->specialization ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        {{-- Jarak antar baris dirapatkan dengan space-y-1 dan padding pt-3 --}}
                        <div class="space-y-1 border-t border-gray-50 pt-3">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i data-lucide="id-card" class="w-4 h-4 text-gray-400"></i>
                                <span><strong>KTP:</strong> {{ $mechanic->ktp ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                <span class="truncate"><strong>Email:</strong> {{ $mechanic->user->email ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                <span><strong>Telp:</strong> {{ $mechanic->phone ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600 flex items-start gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-1"></i>
                                <span class="line-clamp-1"><strong>Alamat:</strong> {{ $mechanic->address ?? '-' }}</span>
                            </p>
                        </div>

                        {{-- Margin atas tombol dikurangi (mt-4) agar lebih naik --}}
                        <div class="flex justify-end gap-3 mt-4 pt-3 border-t border-gray-50">
                            <a href="{{ route('admin.mechanics.edit', $mechanic->id) }}"
                               class="p-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 shadow-sm transition-all"
                               title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>

                            <form action="{{ route('admin.mechanics.destroy', $mechanic->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus mekanik ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-sm transition-all"
                                    title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 text-gray-500 italic animate-fadeIn bg-white rounded-2xl shadow-inner border-2 border-dashed border-gray-200">
                        <i data-lucide="user-x" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        Belum ada mekanik yang terdaftar.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 animate-fadeIn">
                {{ $mechanics->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out; }

        @keyframes cardPop {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-card { animation: cardPop 0.5s ease-out; }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>