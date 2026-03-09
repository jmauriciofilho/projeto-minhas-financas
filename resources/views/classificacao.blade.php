<x-layouts::app :title="isset($classificacao) ? __('Editar Classificação') : __('Nova Classificação')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl max-w-xl">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                {{ isset($classificacao) ? 'Editar Classificação' : 'Nova Classificação' }}
            </h1>

            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Informe o nome da classificação.
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
                    <span>Não foi possível salvar a classificação</span>
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
            action="{{ isset($classificacao)
                        ? route('classificacoes.update', $classificacao)
                        : route('classificacoes.store') }}"
            class="flex flex-col gap-5 rounded-xl border border-neutral-200
                   bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            @csrf

            @if(isset($classificacao))
                @method('PUT')
            @endif

            {{-- NOME --}}
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Nome
                </label>

                <input
                    name="nome"
                    type="text"
                    value="{{ old('nome', $classificacao->nome ?? '') }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 focus:border-blue-600 focus:ring-blue-600
                           dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                    required
                />
            </div>

            {{-- AÇÕES --}}
            <div class="mt-4 flex justify-end gap-3">

                <a
                    href="{{ route('classificacoes.index') }}"
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
                    {{ isset($classificacao) ? 'Salvar Alterações' : 'Salvar Classificação' }}
                </button>

            </div>

        </form>

    </div>
</x-layouts::app>