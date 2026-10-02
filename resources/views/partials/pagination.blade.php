@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegação de Páginas" class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-neutral-500 dark:text-neutral-400">
        {{-- Texto de Contagem --}}
        <div>
            Exibindo 
            <span class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $paginator->firstItem() }}</span>
            a
            <span class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $paginator->total() }}</span>
            resultados
        </div>

        {{-- Botões de Páginas --}}
        <div class="inline-flex items-center gap-1">
            {{-- Botão Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="px-2.5 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 text-neutral-300 dark:text-neutral-700 cursor-not-allowed">
                    &lsaquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-2.5 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                    &lsaquo;
                </a>
            @endif

            {{-- Números das Páginas --}}
            @foreach ($elements as $element)
                {{-- Separador de Três Pontos (...) --}}
                @if (is_string($element))
                    <span class="px-2 py-1 text-neutral-400">{{ $element }}</span>
                @endif

                {{-- Lista de Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 rounded-lg font-semibold bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botão Próximo --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-2.5 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                    &rsaquo;
                </a>
            @else
                <span class="px-2.5 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 text-neutral-300 dark:text-neutral-700 cursor-not-allowed">
                    &rsaquo;
                </span>
            @endif
        </div>
    </nav>
@endif