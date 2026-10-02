<x-layouts::app :title="__('Editar Compra')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO CONTEXTUAL --}}
        <div>
            <a 
                href="{{ route('cartoes.faturas.compras.index', [$cartao, $fatura]) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Compras da Fatura
            </a>

            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Editar Compra
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        {{ $cartao->nome }} (**** {{ $cartao->final_cartao }}) • Fatura {{ \Carbon\Carbon::parse($fatura->mes_referencia)->format('m/Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ALERTA DE ERROS DE VALIDAÇÃO GERAL --}}
        @if ($errors->any())
            <div class="flex flex-col gap-2 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm dark:border-rose-900/50 dark:bg-rose-950 dark:text-rose-300">
                <div class="flex items-center gap-2 font-semibold text-sm">
                    <svg class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Não foi possível atualizar a compra:</span>
                </div>
                <ul class="ml-7 list-disc text-xs sm:text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULÁRIO --}}
        <form
            method="POST"
            action="{{ route('cartoes.faturas.compras.update', [$cartao, $fatura, $compra]) }}"
            class="flex flex-col gap-5 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
        >
            @csrf
            @method('PUT')

            {{-- CONTEXTO RÁPIDO DO LANÇAMENTO --}}
            <div class="p-3.5 rounded-xl bg-neutral-50 dark:bg-neutral-800/40 border border-neutral-100 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <span class="text-xs text-neutral-400 block">Status da Fatura</span>
                    @if($fatura->ja_foi_paga)
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Fatura Paga
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Fatura Aberta
                        </span>
                    @endif
                </div>

                <div class="text-right">
                    <span class="text-xs text-neutral-400 block">Valor Registrado</span>
                    <span class="text-base font-bold text-rose-600 dark:text-rose-400 tabular-nums mt-0.5 inline-block">
                        R$ {{ number_format($compra->valor, 2, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- DESCRIÇÃO DA COMPRA --}}
            <div class="flex flex-col gap-1.5">
                <label for="descricao" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Descrição da Compra
                </label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>

                    <input
                        id="descricao"
                        name="descricao"
                        type="text"
                        value="{{ old('descricao', $compra->descricao) }}"
                        placeholder="Ex: Supermercado, Farmácia, Assinatura..."
                        class="w-full rounded-xl border pl-10 pr-3.5 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 bg-white dark:bg-neutral-800 dark:text-white dark:placeholder:text-neutral-500 shadow-sm transition
                               @error('descricao') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                </div>
                @error('descricao')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- GRID: DATA DA COMPRA E VALOR --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Data da Compra --}}
                <div class="flex flex-col gap-1.5">
                    <label for="data_compra" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Data da Compra
                    </label>

                    <input
                        id="data_compra"
                        name="data_compra"
                        type="date"
                        value="{{ old('data_compra', $compra->data_compra ? \Carbon\Carbon::parse($compra->data_compra)->format('Y-m-d') : '') }}"
                        class="w-full rounded-xl border px-3.5 py-2.5 text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('data_compra') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                    @error('data_compra')
                        <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Valor da Compra --}}
                <div class="flex flex-col gap-1.5">
                    <label for="valor" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Valor
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-xs font-semibold text-neutral-400">
                            R$
                        </span>

                        <input
                            id="valor"
                            name="valor"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('valor', $compra->valor) }}"
                            placeholder="0,00"
                            class="w-full rounded-xl border pl-10 pr-3.5 py-2.5 font-mono text-sm font-semibold text-rose-600 dark:text-rose-400 placeholder:text-neutral-400 bg-white dark:bg-neutral-800 shadow-sm transition
                                   @error('valor') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                            required
                        />
                    </div>
                    @error('valor')
                        <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- GRID: PARCELAS (TOTAL DE PARCELAS & NÚMERO DA PARCELA) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Total de Parcelas --}}
                <div class="flex flex-col gap-1.5">
                    <label for="total_parcelas" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Total de Parcelas
                    </label>

                    <input
                        id="total_parcelas"
                        name="total_parcelas"
                        type="number"
                        min="1"
                        max="99"
                        value="{{ old('total_parcelas', $compra->total_parcelas) }}"
                        class="w-full rounded-xl border px-3.5 py-2.5 font-mono text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('total_parcelas') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                    <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                        Informe 1 se for compra à vista.
                    </span>
                    @error('total_parcelas')
                        <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Número da Parcela Atual --}}
                <div class="flex flex-col gap-1.5">
                    <label for="numero_parcela" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Número da Parcela
                    </label>

                    <input
                        id="numero_parcela"
                        name="numero_parcela"
                        type="number"
                        min="1"
                        max="99"
                        value="{{ old('numero_parcela', $compra->numero_parcela) }}"
                        class="w-full rounded-xl border px-3.5 py-2.5 font-mono text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('numero_parcela') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                    <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                        Parcela correspondente desta fatura.
                    </span>
                    @error('numero_parcela')
                        <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- AÇÕES DO FORMULÁRIO --}}
            <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-end gap-3">
                <a
                    href="{{ route('cartoes.faturas.compras.index', [$cartao, $fatura]) }}"
                    class="px-4 py-2 text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-xl transition"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs sm:text-sm font-medium rounded-xl shadow-sm transition-all"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Atualizar Compra</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>