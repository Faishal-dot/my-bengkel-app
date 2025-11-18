<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
            <i data-lucide="wrench" class="w-6 h-6 text-blue-600"></i>
            Pekerjaan Saya
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-2xl shadow-lg">

                {{-- Alert success --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Heading --}}
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Pekerjaan</h3>
                    <p class="text-gray-600 text-sm">List pekerjaan yang sedang menjadi tanggung jawab Anda</p>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border">No</th>
                                <th class="px-4 py-3 border text-left">Customer</th>
                                <th class="px-4 py-3 border text-left">Kendaraan</th>
                                <th class="px-4 py-3 border text-left">Layanan</th>
                                <th class="px-4 py-3 border text-center">Status</th>
                                <th class="px-4 py-3 border text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($jobs as $index => $job)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition">
                                    <td class="px-4 py-3 border text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border">{{ $job->customer->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border">
                                        {{ $job->vehicle->brand ?? '-' }} -
                                        {{ $job->vehicle->model ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 border">
                                        {{ $job->service->name ?? '-' }}
                                    </td>

                                    {{-- Badge Status --}}
                                    <td class="px-4 py-3 border text-center">
                                        @if($job->status == 'selesai')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i> Selesai
                                            </span>
                                        @elseif($job->status == 'proses')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="loader" class="w-4 h-4"></i> Proses
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                                <i data-lucide="clock" class="w-4 h-4"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi (Update Status) --}}
                                    <td class="px-4 py-3 border text-center">
                                        <form action="{{ route('mechanic.jobs.updateStatus', $job) }}" 
                                              method="POST" class="inline-block">
                                            @csrf

                                            <select name="status"
                                                    onchange="this.form.submit()"
                                                    class="border border-gray-300 rounded-lg px-2 py-1 pr-8 text-xs bg-white focus:ring focus:ring-blue-200 focus:border-blue-400">
                                                <option value="pending"  {{ $job->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="proses"   {{ $job->status == 'proses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="selesai"  {{ $job->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500 italic">
                                        Belum ada pekerjaan yang diterima
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>