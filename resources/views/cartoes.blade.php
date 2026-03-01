<x-layouts::app :title="__('Cartões de Crédito')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        @if (session('success'))
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition
                class="fixed top-6 right-6 z-50"
            >
                <div class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-4">
                    
                    <span class="font-medium">
                        {{ session('success') }}
                    </span>

                    <button 
                        @click="show = false"
                        class="text-white/80 hover:text-white text-lg leading-none"
                    >
                        &times;
                    </button>

                </div>
            </div>
        @endif

        {{-- Topo --}}
        <div class="flex justify-start">
            <a
                href="{{ route('cartoes.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium
                       hover:bg-blue-700 transition-colors"
            >
                Adicionar Cartão
            </a>
        </div>

        {{-- Grid --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            @foreach ($cartoes as $cartao)
                {{-- Card --}}
                <div class="relative flex flex-col gap-4 rounded-xl border border-neutral-200
                            bg-white p-5 shadow-sm hover:shadow-md transition
                            dark:border-neutral-700 dark:bg-neutral-900">

                    {{-- Ações --}}
                    <div class="absolute right-3 top-3 flex gap-2">

                        {{-- Faturas --}}
                        <a
                            title="Ver faturas"
                            href="{{ route('cartoes.faturas.index', $cartao) }}"
                            class="rounded-md p-1 text-blue-500
                                hover:bg-blue-100 hover:text-blue-700
                                dark:hover:bg-blue-900/40"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3V6a2 2 0 012-2z"/>
                            </svg>
                        </a>

                        {{-- Editar --}}
                        <a
                            title="Editar cartão"
                            href="{{ route('cartoes.edit', $cartao) }}"
                            class="rounded-md p-1 text-neutral-500
                                hover:bg-neutral-100 hover:text-neutral-800
                                dark:hover:bg-neutral-800 dark:hover:text-neutral-100"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5h2m-1-1v2m-7.121 7.121l9-9a2 2 0 112.828 2.828l-9 9L4 20l1.879-5.879z"/>
                            </svg>
                        </a>

                        {{-- Excluir --}}
                        <form 
                            action="{{ route('cartoes.destroy', $cartao) }}" 
                            method="POST"
                            onsubmit="return confirm('Tem certeza que deseja excluir este cartão?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                title="Excluir cartão"
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
                        </form>

                    </div>

                    {{-- Cabeçalho --}}
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full
                                    bg-blue-100 text-blue-700
                                    dark:bg-blue-900 dark:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                <path d="M2 10h20"/>
                            </svg>
                        </div>

                        <div>
                            <span class="text-sm text-neutral-500 dark:text-neutral-400">
                                Cartão
                            </span>
                            <div class="font-medium text-neutral-800 dark:text-neutral-100">
                                {{$cartao->nome}}
                            </div>
                        </div>
                    </div>

                    {{-- Informações --}}
                    <div class="mt-2">
                        <span class="text-sm text-neutral-500 dark:text-neutral-400">
                            Final
                        </span>
                        <div class="text-lg font-semibold tracking-widest text-neutral-900 dark:text-white">
                            **** {{$cartao->final_cartao}}
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

    </div>
</x-layouts::app>