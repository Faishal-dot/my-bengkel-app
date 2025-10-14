<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-2xl font-bold text-blue-600 mb-4">{{ $product->name }}</h3>

                <p class="text-gray-700 mb-4">
                    {{ $product->description ?? 'Tidak ada deskripsi.' }}
                </p>

                <p class="text-green-600 font-semibold text-lg mb-6">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <button class="px-4 py-2 bg-green-600 text-white rounded">
                    Beli Sekarang
                </button>
                <a href="{{ route('customer.products') }}" 
                   class="ml-3 px-4 py-2 bg-gray-500 text-white rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>