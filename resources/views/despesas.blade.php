<x-layouts::app :title="__('Despesas')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO: TITULO + AÇÕES --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-2xl font-bold">
                Despesas
            </h1>

            <div class="flex items-center gap-3">

                {{-- FILTRO POR MÊS --}}
                <form 
                    action="{{ route('despesas.index') }}" 
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

                {{-- BOTÃO NOVA DESPESA --}}
                <a
                    href="{{ route('despesas.create') }}"
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                    + Nova Despesa
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

        {{-- CARDS DE RESUMO --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Total Para Pagar</p>
                <h2 class="text-2xl font-bold text-red-600 mt-2">
                    R$ {{ number_format($totalParaPagarNoMes, 2, ',', '.') }}
                </h2>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Total Pago</p>
                <h2 class="text-2xl font-bold mt-2">
                    R$ {{ number_format($totalPagoNoMes, 2, ',', '.') }}
                </h2>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <p class="text-sm text-neutral-500">Quantidade</p>
                <h2 class="text-lg font-semibold mt-2">
                    {{$quantidadeDespesasNoMes}} - Despesas
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
                            <th class="px-6 py-4">Data de Pagamento</th>
                            <th class="px-6 py-4 text-right">Valor</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        
                        {{-- Quando for dinâmico, substituir por @foreach --}}
                        @forelse($despesas ?? [] as $despesa)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                                <td class="px-6 py-4 font-medium">
                                    {{ $despesa->nome }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $despesa->conta->nome }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $despesa->data_pagamento ? \Carbon\Carbon::parse($despesa->data_pagamento)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right text-red-600 font-semibold">
                                    R$ {{ number_format($despesa->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">

                                    @if(!$despesa->ja_pago)
                                        <form 
                                            action="{{ route('despesas.updateStatus', $despesa) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button 
                                                type="submit"
                                                class="px-3 py-1 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
                                            >
                                                Marcar como Pago
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-green-600 text-white rounded-lg opacity-70 cursor-not-allowed">
                                            Pago
                                        </span>
                                    @endif

                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <!-- <button class="text-blue-600 hover:underline">Editar</button> -->
                                    <form 
                                        action="{{ route('despesas.destroy', $despesa) }}" 
                                        method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta despesa?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Excluir"
                                            class="text-red-600 hover:underline"
                                        >
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-neutral-500">
                                    Nenhuma despesa encontrada para este mês.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if($despesas instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-700 p-4">
                    {{ $despesas->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>