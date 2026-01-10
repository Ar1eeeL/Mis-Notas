@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-2">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <span class="px-4 py-2 rounded-xl bg-white/5 text-slate-500 cursor-not-allowed">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}"
        class="px-4 py-2 rounded-xl bg-white/5 text-white hover:bg-white/10 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <span class="px-4 py-2 text-slate-500">{{ $element }}</span>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="px-4 py-2 rounded-xl bg-indigo-500 text-white font-medium">
        {{ $page }}
    </span>
    @else
    <a href="{{ $url }}"
        class="px-4 py-2 rounded-xl bg-white/5 text-white hover:bg-white/10 transition-colors">
        {{ $page }}
    </a>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}"
        class="px-4 py-2 rounded-xl bg-white/5 text-white hover:bg-white/10 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </a>
    @else
    <span class="px-4 py-2 rounded-xl bg-white/5 text-slate-500 cursor-not-allowed">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </span>
    @endif
</nav>
@endif