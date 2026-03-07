<x-layouts::app :title="__('Faturas do Cartão')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO: TITULO + DADOS DO CARTÃO --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold">
                    Faturas
                </h1>

                <p class="text-sm text-neutral-500 mt-1">
                    {{ $cartao->nome }} • Final **** {{$cartao->final_cartao}}
                </p>
            </div>

            <a
                href="{{ route('cartoes.faturas.create', $cartao) }}"
                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                + Nova Fatura
            </a>

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
                            <th class="px-6 py-4">Mês Referência</th>
                            <th class="px-6 py-4">Fechamento</th>
                            <th class="px-6 py-4">Vencimento</th>
                            <th class="px-6 py-4">Conta</th>
                            <th class="px-4 py-2 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($faturas as $fatura)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">

                                <td class="px-6 py-4 font-medium">
                                    {{ \Carbon\Carbon::parse($fatura->mes_referencia)->format('m/Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    Dia {{ \Carbon\Carbon::parse($fatura->data_fechamento)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    Dia {{ \Carbon\Carbon::parse($fatura->data_vencimento)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $fatura->conta->nome ?? '-' }}
                                </td>

                                <td class="px-4 py-2 text-right font-semibold">
                                    R$ {{ number_format($fatura->despesa_total ?? 0, 2, ',', '.') }}
                                </td>

                                {{-- STATUS INTERATIVO --}}
                                <td class="px-6 py-4 text-center">

                                    @if(!$fatura->ja_foi_paga)
                                        <form 
                                            action="{{ route('cartoes.faturas.updateStatus', [$cartao, $fatura]) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button 
                                                type="submit"
                                                class="px-3 py-1 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
                                            >
                                                Marcar como Paga
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-green-600 text-white rounded-lg opacity-70 cursor-not-allowed">
                                            Paga
                                        </span>
                                    @endif

                                </td>

                                {{-- AÇÕES --}}
                                <td class="px-6 py-4 text-right space-x-3">

                                     {{-- VER COMPRAS --}}
                                    <a
                                        href="{{ route('cartoes.faturas.compras.index', [$cartao, $fatura]) }}"
                                        class="text-neutral-500 hover:text-indigo-600 transition"
                                        title="Ver compras"
                                    >
                                        🧾
                                    </a>

                                    @if(!$fatura->ja_foi_paga)
                                        <a
                                            href="{{ route('cartoes.faturas.edit', [$cartao, $fatura]) }}"
                                            class="text-neutral-500 hover:text-blue-600 transition"
                                            title="Editar"
                                        >
                                            ✏️
                                        </a>

                                        <form 
                                            action="{{ route('cartoes.faturas.destroy', [$cartao, $fatura]) }}" 
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta fatura?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Excluir"
                                                class="text-neutral-500 hover:text-red-600 transition"
                                            >
                                                🗑️
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-neutral-400 text-xs">
                                            Bloqueado
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-neutral-500">
                                    Nenhuma fatura cadastrada.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if($faturas instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="border-t border-neutral-200 dark:border-neutral-700 p-4">
                    {{ $faturas->withQueryString()->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts::app>