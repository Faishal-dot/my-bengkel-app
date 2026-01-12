@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
        class="flex items-center justify-center mt-6">

        <div class="inline-flex items-center space-x-5"> {{-- spacing --}}

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="text-gray-400 cursor-not-allowed select-none">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="text-gray-700 hover:text-indigo-600 transition select-none">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)

                {{-- Dots --}}
                @if (is_string($element))
                    <span class="text-gray-500 select-none">{{ $element }}</span>
                @endif

                {{-- Array of pages --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="font-semibold text-indigo-600 relative pb-1 select-none">
                                {{ $page }}
                                <span
                                    class="absolute left-0 right-0 -bottom-1 h-[3px] bg-indigo-600 rounded-full"></span>
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="text-gray-600 hover:text-indigo-500 transition relative pb-1">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif

            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="text-gray-700 hover:text-indigo-600 transition select-none">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </a>
            @else
                <span class="text-gray-400 cursor-not-allowed select-none">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </span>
            @endif

        </div>
    </nav>
@endif