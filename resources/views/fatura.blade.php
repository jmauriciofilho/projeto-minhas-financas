<x-layouts::app :title="isset($fatura) ? __('Editar Fatura') : __('Adicionar Fatura')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl max-w-xl">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                {{ isset($fatura) ? 'Editar Fatura' : 'Adicionar Fatura' }}
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Informe os dados da fatura do cartão
            </p>
        </div>

        @if ($errors->any())
            <div
                class="flex flex-col gap-2 rounded-xl border border-red-200
                    bg-red-50 p-4 text-red-700
                    dark:border-red-900/50 dark:bg-red-950 dark:text-red-300"
            >
                <div class="flex items-center gap-2 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0
                                9 9 0 0118 0z"/>
                    </svg>
                    <span>Não foi possível salvar a fatura</span>
                </div>

                <ul class="ml-6 list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário --}}
        <form
            method="POST"
            action="{{ isset($fatura) 
                ? route('cartoes.faturas.update', [$fatura->cartao, $fatura]) 
                : route('cartoes.faturas.store', $cartao) }}"
            class="flex flex-col gap-5 rounded-xl border border-neutral-200
                   bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            @csrf
            @if(isset($fatura))
                @method('PUT')
            @endif

            {{-- cartao_id (hidden) --}}
            <input 
                type="hidden" 
                name="cartao_id" 
                value="{{ old('cartao_id', $fatura->cartao_id ?? $cartao->id) }}"
            />

            {{-- Mês de referência --}}
            <div class="flex flex-col gap-1">
                <label
                    for="mes_referencia"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Mês de referência
                </label>

                <input
                    id="mes_referencia"
                    name="mes_referencia"
                    type="month"
                    value="{{ old('mes_referencia', $fatura->mes_referencia ?? '') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white"
                    required
                />
            </div>

            {{-- Data de fechamento --}}
            <div class="flex flex-col gap-1">
                <label
                    for="data_fechamento"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Data de fechamento
                </label>

                <input
                    id="data_fechamento"
                    name="data_fechamento"
                    type="date"
                    value="{{ old('data_fechamento', $fatura->data_fechamento ?? '') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white"
                    required
                />
            </div>

            {{-- Data de vencimento --}}
            <div class="flex flex-col gap-1">
                <label
                    for="data_vencimento"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Data de vencimento
                </label>

                <input
                    id="data_vencimento"
                    name="data_vencimento"
                    type="date"
                    value="{{ old('data_vencimento', $fatura->data_vencimento ?? '') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white"
                    required
                />
            </div>

            {{-- Conta vinculada --}}
            <div class="flex flex-col gap-1">
                <label
                    for="conta_id"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Conta para pagamento
                </label>

                <select
                    id="conta_id"
                    name="conta_id"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white"
                    required
                >
                    <option value="">Selecione uma conta</option>

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

            {{-- Ações --}}
            <div class="mt-4 flex justify-end gap-3">
                <a
                    href="{{ route('cartoes.faturas.index', isset($fatura) ? $fatura->cartao : $cartao) }}"
                    class="rounded-lg border border-neutral-300 px-4 py-2
                           text-neutral-700 hover:bg-neutral-100
                           dark:border-neutral-700 dark:text-neutral-300
                           dark:hover:bg-neutral-800"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2
                           font-medium text-white hover:bg-blue-700"
                >
                    {{ isset($fatura) ? 'Atualizar Fatura' : 'Salvar Fatura' }}
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>