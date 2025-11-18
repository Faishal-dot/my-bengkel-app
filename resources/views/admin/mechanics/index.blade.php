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
                        class="pl-10 pr-4 py-2 text-sm border rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500"
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

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($mechanics as $mechanic)
                    <div class="bg-white shadow-lg hover:shadow-2xl rounded-2xl p-6 transition transform hover:-translate-y-1 animate-card">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-semibold text-lg text-gray-800">
                                {{ $mechanic->name }}
                            </h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                {{ $mechanic->specialization ?? 'Umum' }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600">
                            <strong>Email:</strong> {{ $mechanic->user->email ?? '-' }}
                        </p>

                        <p class="text-sm text-gray-600 mb-4 mt-1">
                            <strong>Telp:</strong> {{ $mechanic->phone ?? '-' }}
                        </p>

                        <div class="flex justify-end gap-3 mt-3">
                            <a href="{{ route('admin.mechanics.edit', $mechanic->id) }}"
                               class="p-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow transition"
                               title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>

                            <form action="{{ route('admin.mechanics.destroy', $mechanic->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus mekanik ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 shadow transition"
                                    title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500 italic animate-fadeIn">
                        Belum ada mekanik.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 animate-fadeIn">
                {{ $mechanics->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Animasi -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes cardPop {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-card {
            animation: cardPop 0.5s ease-out;
        }
    </style>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>