<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2 fade-slide">
            <i data-lucide="wrench" class="w-6 h-6 text-blue-600"></i>
            Pekerjaan Saya
        </h2>
    </x-slot>

    {{-- STYLE ANIMASI --}}
    <style>
        .fade-slide { opacity:0; transform:translateY(20px); animation: slideUp .6s ease-out forwards; }
        @keyframes slideUp { to { opacity:1; transform:translateY(0);} }

        .fade-row { opacity:0; animation: fadeRow .6s ease-out forwards; }
        @keyframes fadeRow { to { opacity:1; } }

        /* Modal Animation */
        #complaintModal { transition: opacity 0.3s ease, visibility 0.3s ease; }
        #complaintModal.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        #complaintModal:not(.hidden) { opacity: 1; visibility: visible; pointer-events: auto; }
        #complaintModal:not(.hidden) #modalContent { animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    </style>

    <div class="py-10 bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen fade-slide" style="animation-delay:.15s">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Container Putih (White Card) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg fade-slide" style="animation-delay:.25s">

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex items-center gap-2 fade-slide" style="animation-delay:.35s">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Header Section dalam Card --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 fade-slide" style="animation-delay:.45s">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Antrian Servis</h3>
                        <p class="text-gray-600 text-sm">List kendaraan yang perlu dikerjakan hari ini</p>
                    </div>
                    
                    {{-- Counter Badge --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-3 py-1 bg-blue-100 text-blue-700 border border-blue-200 rounded-full shadow-sm">
                            Total: {{ $bookings->count() }}
                        </span>
                    </div>
                </div>

                {{-- Table Pekerjaan --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 fade-slide" style="animation-delay:.55s">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-blue-600 text-white uppercase text-xs">
                                <th class="px-4 py-3 border-r border-blue-500 text-center w-12">No</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Antrian</th> {{-- KOLOM BARU --}}
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Customer</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Kendaraan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-left">Layanan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Keluhan</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Tanggal</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center">Status</th>
                                <th class="px-4 py-3 border-r border-blue-500 text-center" style="min-width: 160px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bookings as $index => $job)
                                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition fade-row"
                                    style="animation-delay: {{ $index * 0.12 }}s">

                                    {{-- 1. NO --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center font-medium">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- 2. ANTRIAN (Lingkaran) --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($job->queue_number)
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-600 text-white font-bold shadow-md transform hover:scale-110 transition">
                                                {{ $job->queue_number }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- 3. CUSTOMER --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <div class="font-bold text-gray-800 flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shadow-sm border border-blue-200">
                                                {{ substr($job->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            {{ $job->user->name ?? 'Guest' }}
                                        </div>
                                    </td>

                                    {{-- 4. KENDARAAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        @if($job->vehicle)
                                            <div class="font-semibold text-gray-700">{{ $job->vehicle->plate_number }}</div>
                                            <div class="text-xs text-gray-500">{{ $job->vehicle->brand }} - {{ $job->vehicle->model }}</div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">-</span>
                                        @endif
                                    </td>

                                    {{-- 5. LAYANAN --}}
                                    <td class="px-4 py-3 border-r border-gray-200">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-700">{{ $job->service->name ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- 6. KELUHAN (Modal Trigger) --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($job->complaint)
                                            <button onclick="openModal('{{ $job->user->name }}', '{{ addslashes($job->complaint) }}')" 
                                                class="px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-xs rounded-lg font-bold border border-yellow-300 transition flex items-center justify-center gap-1 mx-auto shadow-sm">
                                                <i data-lucide="file-text" class="w-3 h-3"></i> Cek
                                            </button>
                                        @else
                                            <span class="text-gray-300 text-xs italic">-</span>
                                        @endif
                                    </td>

                                    {{-- 7. TANGGAL --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center whitespace-nowrap text-gray-600">
                                        {{ \Carbon\Carbon::parse($job->booking_date)->format('d-m-Y') }}
                                    </td>

                                    {{-- 8. STATUS --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        @if($job->status == 'disetujui')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Disetujui
                                            </span>
                                        @elseif($job->status == 'proses')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold border border-blue-200 inline-flex items-center gap-1">
                                                <i data-lucide="loader" class="w-3 h-3 animate-spin"></i> Proses
                                            </span>
                                        @elseif($job->status == 'selesai')
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-bold border border-indigo-200 inline-flex items-center gap-1">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i> Selesai
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold border border-gray-200">
                                                {{ ucfirst($job->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 9. AKSI (Chat & Status) --}}
                                    <td class="px-4 py-3 border-r border-gray-200 text-center">
                                        <div class="flex flex-col gap-2">
                                            
                                            {{-- Tombol Chat (Dipindahkan ke sini) --}}
                                            @if($job->user)
                                                <a href="{{ route('chat.show', $job->id) }}" 
                                                   class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors duration-200">
                                                    <i data-lucide="message-circle" class="w-3 h-3"></i>
                                                    Chat Customer
                                                </a>
                                            @endif

                                            {{-- Form Update Status (Tanpa Emoji) --}}
                                            <form action="{{ route('mechanic.jobs.update', $job->id) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                
                                                <div class="relative">
                                                    <select name="status" onchange="this.form.submit()" 
                                                        class="appearance-none w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer hover:bg-gray-50 transition font-medium text-gray-700 shadow-sm pr-8">
                                                        
                                                        @if($job->status == 'disetujui')
                                                            <option value="disetujui" selected disabled>Pilih Aksi</option>
                                                            <option value="proses">Mulai Servis</option>
                                                        
                                                        @elseif($job->status == 'proses')
                                                            <option value="proses" selected disabled>Sedang Servis</option>
                                                            <option value="selesai">Selesaikan</option>
                                                        
                                                        @elseif($job->status == 'selesai')
                                                            <option value="selesai" selected disabled>Selesai</option>
                                                        @endif
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr class="fade-row" style="animation-delay:.3s">
                                    <td colspan="9" class="text-center py-8 text-gray-500 italic bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mb-2"></i>
                                            <p>Belum ada pekerjaan yang masuk.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL POPUP (Design Modern) --}}
    <div id="complaintModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm p-4">
        
        <div id="modalContent" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all">
            
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold flex items-center gap-2 text-lg">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    Catatan Pelanggan
                </h3>
                <button onclick="closeModal()" class="text-blue-100 hover:text-white hover:bg-blue-700 rounded-full p-1 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Pelanggan</div>
                <div class="text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100" id="modalCustomerName">-</div>
                
                <div class="mb-2 text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3 text-orange-500"></i> Isi Keluhan
                </div>
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-xl shadow-sm">
                    <p class="text-gray-700 italic text-sm leading-relaxed" id="modalText">-</p>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function openModal(name, text) {
            document.getElementById('modalCustomerName').innerText = name;
            document.getElementById('modalText').innerText = text;
            const modal = document.getElementById('complaintModal');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('complaintModal');
            modal.classList.add('hidden');
        }

        document.getElementById('complaintModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</x-app-layout>