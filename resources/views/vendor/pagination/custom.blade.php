@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4 space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-sm text-blue-600 bg-white rounded-lg">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-sm text-blue-600 bg-white rounded-lg hover:bg-gray-300 transition">←</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Dots --}}
            @if (is_string($element))
                <span class="px-3 py-1 text-sm text-gray-400">{{ $element }}</span>
            @endif

            {{-- Page Numbers --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-semibold bg-blue-600 text-white rounded-lg" style="min-height: 2rem;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-sm text-blue-600 border bg-white rounded-lg hover:bg-gray-300 transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-sm text-blue-600 bg-white rounded-lg hover:bg-gray-300 transition">→</a>
        @else
            <span class="px-3 py-1 text-sm text-gray-400 bg-white rounded-lg">→</span>
        @endif
    </nav>
@endif
