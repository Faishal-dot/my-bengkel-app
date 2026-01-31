<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-2xl text-gray-800 flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-7 h-7 text-indigo-600"></i>
                Keranjang Belanja
            </h2>
            <a href="{{ route('customer.products') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali Belanja
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl flex items-center gap-2 animate-fadeSlide">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl flex items-center gap-2 animate-fadeSlide">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('cart') && count(session('cart')) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Daftar Item --}}
                    <div class="lg:col-span-2 space-y-4">
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap md:flex-nowrap items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="w-20 h-20 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="package" class="w-10 h-10 text-indigo-300"></i>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $details['name'] }}</h3>
                                    <p class="text-indigo-600 font-bold">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                </div>
                                
                                {{-- Update Quantity --}}
                                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden p-1">
                                    <form action="{{ route('customer.cart.update', $id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" 
                                            class="w-16 border-none text-center focus:ring-0 text-sm font-bold"
                                            onchange="this.form.submit()">
                                    </form>
                                </div>

                                {{-- Hapus --}}
                                <form action="{{ route('customer.cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan Pesanan --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 sticky top-6">
                            <h3 class="text-xl font-extrabold text-gray-800 mb-6">Ringkasan Pesanan</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between text-gray-500">
                                    <span>Total Item</span>
                                    <span class="font-bold text-gray-800">{{ count(session('cart')) }} Produk</span>
                                </div>
                                <div class="pt-4 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Total Harga</span>
                                        <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- PERBAIKAN: Bungkus tombol dengan form checkout --}}
                            <form action="{{ route('customer.orders.store', array_key_first(session('cart'))) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-bold shadow-xl shadow-indigo-100 transition-all flex items-center justify-center gap-2 transform active:scale-95">
                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    Lanjutkan Checkout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @else
                {{-- Tampilan Kosong --}}
                <div class="text-center py-24 bg-white rounded-[2rem] border-2 border-dashed border-gray-200 flex flex-col items-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-200"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Keranjang Masih Kosong</h3>
                    <p class="text-gray-500 mt-2 mb-8 max-w-sm">Anda belum menambahkan produk apa pun ke dalam keranjang belanja Anda.</p>
                    <a href="{{ route('customer.products') }}" class="px-10 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                        Cari Sparepart Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    <style>
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeSlide { animation: fadeSlide 0.4s ease-out forwards; }
    </style>
</x-app-layout>