<x-layouts::app :title="__('Receitas')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO: TITULO + AÇÕES --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-2xl font-bold">
                Receitas
            </h1>

            <div class="flex items-center gap-3">

                {{-- FILTRO POR MÊS --}}
                <form class="flex items-center gap-2">

                    <input 
                        type="month"
                        value="2026-02"
                        class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    >

                    <button 
                        type="submit"
                        class="px-4 py-2 bg-neutral-800 text-white text-sm rounded-lg hover:bg-neutral-700 transition"
                    >
                        Filtrar
                    </button>

                </form>

                {{-- BOTÃO NOVA RECEITA --}}
                <a
                    href="{{ route('receitas.create') }}"
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                    + Nova Receita
                </a>

            </div>

        </div>

        {{-- CARDS DE RESUMO --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Total em Fevereiro</p>
                <h2 class="text-2xl font-bold text-green-600 mt-2">
                    R$ 5.350,00
                </h2>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Quantidade</p>
                <h2 class="text-2xl font-bold mt-2">
                    3 receitas
                </h2>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Maior Receita</p>
                <h2 class="text-lg font-semibold mt-2">
                    Salário - R$ 3.500,00
                </h2>
            </div>

        </div>

        {{-- TABELA --}}
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 flex flex-col">

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="border-b border-neutral-200 dark:border-neutral-700 text-neutral-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Descrição</th>
                            <th class="px-6 py-4">Conta</th>
                            <th class="px-6 py-4">Data de Recebimento</th>
                            <th class="px-6 py-4 text-right">Valor</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        
                        {{-- Quando for dinâmico, substituir por @foreach --}}
                        @forelse($receitas ?? [] as $receita)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                                <td class="px-6 py-4 font-medium">
                                    {{ $receita->nome }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $receita->conta->nome }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($receita->data_recebimento)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-green-600 font-semibold">
                                    R$ {{ number_format($receita->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="text-blue-600 hover:underline">Editar</button>
                                    <button class="text-red-600 hover:underline">Excluir</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-neutral-500">
                                    Nenhuma receita encontrada para este mês.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if($receitas instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-700 p-4">
                    {{ $receitas->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>