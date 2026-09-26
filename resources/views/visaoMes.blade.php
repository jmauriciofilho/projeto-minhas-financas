<x-layouts::app :title="__('Visão Mês')">

    {{-- CARREGA O CHART.JS DO JEITO CERTO NO LIVEWIRE --}}
    @assets
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    <div class="flex h-full w-full flex-1 flex-col gap-6 p-2 md:p-4">

        {{-- TOPO / FILTRO DE MÊS ORIGINAL --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-white">
                Visão Por Mês
            </h1>

            <form 
                action="{{ route('visaoMes') }}" 
                method="GET"
                class="flex items-center gap-2"
            >
                <input 
                    type="month"
                    name="mes"
                    value="{{ $mes ?? '' }}"
                    class="w-full sm:w-auto rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-neutral-900 dark:text-white"
                >
                <button 
                    type="submit"
                    class="px-4 py-2 bg-neutral-800 text-white text-sm rounded-lg hover:bg-neutral-700 transition shadow-sm whitespace-nowrap cursor-pointer"
                >
                    Filtrar
                </button>
            </form>
        </div>

        {{-- LINHA 1 --}}
        <div class="grid auto-rows-min gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
            
            {{-- Tabela Previsto/Realizado --}}
            <div class="sm:col-span-2 xl:col-span-1 flex flex-col justify-between rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 sm:p-6 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-center text-xs sm:text-sm min-w-[340px] sm:min-w-full">
                        <thead>
                            <tr class="text-neutral-500 dark:text-neutral-400 border-b border-neutral-100 dark:border-neutral-800 text-[11px] sm:text-xs uppercase tracking-wider">
                                <th class="pb-3 text-left font-semibold">Tipo</th>
                                <th class="pb-3 px-1.5 sm:px-2 font-semibold whitespace-nowrap">Receita</th>
                                <th class="pb-3 px-1.5 sm:px-2 font-semibold whitespace-nowrap">Despesas</th>
                                <th class="pb-3 px-1.5 sm:px-2 font-semibold whitespace-nowrap">Faturas</th>
                                <th class="pb-3 px-1.5 sm:px-2 font-semibold whitespace-nowrap">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800 text-neutral-900 dark:text-white">
                            {{-- LINHA PREVISTO --}}
                            <tr>
                                <td class="py-3 sm:py-3.5 text-left font-medium whitespace-nowrap">Previsto</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 whitespace-nowrap">R$ {{ number_format($resumo['previsto']['receita'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 text-red-500 whitespace-nowrap">R$ {{ number_format($resumo['previsto']['despesas'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 text-red-500 whitespace-nowrap">R$ {{ number_format($resumo['previsto']['faturas'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 font-semibold whitespace-nowrap {{ ($resumo['previsto']['saldo'] ?? 0) > 0 ? 'text-green-600 dark:text-green-400' : (($resumo['previsto']['saldo'] ?? 0) < 0 ? 'text-red-500 dark:text-red-400' : 'text-amber-500 dark:text-amber-400') }}">
                                    R$ {{ number_format($resumo['previsto']['saldo'] ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                            {{-- LINHA REALIZADO --}}
                            <tr>
                                <td class="py-3 sm:py-3.5 text-left font-medium whitespace-nowrap">Realizado</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 whitespace-nowrap">R$ {{ number_format($resumo['realizado']['receita'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 text-red-500 whitespace-nowrap">R$ {{ number_format($resumo['realizado']['despesas'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 text-red-500 whitespace-nowrap">R$ {{ number_format($resumo['realizado']['faturas'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-3 sm:py-3.5 px-1.5 sm:px-2 font-semibold whitespace-nowrap {{ ($resumo['realizado']['saldo'] ?? 0) > 0 ? 'text-green-600 dark:text-green-400' : (($resumo['realizado']['saldo'] ?? 0) < 0 ? 'text-red-500 dark:text-red-400' : 'text-amber-500 dark:text-amber-400') }}">
                                    R$ {{ number_format($resumo['realizado']['saldo'] ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Saldo Previsto --}}
            <div class="col-span-1 flex flex-col items-center justify-center text-center rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 sm:p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <h2 class="text-base sm:text-lg font-medium text-neutral-900 dark:text-white">
                    Saldo Previsto Próximo Mês<br>
                    <span class="text-xs sm:text-sm font-normal text-neutral-500">(Sem Multibenefícios)</span>
                </h2>
                <p class="text-3xl sm:text-4xl xl:text-5xl font-extrabold mt-4 sm:mt-6 tracking-tight whitespace-nowrap {{ ($saldoPrevistoProximoMesSemBeneficio ?? 0) > 0 ? 'text-green-600 dark:text-green-400' : (($saldoPrevistoProximoMesSemBeneficio ?? 0) < 0 ? 'text-red-500 dark:text-red-400' : 'text-amber-500 dark:text-amber-400') }}">
                    R$ {{ number_format($saldoPrevistoProximoMesSemBeneficio ?? 0, 2, ',', '.') }}
                </p>
            </div>

            {{-- Faturas Cartões --}}
            <div class="col-span-1 flex flex-col justify-between rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 sm:p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <h2 class="text-center text-base sm:text-lg font-medium text-neutral-900 dark:text-white mb-4 sm:mb-6">Faturas Cartões Próximos Mês</h2>
                
                <div class="flex-1 space-y-3 sm:space-y-4 max-h-[180px] overflow-y-auto pr-2">
                    @forelse ($faturas ?? [] as $fatura)
                        <div class="flex justify-between items-center text-xs sm:text-sm text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-2">
                            <span class="truncate pr-2" title="{{ $fatura['nome'] }}">{{ $fatura['nome'] }}:</span>
                            <span class="font-medium text-red-500 whitespace-nowrap">R$ {{ number_format($fatura['valor'], 2, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">Nenhuma fatura cadastrada.</p>
                    @endforelse
                </div>

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('cartoes.index') }}" class="px-4 sm:px-5 py-2 sm:py-2.5 bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 rounded-xl text-xs sm:text-sm hover:opacity-80 transition font-medium shadow-sm whitespace-nowrap">
                        Ver Cartões
                    </a>
                </div>
            </div>
            
        </div>

        {{-- LINHA 2 --}}
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">

            {{-- Gráfico Barras --}}
            <div class="flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 sm:p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <div class="relative flex-1 w-full min-h-[220px] sm:min-h-[250px] flex items-center justify-center">
                    <canvas id="graficoBarras" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Gráfico Pizza e Legenda --}}
            <div class="flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 sm:p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <h2 class="text-center text-base sm:text-lg font-medium text-neutral-900 dark:text-white mb-4">Gastos por área</h2>
                
                <div class="flex-1 w-full flex flex-col sm:flex-row items-center gap-4 sm:gap-0 overflow-hidden min-h-[260px] sm:min-h-[250px]">
                    
                    {{-- O GRÁFICO --}}
                    <div class="w-full sm:w-1/2 h-[180px] sm:h-full relative flex items-center justify-center pb-3 sm:pb-0 sm:pr-4 sm:border-r border-b sm:border-b-0 border-neutral-100 dark:border-neutral-800">
                        <canvas id="graficoPizza"></canvas>
                    </div>

                    {{-- LEGENDA HTML --}}
                    <div class="w-full sm:w-1/2 max-h-[140px] sm:max-h-full overflow-y-auto flex flex-col gap-2.5 sm:gap-3 pt-2 sm:pt-0 sm:pl-4">
                        @foreach ($classificacoes ?? [] as $c)
                            <div class="flex items-center gap-2.5 sm:gap-3 text-xs sm:text-sm text-neutral-700 dark:text-neutral-300">
                                <span class="w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full flex-shrink-0" style="background-color: {{ $c['background_color'] ?? '#9ca3af' }}"></span>
                                <span class="truncate font-medium" title="{{ $c['nome'] }}">{{ $c['nome'] }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        (function () {
            function inicializarGraficos() {
                // Configuração Global para combinar com o tema
                Chart.defaults.color = '#9ca3af';
                Chart.defaults.font.family = "Inter, ui-sans-serif, system-ui, -apple-system, sans-serif";

                // --- GRÁFICO DE BARRAS (Receita x Despesas) ---
                const dadosBarras = @json($graficoBarras ?? ['receita' => 0, 'despesas' => 0]);
                const canvasBarras = document.getElementById('graficoBarras');
                
                if (canvasBarras) {
                    const graficoExistente = Chart.getChart(canvasBarras);
                    if (graficoExistente) graficoExistente.destroy();

                    new Chart(canvasBarras, {
                        type: 'bar',
                        data: {
                            labels: ['Receita', 'Despesas'],
                            datasets: [{
                                label: 'Valor',
                                data: [dadosBarras.receita, dadosBarras.despesas],
                                backgroundColor: ['rgba(74, 222, 128, 0.8)', 'rgba(248, 113, 113, 0.8)'], // Ajustado para leve transparência
                                borderColor: ['#16a34a', '#dc2626'],
                                borderWidth: 1,
                                barPercentage: 0.5,
                                borderRadius: 4 // Arredondamento nas pontas para ficar elegante
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { 
                                    beginAtZero: true,
                                    grid: { color: 'rgba(156, 163, 175, 0.1)', drawBorder: false }
                                },
                                x: { 
                                    grid: { display: false, drawBorder: false } 
                                }
                            }
                        }
                    });
                }

                // --- GRÁFICO DE PIZZA (Gastos por Área) ---
                const classificacoes = @json($classificacoes ?? []);
                const canvasPizza = document.getElementById('graficoPizza');

                if (canvasPizza && classificacoes.length > 0) {
                    const graficoExistente = Chart.getChart(canvasPizza);
                    if (graficoExistente) graficoExistente.destroy();

                    new Chart(canvasPizza, {
                        type: 'doughnut', // Alterado de 'pie' para 'doughnut' (rosca) por ser mais moderno e elegante, mas sem alterar os dados
                        data: {
                            labels: classificacoes.map(c => c.nome),
                            datasets: [{
                                data: classificacoes.map(c => c.total_mes),
                                backgroundColor: classificacoes.map(c => c.background_color ?? '#9ca3af'),
                                borderWidth: 2,
                                borderColor: document.documentElement.classList.contains('dark') ? '#171717' : '#ffffff', // Borda dinâmica dark/light
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%', // Tamanho do furo no meio
                            layout: { padding: 10 },
                            plugins: { legend: { display: false } }
                        }
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', inicializarGraficos);
            document.addEventListener('livewire:navigated', inicializarGraficos);
            document.addEventListener('turbo:load', inicializarGraficos);
        })();
    </script>

</x-layouts::app>