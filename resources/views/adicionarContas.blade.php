<x-layouts::app :title="__('Adicionar Conta')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl max-w-xl">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                Adicionar Conta
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Informe os dados básicos da conta ou carteira
            </p>
        </div>

        @if ($errors->any())
            <div
                class="flex flex-col gap-2 rounded-xl border border-red-200
                    bg-red-50 p-4 text-red-700
                    dark:border-red-900/50 dark:bg-red-950 dark:text-red-300"
            >
                <div class="flex items-center gap-2 font-medium">
                    {{-- Ícone de erro --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0
                                9 9 0 0118 0z"/>
                    </svg>

                    <span>Não foi possível salvar a conta</span>
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
            action="{{ route('adicionar.conta') }}"
            class="flex flex-col gap-5 rounded-xl border border-neutral-200
                   bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            @csrf

            {{-- Nome da conta --}}
            <div class="flex flex-col gap-1">
                <label
                    for="name"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Nome da conta
                </label>

                <input
                    id="name"
                    name="nome"
                    type="text"
                    placeholder="Ex: Carteira Principal, Nubank, Itaú..."
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 placeholder:text-neutral-400
                           focus:border-green-600 focus:ring-green-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white dark:placeholder:text-neutral-500"
                    required
                />
            </div>

            {{-- Saldo --}}
            <div class="flex flex-col gap-1">
                <label
                    for="balance"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Saldo inicial
                </label>

                <input
                    id="balance"
                    name="saldo"
                    type="number"
                    step="0.01"
                    placeholder="0,00"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 placeholder:text-neutral-400
                           focus:border-green-600 focus:ring-green-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white dark:placeholder:text-neutral-500"
                    required
                />
            </div>

            {{-- Ações --}}
            <div class="mt-4 flex justify-end gap-3">
                <a
                    href="{{ route('contas') }}"
                    class="rounded-lg border border-neutral-300 px-4 py-2
                           text-neutral-700 hover:bg-neutral-100
                           dark:border-neutral-700 dark:text-neutral-300
                           dark:hover:bg-neutral-800"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-green-600 px-4 py-2
                           font-medium text-white hover:bg-green-700"
                >
                    Salvar Conta
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>