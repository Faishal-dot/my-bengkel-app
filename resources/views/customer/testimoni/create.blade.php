<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3 animate-fadeIn">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <i data-lucide="message-circle-heart" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Kirim Testimoni</h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-12 bg-gradient-to-br from-slate-50 via-indigo-50 to-blue-50 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 relative z-10">
            
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8 md:p-10 animate-fadeSlide">
                
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Bagaimana Pengalaman Anda? 🌟
                    </h3>
                    <p class="text-gray-500">
                        Masukan Anda membantu kami menjadi lebih baik.
                    </p>
                </div>

                <form action="{{ route('customer.testimoni.store') }}" method="POST" class="space-y-8">
                    @csrf

                    {{-- BAGIAN RATING (MODIFIKASI: Tanpa Efek Hover Warna) --}}
                    {{-- Kita hanya butuh variabel 'rating', 'hoverRating' dihapus --}}
                    <div class="flex flex-col items-center justify-center space-y-4" x-data="{ rating: 0 }">
                        <label class="font-semibold text-gray-700 text-sm uppercase tracking-wider">Berikan Rating</label>
                        
                        <div class="flex items-center justify-center gap-2">
                            <template x-for="i in 5">
                                <button 
                                    type="button"
                                    @click="rating = i"
                                    class="transition-all duration-200 transform hover:scale-110 focus:outline-none p-1 group"
                                >
                                    {{-- Mouseover event dihapus agar warna tidak berubah saat digeser --}}
                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        width="40" 
                                        height="40" 
                                        viewBox="0 0 24 24" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        stroke-width="2" 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round"
                                        class="transition-colors duration-200"
                                        {{-- LOGIKA BARU: Warna hanya berubah jika rating >= i (saat diklik) --}}
                                        :class="rating >= i ? 'fill-yellow-400 text-yellow-400 drop-shadow-md' : 'text-slate-200 fill-slate-100 group-hover:text-slate-300'"
                                    >
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </button>
                            </template>
                            
                            <input type="hidden" name="rating" x-model="rating" required>
                        </div>
                        
                        <div class="h-6 text-sm font-medium text-indigo-600 transition-opacity" x-show="rating > 0" x-transition>
                            <span x-text="['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'][rating - 1]"></span>
                        </div>
                    </div>

                    {{-- Message Section --}}
                    <div class="space-y-2">
                        <label for="message" class="block font-semibold text-gray-700">Ceritakan Pengalaman Anda</label>
                        <div class="relative">
                            <textarea 
                                id="message"
                                name="message"
                                rows="5"
                                required
                                class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 resize-none placeholder-gray-400 text-gray-800 shadow-sm"
                                placeholder="Pelayanan ramah, pengerjaan cepat, dan hasil memuaskan..."
                            >{{ old('message') }}</textarea>
                            
                            <div class="absolute bottom-4 right-4 text-gray-300">
                                <i data-lucide="pen-line" class="w-5 h-5"></i>
                            </div>
                        </div>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl active:scale-95 flex items-center justify-center gap-2 group"
                    >
                        <span>Kirim Testimoni</span>
                        <i data-lucide="send" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Terima Kasih!',
                    text: '{{ session("success") }}',
                    background: '#ffffff',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'Tutup',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl px-6 py-2.5' }
                });
            @endif
        });
    </script>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn .6s ease-out; }

        @keyframes fadeSlide { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeSlide { animation: fadeSlide .8s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</x-app-layout>