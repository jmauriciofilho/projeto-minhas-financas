<x-layouts::app :title="__('Contas')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Topo da página --}}
        <div class="flex justify-start">
            <a
                href="{{ route('adicionar.conta') }}"
                class="rounded-lg bg-green-600 px-4 py-2 text-white font-medium
                        hover:bg-green-700 transition-colors"
            >
                Adicionar Conta
            </a>
        </div>

        {{-- Grid de cards --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @for ($i = 0; $i < 5; $i++)
                <div
                    class="relative flex flex-col gap-4 rounded-xl border border-neutral-200
                           bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
                >

                    {{-- Ações --}}
                    <div class="absolute right-3 top-3 flex gap-2">
                        {{-- Editar --}}
                        <button
                            title="Editar conta"
                            class="rounded-md p-1 text-neutral-500
                                   hover:bg-neutral-100 hover:text-neutral-800
                                   dark:hover:bg-neutral-800 dark:hover:text-neutral-100"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5h2m-1-1v2m-7.121 7.121l9-9a2 2 0 112.828 2.828l-9 9L4 20l1.879-5.879z"/>
                            </svg>
                        </button>

                        {{-- Excluir --}}
                        <button
                            title="Excluir conta"
                            class="rounded-md p-1 text-red-500
                                   hover:bg-red-100 hover:text-red-700
                                   dark:hover:bg-red-900/40"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                         a2 2 0 01-1.995-1.858L5 7m5-4h4m-4
                                         0a1 1 0 00-1 1v1h6V4a1 1 0
                                         00-1-1m-4 0h4"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Cabeçalho --}}
                    <div class="flex items-center gap-3">
                        {{-- Logo --}}
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full
                                   bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2
                                         2v10a2 2 0 002 2h10a2 2 0 002-2v-2
                                         m2-4h-6a2 2 0 000 4h6v-4z"/>
                            </svg>
                        </div>

                        {{-- Nome --}}
                        <div>
                            <span class="text-sm text-neutral-500 dark:text-neutral-400">
                                Conta
                            </span>
                            <div class="font-medium text-neutral-800 dark:text-neutral-100">
                                Carteira Principal
                            </div>
                        </div>
                    </div>

                    {{-- Saldo --}}
                    <div>
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">
                            Saldo atual
                        </span>
                        <div class="text-2xl font-semibold text-neutral-900 dark:text-white">
                            R$ 2.450,00
                        </div>
                    </div>

                </div>
            @endfor
        </div>

    </div>
</x-layouts::app>