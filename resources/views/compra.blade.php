<x-layouts::app :title="__('Adicionar Compra')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl max-w-xl">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                Adicionar Compra
            </h1>

            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ $cartao->nome }} • Fatura {{ \Carbon\Carbon::parse($fatura->mes_referencia)->format('m/Y') }}
            </p>
        </div>

        {{-- ERROS --}}
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
                    <span>Não foi possível salvar a compra</span>
                </div>

                <ul class="ml-6 list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('cartoes.faturas.compras.store', [$cartao, $fatura]) }}"
            class="flex flex-col gap-5 rounded-xl border border-neutral-200
                   bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            @csrf

            {{-- DESCRIÇÃO --}}
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Descrição
                </label>

                <input
                    name="descricao"
                    type="text"
                    value="{{ old('descricao') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                    required
                />
            </div>

            {{-- DATA DA COMPRA --}}
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Data da compra
                </label>

                <input
                    name="data_compra"
                    type="date"
                    value="{{ old('data_compra') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                    required
                />
            </div>

            {{-- VALOR --}}
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Valor
                </label>

                <input
                    name="valor"
                    type="number"
                    step="0.01"
                    value="{{ old('valor') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                    required
                />
            </div>

            {{-- PARCELAS --}}
            <div class="grid grid-cols-2 gap-4">

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Total de parcelas
                    </label>

                    <input
                        name="total_parcelas"
                        type="number"
                        min="1"
                        value="{{ old('total_parcelas', 1) }}"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                               text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                               dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        required
                    />
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Número da parcela
                    </label>

                    <input
                        name="numero_parcela"
                        type="number"
                        min="1"
                        value="{{ old('numero_parcela', 1) }}"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                               text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                               dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        required
                    />
                </div>

            </div>

            {{-- AÇÕES --}}
            <div class="mt-4 flex justify-end gap-3">

                <a
                    href="{{ route('cartoes.faturas.compras.index', [$cartao, $fatura]) }}"
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
                    Salvar Compra
                </button>

            </div>

        </form>

    </div>
</x-layouts::app>