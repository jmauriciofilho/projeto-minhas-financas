<x-layouts::app :title="__('Receitas')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO: TITULO + AÇÕES --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-2xl font-bold">
                Receitas
            </h1>

            <div class="flex items-center gap-3">

                {{-- FILTRO POR MÊS --}}
                <form 
                    action="{{ route('receitas.index') }}" 
                    method="GET"
                    class="flex items-center gap-2"
                >

                    <input 
                        type="month"
                        name="mes"
                        value="{{$mes}}"
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

        @if (session('error'))
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition
                class="fixed top-6 right-6 z-50 max-w-md"
            >
                <div class="flex items-center gap-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-lg dark:border-red-900/50 dark:bg-red-950 dark:text-red-300">
                    
                    {{-- Ícone SVG de erro integrado do layout do formulário --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                    <span class="text-sm font-medium">
                        {{ session('error') }}
                    </span>

                    {{-- Botão Fechar ajustado para a nova paleta --}}
                    <button 
                        @click="show = false"
                        class="ml-auto text-red-400 hover:text-red-700 dark:text-red-500 dark:hover:text-red-300 text-lg leading-none"
                    >
                        &times;
                    </button>
                </div>
            </div>
        @endif

        {{-- CARDS DE RESUMO --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Total Recebido</p>
                <h2 class="text-2xl font-bold text-green-600 mt-2">
                    R$ {{ number_format($totalRecebidoNoMes, 2, ',', '.') }}
                </h2>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Total Para Receber</p>
                <h2 class="text-2xl font-bold mt-2">
                    R$ {{ number_format($totalParaReceberNoMes, 2, ',', '.') }}
                </h2>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Quantidade</p>
                <h2 class="text-lg font-semibold mt-2">
                    {{$quantidadeReceitasNoMes}} - Receitas
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
                            <th class="px-6 py-4 text-center">Status</th>
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
                                    {{ $receita->conta ? $receita->conta->nome : 'Nenhuma' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $receita->data_recebimento ? \Carbon\Carbon::parse($receita->data_recebimento)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right text-green-600 font-semibold">
                                    R$ {{ number_format($receita->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">

                                    @if(!$receita->ja_recebido)
                                        <form 
                                            action="{{ route('receitas.updateStatus', $receita) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button 
                                                type="submit"
                                                class="px-3 py-1 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
                                            >
                                                Marcar como Recebido
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-green-600 text-white rounded-lg opacity-70 cursor-not-allowed">
                                            Recebido
                                        </span>
                                    @endif

                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        @if(!$receita->ja_recebido)
                                            <a
                                                href="{{ route('receitas.edit', $receita) }}"
                                                class="text-neutral-500 hover:text-blue-600 transition"
                                                title="Editar"
                                            >
                                                ✏️
                                            </a>
                                        @endif

                                        <form 
                                            action="{{ route('receitas.destroy', $receita) }}" 
                                            method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta receita?')"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-neutral-500 hover:text-red-600 transition block"
                                                title="Excluir"
                                            >
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-neutral-500">
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