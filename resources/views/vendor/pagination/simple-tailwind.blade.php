@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" 
         class="flex items-center justify-center py-8">

        <div class="flex items-center gap-10"> {{-- JARAK BESAR DI SINI --}}

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center text-gray-400 select-none">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="inline-flex items-center text-gray-600 hover:text-indigo-600 transition">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </a>
            @endif

            {{-- Current Page --}}
            <span class="px-6 py-2 text-2xl font-bold text-indigo-600"> {{-- ANGKA LEBIH BESAR + SPACING --}}
                {{ $paginator->currentPage() }}
            </span>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="inline-flex items-center text-gray-600 hover:text-indigo-600 transition">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </a>
            @else
                <span class="inline-flex items-center text-gray-400 select-none">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </span>
            @endif

        </div>
    </nav>
@endif