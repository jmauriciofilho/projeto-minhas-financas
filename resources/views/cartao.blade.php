<x-layouts::app :title="isset($cartao) ? __('Editar Cartão') : __('Adicionar Cartão')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO --}}
        <div>
            <a 
                href="{{ route('cartoes.index') }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Cartões
            </a>

            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="1.75"/>
                        <path d="M2 10h20" stroke-width="1.75"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ isset($cartao) ? 'Editar Cartão' : 'Adicionar Cartão' }}
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Informe os dados de identificação do seu cartão de crédito
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
                    <span>Não foi possível salvar o cartão:</span>
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
            action="{{ isset($cartao) ? route('cartoes.update', $cartao) : route('cartoes.store') }}"
            class="flex flex-col gap-5 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
        >
            @csrf
            @if(isset($cartao))
                @method('PUT')
            @endif

            {{-- NOME DO CARTÃO --}}
            <div class="flex flex-col gap-1.5">
                <label for="nome" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Nome do Cartão
                </label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>

                    <input
                        id="nome"
                        name="nome"
                        type="text"
                        value="{{ old('nome', $cartao->nome ?? '') }}"
                        placeholder="Ex: Nubank Ultravioleta, Itaú Personnalité..."
                        class="w-full rounded-xl border pl-10 pr-3.5 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 bg-white dark:bg-neutral-800 dark:text-white dark:placeholder:text-neutral-500 shadow-sm transition
                               @error('nome') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                </div>
                @error('nome')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- 4 ÚLTIMOS DÍGITOS --}}
            <div class="flex flex-col gap-1.5">
                <label for="final_cartao" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    4 Últimos Dígitos
                </label>

                <div class="relative flex items-center">
                    {{-- Prefixo visual do cartão mascarado --}}
                    <span class="absolute left-3.5 select-none font-mono text-xs sm:text-sm tracking-widest text-neutral-400 dark:text-neutral-500">
                        •••• •••• ••••
                    </span>

                    <input
                        id="final_cartao"
                        name="final_cartao"
                        type="text"
                        inputmode="numeric"
                        pattern="\d{4}"
                        maxlength="4"
                        value="{{ old('final_cartao', $cartao->final_cartao ?? '') }}"
                        placeholder="1234"
                        class="w-full rounded-xl border pl-36 sm:pl-40 pr-3.5 py-2.5 font-mono text-sm tracking-widest font-bold text-neutral-900 placeholder:text-neutral-400 bg-white dark:bg-neutral-800 dark:text-white dark:placeholder:text-neutral-500 shadow-sm transition
                               @error('final_cartao') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 @enderror"
                        required
                    />
                </div>
                <span class="text-[11px] text-neutral-400 dark:text-neutral-500">
                    Apenas os 4 últimos dígitos impressos no cartão para facilitar a identificação.
                </span>
                @error('final_cartao')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- AÇÕES DO FORMULÁRIO --}}
            <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-end gap-3">
                <a
                    href="{{ route('cartoes.index') }}"
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
                    <span>{{ isset($cartao) ? 'Atualizar Cartão' : 'Salvar Cartão' }}</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>