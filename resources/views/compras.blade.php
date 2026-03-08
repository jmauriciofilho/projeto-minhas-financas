<x-layouts::app :title="__('Compras da Fatura')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO: TITULO + AÇÕES --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold">
                    Compras da Fatura
                </h1>

                <p class="text-sm text-neutral-500 mt-1">
                    {{ $cartao->nome }} • 
                    Fatura {{ \Carbon\Carbon::parse($fatura->mes_referencia)->format('m/Y') }}
                </p>
            </div>

            <div class="flex items-center gap-2">

                {{-- VOLTAR --}}
                <a
                    href="{{ route('cartoes.faturas.index', $cartao) }}"
                    class="px-4 py-2 border border-neutral-300 text-sm rounded-lg
                        hover:bg-neutral-100 transition
                        dark:border-neutral-700 dark:hover:bg-neutral-800"
                >
                    ← Voltar para Faturas
                </a>

                {{-- NOVA COMPRA --}}
                @if(!$fatura->ja_foi_paga)
                    <a href="{{ route('cartoes.faturas.compras.create', [$cartao, $fatura]) }}"
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                        + Nova compra
                    </a>
                @else
                    <button
                        class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed"
                        disabled>
                        + Nova compra
                    </button>
                @endif

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
                            <th class="px-6 py-4">Descrição</th>
                            <th class="px-6 py-4">Data</th>
                            <th class="px-6 py-4 text-center">Parcela</th>
                            <th class="px-6 py-4 text-right">Valor</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($compras as $compra)

                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">

                                <td class="px-6 py-4 font-medium">
                                    {{ $compra->descricao }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($compra->data_compra)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($compra->total_parcelas > 1)
                                        {{ $compra->numero_parcela }}/{{ $compra->total_parcelas }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right font-semibold">
                                    R$ {{ number_format($compra->valor, 2, ',', '.') }}
                                </td>

                                {{-- AÇÕES --}}
                                <td class="px-6 py-4 text-right space-x-3">

                                    <form
                                        action="{{ route('cartoes.faturas.compras.destroy', [$cartao, $fatura, $compra]) }}"
                                        method="POST"
                                        class="inline"
                                        @if(!$fatura->ja_foi_paga)
                                            onsubmit="return confirm('Deseja excluir esta compra?')"
                                        @endif
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            @if($fatura->ja_foi_paga)
                                                disabled
                                                class="text-neutral-300 cursor-not-allowed"
                                                title="Fatura paga - não é possível excluir"
                                            @else
                                                class="text-neutral-500 hover:text-red-600 transition"
                                                title="Excluir"
                                            @endif
                                        >
                                            🗑️
                                        </button>

                                    </form>

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-neutral-500">
                                    Nenhuma compra cadastrada nesta fatura.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if($compras instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-700 p-4">
                    {{ $compras->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>