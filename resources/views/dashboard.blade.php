<x-layouts::app :title="__('Dashboard')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                Dashboard
            </h1>

            {{-- FILTRO MÊS --}}
            {{-- FILTRO POR MÊS --}}
            <form 
                action="{{ route('dashboard') }}" 
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
        </div>


        {{-- CARDS --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">

            {{-- TOTAL GASTO --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <p class="text-sm text-neutral-500">
                    Total gasto no mês
                </p>

                <p class="text-2xl font-semibold text-red-600 mt-2">
                    R$ {{ number_format($totalGastosMes, 2, ',', '.') }}
                </p>
            </div>


            {{-- SALDO CONTAS --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <p class="text-sm text-neutral-500">
                    Saldo de todas as contas
                </p>

                <p class="text-2xl font-semibold text-green-600 mt-2">
                    R$ {{ number_format($saldoTotalContas, 2, ',', '.') }}
                </p>
            </div>


            {{-- GASTO PREVISTO --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <p class="text-sm text-neutral-500">
                    Gasto previsto do mês
                </p>

                <p class="text-2xl font-semibold text-orange-500 mt-2">
                    R$ {{ number_format($gastosPrevistosMes, 2, ',', '.') }}
                </p>
            </div>


            {{-- SALDO PREVISTO --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <p class="text-sm text-neutral-500">
                    Saldo previsto do mês
                </p>

                <p class="text-2xl font-semibold text-blue-600 mt-2">
                    R$ {{ number_format($saldoTotalContasPrevistoMes, 2, ',', '.') }}
                </p>
            </div>

        </div>


        {{-- GRAFICOS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- GRAFICO PIZZA --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

                <h2 class="text-lg font-semibold mb-4 text-neutral-900 dark:text-white">
                    Gastos por classificação
                </h2>

                <canvas id="graficoClassificacao"></canvas>

            </div>


            {{-- RESUMO --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

                <h2 class="text-lg font-semibold mb-4 text-neutral-900 dark:text-white">
                    Resumo do mês
                </h2>

                <div class="space-y-3 text-sm">

                    @foreach ($classificacoes as $classificacao)

                        <div class="flex justify-between">
                            <span class="text-neutral-500">{{ $classificacao['nome'] }}</span>
                            <span>R$ {{ number_format($classificacao['total_mes'], 2, ',', '.') }}</span>
                        </div>
                        
                    @endforeach

                </div>

            </div>

        </div>

    </div>


    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <script>
        (function () {

            const classificacoes = @json($classificacoes);

            const labels = classificacoes.map(c => c.nome);
            const valores = classificacoes.map(c => c.total_mes);
            const cores = classificacoes.map(c => c.background_color ?? '#9ca3af');

            const ctx = document.getElementById('graficoClassificacao');

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: valores,
                        backgroundColor: cores
                    }]
                }
            });

        })();
    </script>

</x-layouts::app>