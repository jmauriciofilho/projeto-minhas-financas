<x-layouts::app :title="__('Cartões de Crédito')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">

        {{-- TOPO: TÍTULO + AÇÃO DE NOVO CARTÃO --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Cartões de Crédito
                </h1>
                <p class="text-xs text-neutral-500 mt-0.5">Gerencie seus cartões, acompanhe faturas e compras parceladas</p>
            </div>

            <div>
                <a
                    href="{{ route('cartoes.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Adicionar Cartão</span>
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

        {{-- GRID DE CARTÕES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($cartoes as $cartao)
                <div class="relative flex flex-col justify-between rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-5 shadow-sm hover:shadow-md hover:border-neutral-300 dark:hover:border-neutral-700 transition-all duration-200 group">

                    {{-- Topo do Card: Identificação e Ações --}}
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                {{-- Ícone de Cartão --}}
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="1.75"/>
                                        <path d="M2 10h20" stroke-width="1.75"/>
                                    </svg>
                                </div>

                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-neutral-900 dark:text-neutral-100 text-base truncate" title="{{ $cartao->nome }}">
                                        {{ $cartao->nome }}
                                    </h3>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-neutral-400">
                                        Crédito
                                    </span>
                                </div>
                            </div>

                            {{-- Menu de Ações (Editar e Excluir) --}}
                            <div class="flex items-center gap-1">
                                <a
                                    href="{{ route('cartoes.edit', $cartao) }}"
                                    title="Editar cartão"
                                    class="p-1.5 text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>

                                <form 
                                    action="{{ route('cartoes.destroy', $cartao) }}" 
                                    method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este cartão? Todas as faturas e compras vinculadas serão afetadas.')"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        title="Excluir cartão"
                                        class="p-1.5 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Número do Cartão Mascarado --}}
                        <div class="mt-6 mb-2">
                            <span class="text-[11px] font-medium text-neutral-400 uppercase tracking-wider block mb-1">
                                Número do Cartão
                            </span>
                            <div class="font-mono text-base sm:text-lg tracking-widest text-neutral-800 dark:text-neutral-100 flex items-center gap-2">
                                <span class="text-neutral-400">•••• •••• ••••</span>
                                <span class="font-bold">{{ $cartao->final_cartao }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Rodapé: Botão de Faturas com Cores Vivas e Alto Contraste --}}
                    <div class="mt-5 pt-3 border-t border-neutral-100 dark:border-neutral-800/80">
                        <a
                            href="{{ route('cartoes.faturas.index', $cartao) }}"
                            class="w-full inline-flex items-center justify-between px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold shadow-sm transition-all"
                        >
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3V6a2 2 0 012-2z"/>
                                </svg>
                                Ver Faturas e Compras
                            </span>
                            <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-800 p-12 text-center bg-white/50 dark:bg-neutral-900/50">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="1.5"/>
                            <path d="M2 10h20" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-neutral-800 dark:text-neutral-200">Nenhum cartão cadastrado</h3>
                    <p class="mt-1 text-sm text-neutral-500">Cadastre seus cartões de crédito para registrar compras parceladas e controlar as faturas.</p>
                    <div class="mt-5">
                        <a
                            href="{{ route('cartoes.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Cadastrar Primeiro Cartão</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts::app>