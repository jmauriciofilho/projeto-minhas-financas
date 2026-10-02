<x-layouts::app :title="__('Faturas do Cartão')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">

        {{-- TOPO: NAVEGAÇÃO DE VOLTA + TÍTULO E AÇÃO --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a 
                    href="{{ route('cartoes.index') }}" 
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-1"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para Cartões
                </a>

                <div class="flex items-center gap-3 mt-1">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="1.75"/>
                            <path d="M2 10h20" stroke-width="1.75"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                            Faturas de {{ $cartao->nome }}
                        </h1>
                        <p class="text-xs text-neutral-500 font-mono">
                            •••• •••• •••• {{ $cartao->final_cartao }}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <a
                    href="{{ route('cartoes.faturas.create', $cartao) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nova Fatura</span>
                </a>
            </div>
        </div>

        {{-- ALERTAS DE FEEDBACK (SUCCESS / ERROR) --}}
        @if (session('success'))
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition
                class="fixed top-6 right-6 z-50 max-w-sm"
            >
                <div class="bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
                    <svg class="h-5 w-5 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-white/80 hover:text-white text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition
                class="fixed top-6 right-6 z-50 max-w-sm"
            >
                <div class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-lg dark:border-rose-900/50 dark:bg-rose-950 dark:text-rose-300">
                    <svg class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-rose-400 hover:text-rose-700 dark:text-rose-500 dark:hover:text-rose-300 text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        {{-- CONTAINER DE FATURAS --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden flex flex-col">

            {{-- 1. MODO MOBILE (< md): LISTAGEM EM CARDS --}}
            <div class="md:hidden divide-y divide-neutral-200 dark:divide-neutral-800">
                @forelse($faturas as $fatura)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100 text-base">
                                        {{ \Carbon\Carbon::parse($fatura->mes_referencia)->format('m/Y') }}
                                    </span>

                                    @if(\Carbon\Carbon::parse($fatura->data_fechamento)->isPast())
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                            Fechada
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-900">
                                            Aberta
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-col text-xs text-neutral-500 mt-1 space-y-0.5">
                                    <span>Vencimento: <strong class="text-neutral-700 dark:text-neutral-300">{{ \Carbon\Carbon::parse($fatura->data_vencimento)->format('d/m/Y') }}</strong></span>
                                    <span>Fechamento: {{ \Carbon\Carbon::parse($fatura->data_fechamento)->format('d/m/Y') }}</span>
                                    <span>Débito em: {{ $fatura->conta->nome ?? 'Nenhuma' }}</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-xs text-neutral-400 block mb-0.5">Total</span>
                                <span class="font-bold text-base text-rose-600 dark:text-rose-400 tabular-nums">
                                    R$ {{ number_format($fatura->despesa_total ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status e Ações Mobile --}}
                        <div class="flex items-center justify-between gap-2 pt-2 border-t border-neutral-100 dark:border-neutral-800/60">
                            <div>
                                @if(!$fatura->ja_foi_paga)
                                    <form action="{{ route('cartoes.faturas.updateStatus', [$cartao, $fatura]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 text-xs font-medium bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition shadow-sm"
                                        >
                                            Marcar como Paga
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Paga
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                {{-- Ver Compras --}}
                                <a 
                                    href="{{ route('cartoes.faturas.compras.index', [$cartao, $fatura]) }}" 
                                    class="text-xs text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1 font-medium"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3V6a2 2 0 012-2z"/>
                                    </svg>
                                    Compras
                                </a>

                                @if(!$fatura->ja_foi_paga)
                                    <a 
                                        href="{{ route('cartoes.faturas.edit', [$cartao, $fatura]) }}" 
                                        class="text-xs text-neutral-500 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    <form 
                                        action="{{ route('cartoes.faturas.destroy', [$cartao, $fatura]) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta fatura?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 flex items-center gap-1 transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-neutral-400 dark:text-neutral-500">
                        Nenhuma fatura cadastrada para este cartão.
                    </div>
                @endforelse
            </div>

            {{-- 2. MODO DESKTOP (>= md): TABELA MODERNA --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-neutral-50/75 dark:bg-neutral-800/40 border-b border-neutral-200 dark:border-neutral-800 text-neutral-500 dark:text-neutral-400 text-xs font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Mês Ref.</th>
                            <th class="px-6 py-3.5">Fechamento</th>
                            <th class="px-6 py-3.5">Vencimento</th>
                            <th class="px-6 py-3.5">Conta Débito</th>
                            <th class="px-6 py-3.5 text-right">Total Fatura</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        @forelse($faturas as $fatura)
                            <tr class="hover:bg-neutral-50/60 dark:hover:bg-neutral-800/50 transition">
                                {{-- Mês Referência --}}
                                <td class="px-6 py-4 font-semibold text-neutral-900 dark:text-neutral-100">
                                    {{ \Carbon\Carbon::parse($fatura->mes_referencia)->format('m/Y') }}
                                </td>

                                {{-- Fechamento --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-neutral-700 dark:text-neutral-300">
                                            {{ \Carbon\Carbon::parse($fatura->data_fechamento)->format('d/m/Y') }}
                                        </span>
                                        @if(\Carbon\Carbon::parse($fatura->data_fechamento)->isPast())
                                            <span class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                                Fechada
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-900">
                                                Aberta
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Vencimento --}}
                                <td class="px-6 py-4 text-neutral-700 dark:text-neutral-300">
                                    {{ \Carbon\Carbon::parse($fatura->data_vencimento)->format('d/m/Y') }}
                                </td>

                                {{-- Conta --}}
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">
                                    {{ $fatura->conta->nome ?? 'Nenhuma' }}
                                </td>

                                {{-- Total --}}
                                <td class="px-6 py-4 text-right font-bold text-rose-600 dark:text-rose-400 tabular-nums">
                                    R$ {{ number_format($fatura->despesa_total ?? 0, 2, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    @if(!$fatura->ja_foi_paga)
                                        <form action="{{ route('cartoes.faturas.updateStatus', [$cartao, $fatura]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button 
                                                type="submit"
                                                class="px-3 py-1 text-xs font-medium bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition shadow-sm"
                                            >
                                                Marcar como Paga
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Paga
                                        </span>
                                    @endif
                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Ver Compras da Fatura --}}
                                        <a
                                            href="{{ route('cartoes.faturas.compras.index', [$cartao, $fatura]) }}"
                                            class="p-1.5 text-neutral-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/40 rounded-lg transition"
                                            title="Ver Compras da Fatura"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3V6a2 2 0 012-2z"/>
                                            </svg>
                                        </a>

                                        @if(!$fatura->ja_foi_paga)
                                            {{-- Editar --}}
                                            <a
                                                href="{{ route('cartoes.faturas.edit', [$cartao, $fatura]) }}"
                                                class="p-1.5 text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition"
                                                title="Editar Fatura"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </a>

                                            {{-- Excluir --}}
                                            <form 
                                                action="{{ route('cartoes.faturas.destroy', [$cartao, $fatura]) }}" 
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Tem certeza que deseja excluir esta fatura?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="p-1.5 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition"
                                                    title="Excluir Fatura"
                                                >
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] text-neutral-400 px-2 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Paga
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-neutral-400 dark:text-neutral-500">
                                    Nenhuma fatura cadastrada para este cartão.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- PAGINAÇÃO --}}
            @if($faturas instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-800 p-4">
                    {{ $faturas->withQueryString()->links('partials.pagination') }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>