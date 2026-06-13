<x-layouts::app :title="__('Dashboard')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 p-2">

        {{-- TOPO / FILTRO DE MÊS ORIGINAL --}}
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                Dashboard
            </h1>

            <form 
                action="{{ route('dashboard') }}" 
                method="GET"
                class="flex items-center gap-2"
            >
                <input 
                    type="month"
                    name="mes"
                    value="{{ $mes ?? '' }}"
                    class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-neutral-900 dark:text-white"
                >
                <button 
                    type="submit"
                    class="px-4 py-2 bg-neutral-800 text-white text-sm rounded-lg hover:bg-neutral-700 transition"
                >
                    Filtrar
                </button>
            </form>
        </div>

        {{-- LINHA 1 --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <table class="w-full text-center">
                    <thead>
                        <tr class="text-neutral-700 dark:text-neutral-300">
                            <th class="pb-4 font-medium text-left"></th>
                            <th class="pb-4 font-medium">Receita</th>
                            <th class="pb-4 font-medium">Despesas</th>
                            <th class="pb-4 font-medium">Total Faturas</th>
                            <th class="pb-4 font-medium">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="text-neutral-900 dark:text-white">
                        {{-- LINHA PREVISTO --}}
                        <tr class="border-b border-neutral-100 dark:border-neutral-800">
                            <td class="py-4 text-left font-medium">Previsto</td>
                            <td class="py-4">R$ {{ number_format($resumo['previsto']['receita'] ?? 0, 2, ',', '.') }}</td>
                            <td class="py-4 text-red-500">R$ {{ number_format($resumo['previsto']['despesas'] ?? 0, 2, ',', '.') }}</td>
                            <td class="py-4 text-red-500">R$ {{ number_format($resumo['previsto']['faturas'] ?? 0, 2, ',', '.') }}</td>
                            <td class="py-4 font-semibold {{ ($resumo['previsto']['saldo'] ?? 0) >= 0 ? 'text-sky-500' : 'text-red-500' }}">
                                R$ {{ number_format($resumo['previsto']['saldo'] ?? 0, 2, ',', '.') }}
                            </td>
                        </tr>
                        {{-- LINHA REALIZADO --}}
                        <tr>
                            <td class="py-4 text-left font-medium">Realizado</td>
                            <td class="py-4">R$ {{ number_format($resumo['realizado']['receita'] ?? 0, 2, ',', '.') }}</td>
                            <td class="py-4 text-red-500">R$ {{ number_format($resumo['realizado']['despesas'] ?? 0, 2, ',', '.') }}</td>
                            <td class="py-4 text-red-500">R$ {{ number_format($resumo['realizado']['faturas'] ?? 0, 2, ',', '.') }}</td>
                            <td class="py-4 font-semibold {{ ($resumo['realizado']['saldo'] ?? 0) >= 0 ? 'text-sky-500' : 'text-red-500' }}">
                                R$ {{ number_format($resumo['realizado']['saldo'] ?? 0, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white items-center justify-center text-center dark:bg-neutral-900">
                <h2 class="text-lg font-medium text-neutral-900 dark:text-white">
                    Saldo Previsto Próximo Mês<br>
                    <span class="text-sm font-normal text-neutral-500">(Sem Multibenefícios)</span>
                </h2>
                <p class="text-3xl font-bold mt-6 text-neutral-900 dark:text-white">
                    R$ {{ number_format($saldoPrevistoProximoMesSemBeneficio ?? 0, 2, ',', '.') }}
                </p>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <h2 class="text-center text-lg font-medium text-neutral-900 dark:text-white mb-6">Faturas Cartões Próximos Mês</h2>
                
                <div class="flex-1 space-y-4 overflow-y-auto pr-2">
                    @foreach ($faturas ?? [] as $fatura)
                        <div class="flex justify-between items-center text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-2">
                            <span>{{ $fatura['nome'] }}:</span>
                            <span class="font-medium text-red-500">R$ {{ number_format($fatura['valor'], 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('cartoes.index') }}" class="px-5 py-2.5 bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 rounded-xl text-sm hover:opacity-80 transition font-medium shadow-sm">
                        Ver Cartões
                    </a>
                </div>
            </div>
            
        </div>


        {{-- LINHA 2 --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <canvas id="graficoBarras" class="w-full h-full"></canvas>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <h2 class="text-center text-lg font-medium text-neutral-900 dark:text-white mb-4">Gastos por área</h2>
                
                <div class="flex-1 w-full flex flex-row items-center overflow-hidden">
                    
                    {{-- O GRÁFICO (Movido para a esquerda) --}}
                    <div class="w-1/2 h-full relative flex items-center justify-center pr-4 border-r border-neutral-100 dark:border-neutral-800">
                        <canvas id="graficoPizza"></canvas>
                    </div>

                    {{-- LEGENDA HTML (Movida para a direita) --}}
                    <div class="w-1/2 max-h-full overflow-y-auto flex flex-col gap-3 pl-4">
                        @foreach ($classificacoes ?? [] as $c)
                            <div class="flex items-center gap-3 text-sm text-neutral-700 dark:text-neutral-300">
                                {{-- Bolinha da cor da classificação --}}
                                <span class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $c['background_color'] ?? '#9ca3af' }}"></span>
                                {{-- Nome da classificação --}}
                                <span class="truncate font-medium" title="{{ $c['nome'] }}">{{ $c['nome'] }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
                <h2 class="text-center text-lg font-medium text-neutral-900 dark:text-white mb-6">Saldo em Contas</h2>
                
                <div class="flex-1 space-y-4 overflow-y-auto pr-2">
                    @foreach ($contas ?? [] as $conta)
                        <div class="flex justify-between items-center text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-2">
                            <span>{{ $conta['nome'] }}:</span>
                            <span class="font-medium">R$ {{ number_format($conta['saldo'], 2, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('cartoes.index') }}" class="px-5 py-2.5 bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 rounded-xl text-sm hover:opacity-80 transition font-medium shadow-sm">
                        Ver Contas
                    </a>
                </div>
            </div>

        </div>

    </div>

    {{-- SCRIPTS CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- GRÁFICO DE BARRAS (Receita x Despesas) ---
            const dadosBarras = @json($graficoBarras ?? ['receita' => 0, 'despesas' => 0]);
            const ctxBarras = document.getElementById('graficoBarras')?.getContext('2d');
            
            if (ctxBarras) {
                new Chart(ctxBarras, {
                    type: 'bar',
                    data: {
                        labels: ['Receita', 'Despesas'],
                        datasets: [{
                            label: 'Valor',
                            data: [dadosBarras.receita, dadosBarras.despesas],
                            backgroundColor: ['#4ade80', '#f87171'], // Verde e Vermelho
                            borderColor: ['#16a34a', '#dc2626'],
                            borderWidth: 1,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                title: { display: true, text: 'valor', align: 'end' } 
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // --- GRÁFICO DE PIZZA (Gastos por Área) ---
            const classificacoes = @json($classificacoes ?? []);
            const ctxPizza = document.getElementById('graficoPizza')?.getContext('2d');

            if (ctxPizza && classificacoes.length > 0) {
                new Chart(ctxPizza, {
                    type: 'pie',
                    data: {
                        labels: classificacoes.map(c => c.nome),
                        datasets: [{
                            data: classificacoes.map(c => c.total_mes),
                            backgroundColor: classificacoes.map(c => c.background_color ?? '#9ca3af'),
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: 10
                        },
                        plugins: {
                            // Legenda nativa DESATIVADA. Estamos usando a legenda em HTML.
                            legend: {
                                display: false 
                            }
                        }
                    }
                });
            }

        });
    </script>

</x-layouts::app>