@if ($paginator->hasPages())
<nav class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50" aria-label="Paginación">

    {{-- Info de registros --}}
    <div class="hidden sm:flex items-center gap-2">
        <span class="text-xs font-semibold text-slate-500">
            Mostrando
            <span class="font-black text-slate-700">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-black text-slate-700">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-black text-slate-700">{{ $paginator->total() }}</span>
            registros
        </span>
    </div>

    {{-- Botones de navegación --}}
    <div class="flex items-center gap-1">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-slate-100/60 text-slate-300 cursor-not-allowed select-none" aria-disabled="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-trigestion-500 hover:text-white hover:border-trigestion-500 transition-all shadow-sm font-bold"
               rel="prev" aria-label="Anterior">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            {{-- Puntos suspensivos --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-xs font-bold text-slate-400 select-none">
                    {{ $element }}
                </span>
            @endif

            {{-- Páginas individuales --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-trigestion-500 text-white text-xs font-black shadow-md shadow-trigestion-500/30 select-none"
                              aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-trigestion-50 hover:text-trigestion-600 hover:border-trigestion-300 transition-all shadow-sm text-xs font-bold">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-trigestion-500 hover:text-white hover:border-trigestion-500 transition-all shadow-sm font-bold"
               rel="next" aria-label="Siguiente">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-slate-100/60 text-slate-300 cursor-not-allowed select-none" aria-disabled="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif

    </div>
</nav>
@endif
