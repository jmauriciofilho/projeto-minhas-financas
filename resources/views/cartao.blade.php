<x-layouts::app :title="isset($cartao) ? __('Editar Cartão') : __('Adicionar Cartão')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl max-w-xl">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                {{ isset($cartao) ? 'Editar Cartão' : 'Adicionar Cartão' }}
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Informe os dados básicos do cartão de crédito
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
                    <span>Não foi possível salvar o cartão</span>
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
            action="{{ isset($cartao) 
                ? route('cartoes.update', $cartao->id) 
                : route('cartoes.store') }}"
            class="flex flex-col gap-5 rounded-xl border border-neutral-200
                   bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            @csrf
            @if(isset($cartao))
                @method('PUT')
            @endif

            {{-- Nome do cartão --}}
            <div class="flex flex-col gap-1">
                <label
                    for="nome"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Nome do cartão
                </label>

                <input
                    id="nome"
                    name="nome"
                    type="text"
                    value="{{ old('nome', $cartao->nome ?? '') }}"
                    placeholder="Ex: Nubank, Itaú Platinum..."
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 placeholder:text-neutral-400
                           focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white dark:placeholder:text-neutral-500"
                    required
                />
            </div>

            {{-- Final do cartão --}}
            <div class="flex flex-col gap-1">
                <label
                    for="final_cartao"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    4 últimos dígitos
                </label>

                <input
                    id="final_cartao"
                    name="final_cartao"
                    type="text"
                    maxlength="4"
                    value="{{ old('final_cartao', $cartao->final_cartao ?? '') }}"
                    placeholder="Ex: 4587"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 placeholder:text-neutral-400
                           focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white dark:placeholder:text-neutral-500"
                    required
                />
                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                    Informe apenas os 4 últimos números para identificação.
                </span>
            </div>

            {{-- Ações --}}
            <div class="mt-4 flex justify-end gap-3">
                <a
                    href="{{ route('cartoes.index') }}"
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
                    {{ isset($cartao) ? 'Atualizar Cartão' : 'Salvar Cartão' }}
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>