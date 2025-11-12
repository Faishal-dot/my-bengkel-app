<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- ✅ Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ==========================
           ANIMASI LOGO BENGKEL OTO
           ========================== */
        @keyframes gentleMove {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }

        .icon-move {
            animation: gentleMove 1.8s ease-in-out infinite;
        }

        .text-move {
            animation: gentleMove 2.2s ease-in-out infinite;
        }

        .logo-anim {
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.3s ease;
        }

        .logo-anim:hover {
            transform: scale(1.05);
        }

        /* ==========================
           LAYOUT & DASHBOARD FIX
           ========================== */

        /* Hilangkan area putih di bawah */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6; /* bg-gray-100 */
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        footer {
            margin-top: auto;
        }

        /* Supaya sidebar tidak menimpa konten */
        @media (min-width: 1024px) {
            main, header, footer {
                margin-left: 16rem; /* 64 = 16rem = 256px */
            }
        }
    </style>

</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ open: false }" class="min-h-screen flex">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Konten utama -->
        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300">
            
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