<x-layouts::app :title="__('Classificações')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO: TITULO + AÇÕES --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold">
                    Classificações
                </h1>

                <p class="text-sm text-neutral-500 mt-1">
                    Gerencie as classificações utilizadas nas despesas e compras no cartão.
                </p>
            </div>

            <div class="flex items-center gap-2">

                {{-- NOVA CLASSIFICAÇÃO --}}
                <a
                    href="{{ route('classificacoes.create') }}"
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition"
                >
                    + Nova classificação
                </a>

            </div>

        </div>

        {{-- ALERTA SUCCESS --}}
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

        {{-- TABELA --}}
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 flex flex-col">

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">

                    <thead class="border-b border-neutral-200 dark:border-neutral-700 text-neutral-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Nome</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($classificacoes as $classificacao)

                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">

                                <td class="px-6 py-4 font-medium">
                                    <div class="flex items-center gap-3">

                                        {{-- INDICADOR DE COR --}}
                                        <span
                                            class="h-4 w-4 rounded-full border border-neutral-300 dark:border-neutral-600"
                                            style="background-color: {{ $classificacao->background_color }}"
                                        ></span>

                                        {{-- NOME --}}
                                        <span>
                                            {{ $classificacao->nome }}
                                        </span>

                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $classificacao->slug }}
                                </td>

                                {{-- AÇÕES --}}
                                <td class="px-6 py-4 text-right space-x-3">

                                    {{-- EDITAR --}}
                                    <a
                                        href="{{ route('classificacoes.edit', $classificacao) }}"
                                        class="text-neutral-500 hover:text-blue-600 transition"
                                        title="Editar"
                                    >
                                        ✏️
                                    </a>

                                    {{-- EXCLUIR --}}
                                    <form
                                        action="{{ route('classificacoes.destroy', $classificacao) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Deseja excluir esta classificação?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-neutral-500 hover:text-red-600 transition"
                                            title="Excluir"
                                        >
                                            🗑️
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-neutral-500">
                                    Nenhuma classificação cadastrada.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if($classificacoes instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-700 p-4">
                    {{ $classificacoes->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>