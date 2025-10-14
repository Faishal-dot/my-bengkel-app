<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div x-data="{ open: false }" class="min-h-screen flex">

            <!-- Sidebar -->
            @include('layouts.navigation')

            <!-- Konten utama -->
            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 lg:ml-64">
                <!-- Header -->
                @isset($header)
                    <header class="bg-white shadow sticky top-0 z-30">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                            {{ $header }}
                            <!-- Tombol toggle sidebar di mobile -->
                            <button @click="open = !open" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                                <i data-lucide="menu" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-grow">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t py-4 mt-auto">
                    <div class="max-w-7xl mx-auto text-center text-sm text-gray-500">
                        © {{ date('Y') }} Bengkel Oto. All rights reserved.
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>