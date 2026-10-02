@php
    $cartaoAtual = isset($fatura) ? $fatura->cartao : $cartao;
@endphp

<x-layouts::app :title="isset($fatura) ? __('Editar Fatura') : __('Adicionar Fatura')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO --}}
        <div>
            <a 
                href="{{ route('cartoes.faturas.index', $cartaoAtual) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Faturas
            </a>

            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3V6a2 2 0 012-2z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ isset($fatura) ? 'Editar Fatura' : 'Adicionar Fatura' }}
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        {{ $cartaoAtual->nome }} • Final **** {{ $cartaoAtual->final_cartao }}
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
                    <span>Não foi possível salvar a fatura:</span>
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
            action="{{ isset($fatura) 
                ? route('cartoes.faturas.update', [$fatura->cartao, $fatura]) 
                : route('cartoes.faturas.store', $cartao) }}"
            class="flex flex-col gap-5 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
        >
            @csrf
            @if(isset($fatura))
                @method('PUT')
            @endif

            {{-- CAMPO OCULTO DO CARTAO_ID --}}
            <input 
                type="hidden" 
                name="cartao_id" 
                value="{{ old('cartao_id', $fatura->cartao_id ?? $cartao->id) }}"
            />

            {{-- MÊS DE REFERÊNCIA --}}
            <div class="flex flex-col gap-1.5">
                <label for="mes_referencia" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Mês de Referência
                </label>

                <div class="relative">
                    <input
                        id="mes_referencia"
                        name="mes_referencia"
                        type="month"
                        value="{{ old('mes_referencia', $fatura->mes_referencia ?? '') }}"
                        class="w-full rounded-xl border px-3.5 py-2.5 text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('mes_referencia') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                </div>
                <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                    O mês ao qual os lançamentos e cobranças desta fatura pertencem.
                </span>
                @error('mes_referencia')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- GRID: DATA DE FECHAMENTO E DATA DE VENCIMENTO --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Data de Fechamento --}}
                <div class="flex flex-col gap-1.5">
                    <label for="data_fechamento" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Data de Fechamento
                    </label>

                    <input
                        id="data_fechamento"
                        name="data_fechamento"
                        type="date"
                        value="{{ old('data_fechamento', $fatura->data_fechamento ?? '') }}"
                        class="w-full rounded-xl border px-3.5 py-2.5 text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('data_fechamento') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                    @error('data_fechamento')
                        <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Data de Vencimento --}}
                <div class="flex flex-col gap-1.5">
                    <label for="data_vencimento" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Data de Vencimento
                    </label>

                    <input
                        id="data_vencimento"
                        name="data_vencimento"
                        type="date"
                        value="{{ old('data_vencimento', $fatura->data_vencimento ?? '') }}"
                        class="w-full rounded-xl border px-3.5 py-2.5 text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('data_vencimento') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                    @error('data_vencimento')
                        <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- CONTA VINCULADA PARA DÉBITO --}}
            <div class="flex flex-col gap-1.5">
                <label for="conta_id" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Conta para Pagamento
                </label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>

                    <select
                        id="conta_id"
                        name="conta_id"
                        class="w-full rounded-xl border pl-10 pr-3.5 py-2.5 text-sm text-neutral-900 bg-white dark:bg-neutral-800 dark:text-white shadow-sm transition
                               @error('conta_id') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    >
                        <option value="">Selecione a conta para pagamento</option>
                        @foreach($contas as $conta)
                            <option 
                                value="{{ $conta->id }}"
                                {{ old('conta_id', $fatura->conta_id ?? '') == $conta->id ? 'selected' : '' }}
                            >
                                {{ $conta->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                    Conta de onde o saldo será debitado ao quitar esta fatura.
                </span>
                @error('conta_id')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- AÇÕES DO FORMULÁRIO --}}
            <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-end gap-3">
                <a
                    href="{{ route('cartoes.faturas.index', $cartaoAtual) }}"
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
                    <span>{{ isset($fatura) ? 'Atualizar Fatura' : 'Salvar Fatura' }}</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>