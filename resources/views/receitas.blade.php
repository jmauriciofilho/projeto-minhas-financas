<x-layouts::app :title="__('Receitas')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">

        {{-- TOPO: TÍTULO + AÇÕES RESPONSIVAS --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Receitas
                </h1>
                <p class="text-xs text-neutral-500 mt-0.5">Acompanhe seus rendimentos e valores a receber no mês</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{-- FILTRO POR MÊS --}}
                <form action="{{ route('receitas.index') }}" method="GET" class="flex items-center gap-2">
                    <input 
                        type="month"
                        name="mes"
                        value="{{ $mes }}"
                        class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400"
                    >
                    <button 
                        type="submit"
                        class="px-3.5 py-2 bg-neutral-800 text-white dark:bg-neutral-100 dark:text-neutral-900 text-sm font-medium rounded-xl hover:bg-neutral-700 dark:hover:bg-neutral-200 transition shadow-sm"
                    >
                        Filtrar
                    </button>
                </form>

                {{-- BOTÃO NOVA RECEITA --}}
                <a
                    href="{{ route('receitas.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nova Receita</span>
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

        {{-- CARDS DE RESUMO COM ÍCONES CONTEXTUAIS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Card 1: Total Recebido --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Recebido</p>
                    <h2 class="text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-1 tabular-nums">
                        R$ {{ number_format($totalRecebidoNoMes, 2, ',', '.') }}
                    </h2>
                </div>
                <div class="p-3 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>

            {{-- Card 2: Total Para Receber --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">A Receber no Mês</p>
                    <h2 class="text-2xl font-bold tracking-tight text-amber-600 dark:text-amber-400 mt-1 tabular-nums">
                        R$ {{ number_format($totalParaReceberNoMes, 2, ',', '.') }}
                    </h2>
                </div>
                <div class="p-3 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Card 3: Quantidade --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total de Entradas</p>
                    <h2 class="text-2xl font-bold tracking-tight text-neutral-800 dark:text-neutral-100 mt-1">
                        {{ $quantidadeReceitasNoMes }} <span class="text-sm font-normal text-neutral-500">recebimentos</span>
                    </h2>
                </div>
                <div class="p-3 rounded-2xl bg-neutral-500/10 text-neutral-600 dark:text-neutral-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- CONTAINER PRINCIPAL DE RECEITAS --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden flex flex-col">

            {{-- 1. MODO MOBILE (< md): LISTAGEM EM CARDS --}}
            <div class="md:hidden divide-y divide-neutral-200 dark:divide-neutral-800">
                @forelse($receitas ?? [] as $receita)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-neutral-900 dark:text-neutral-100 text-base">
                                    {{ $receita->nome }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1 text-xs text-neutral-500">
                                    <span>{{ $receita->conta ? $receita->conta->nome : 'Sem conta associada' }}</span>
                                    <span>•</span>
                                    <span>{{ $receita->data_recebimento ? \Carbon\Carbon::parse($receita->data_recebimento)->format('d/m/Y') : 'Data não def.' }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-base text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    R$ {{ number_format($receita->valor, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status e Ações --}}
                        <div class="flex items-center justify-between gap-2 pt-2 border-t border-neutral-100 dark:border-neutral-800/60">
                            <div>
                                @if($receita->ja_recebido)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Recebido
                                    </span>
                                @else
                                    <form action="{{ route('receitas.updateStatus', $receita) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 text-xs font-medium bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition shadow-sm">
                                            Confirmar Recebimento
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                @if(!$receita->ja_recebido)
                                    <a href="{{ route('receitas.edit', $receita) }}" class="text-xs text-neutral-600 hover:text-blue-600 dark:text-neutral-400 dark:hover:text-blue-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Editar
                                    </a>
                                @endif

                                <form action="{{ route('receitas.destroy', $receita) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta receita?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-neutral-500 hover:text-rose-600 dark:hover:text-rose-400 flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-neutral-400 dark:text-neutral-500">
                        Nenhuma receita encontrada para este mês.
                    </div>
                @endforelse
            </div>

            {{-- 2. MODO DESKTOP (>= md): TABELA --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-neutral-50/75 dark:bg-neutral-800/40 border-b border-neutral-200 dark:border-neutral-800 text-neutral-500 dark:text-neutral-400 text-xs font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Descrição</th>
                            <th class="px-6 py-3.5">Conta</th>
                            <th class="px-6 py-3.5">Data de Recebimento</th>
                            <th class="px-6 py-3.5 text-right">Valor</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        @forelse($receitas ?? [] as $receita)
                            <tr class="hover:bg-neutral-50/60 dark:hover:bg-neutral-800/50 transition">
                                <td class="px-6 py-4 font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $receita->nome }}
                                </td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">
                                    {{ $receita->conta ? $receita->conta->nome : 'Nenhuma' }}
                                </td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">
                                    {{ $receita->data_recebimento ? \Carbon\Carbon::parse($receita->data_recebimento)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    R$ {{ number_format($receita->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$receita->ja_recebido)
                                        <form action="{{ route('receitas.updateStatus', $receita) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button 
                                                type="submit"
                                                class="px-3 py-1 text-xs font-medium bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition shadow-sm"
                                            >
                                                Marcar como Recebido
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Recebido
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$receita->ja_recebido)
                                            <a
                                                href="{{ route('receitas.edit', $receita) }}"
                                                class="p-1.5 text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition"
                                                title="Editar"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </a>
                                        @endif

                                        <form 
                                            action="{{ route('receitas.destroy', $receita) }}" 
                                            method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta receita?')"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="p-1.5 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition"
                                                title="Excluir"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-neutral-400 dark:text-neutral-500">
                                    Nenhuma receita encontrada para este mês.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if($receitas instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-800 p-4">
                    {{ $receitas->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>