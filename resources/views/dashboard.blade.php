<x-layouts::app :title="__('Dashboard')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 p-2 md:p-4">

        {{-- TOPO / FILTRO DE MÊS --}}
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                Dashboard
            </h1>
        </div>

        {{-- LINHA 1: Resumo de Contas e Fluxo de Caixa --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            {{-- 1. Saldo em Contas (Ocupa 1 Coluna) --}}
            <div class="flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <h2 class="text-lg font-medium text-neutral-900 dark:text-white mb-6">Saldo em Contas</h2>
                
                <div class="flex-1 space-y-4 overflow-y-auto pr-2">
                    @forelse ($contas ?? [] as $conta)
                        <div class="flex justify-between items-center text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-2">
                            <span>{{ $conta['nome'] }}:</span>
                            <span class="font-medium">R$ {{ number_format($conta['saldo'], 2, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Nenhuma conta cadastrada.</p>
                    @endforelse
                </div>

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('contas') }}" class="px-5 py-2.5 bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 rounded-xl text-sm hover:opacity-80 transition font-medium shadow-sm">
                        Ver Contas
                    </a>
                </div>
            </div>

            {{-- 2. Receitas vs Despesas (Ocupa 2 Colunas para melhor visualização) --}}
            <div class="flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm md:col-span-2">
                <h2 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Receitas vs Despesas (Últimos 6 Meses)</h2>
                <div class="relative flex-1 w-full min-h-[250px]">
                    <canvas id="receitasDespesasChart"></canvas>
                </div>
            </div>

        </div>

        {{-- LINHA 2: Categorias e Faturas --}}
        <div class="grid gap-4 md:grid-cols-2">

            {{-- 3. Gastos por Categoria --}}
            <div class="flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <h2 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Gastos por Categoria (Últimos 6 Meses)</h2>
                <div class="relative flex-1 w-full min-h-[250px]">
                    <canvas id="categoriasChart"></canvas>
                </div>
            </div>

            {{-- 4. Total de Faturas --}}
            <div class="flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
                <h2 class="text-lg font-medium text-neutral-900 dark:text-white mb-4">Total de Faturas (Últimos 6 Meses)</h2>
                <div class="relative flex-1 w-full min-h-[250px]">
                    <canvas id="faturasChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    {{-- SCRIPTS DOS GRÁFICOS (Chart.js) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            function inicializarGraficos() {
                // Configurações Globais para combinar com Dark/Light mode
                Chart.defaults.color = '#9ca3af'; // Cor do texto (Tailwind neutral-400)
                Chart.defaults.font.family = "Inter, ui-sans-serif, system-ui, -apple-system, sans-serif";
                const gridColor = 'rgba(156, 163, 175, 0.1)'; // Linhas de grade sutis

                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: gridColor, drawBorder: false } },
                        y: { grid: { color: gridColor, drawBorder: false }, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } }
                    }
                };

                const labelsMeses = @json($labelsMeses ?? []);

                // Helper para renderizar e limpar instâncias antigas do Chart.js
                function renderChart(canvasId, config) {
                    const canvas = document.getElementById(canvasId);
                    if (!canvas) return;
                    
                    const graficoExistente = Chart.getChart(canvas);
                    if (graficoExistente) {
                        graficoExistente.destroy();
                    }
                    
                    new Chart(canvas, config);
                }

                // 1. Gráfico: Receitas vs Despesas (Linhas)
                renderChart('receitasDespesasChart', {
                    type: 'line',
                    data: {
                        labels: labelsMeses,
                        datasets: [
                            {
                                label: 'Receitas',
                                data: @json($totalReceitasUltimoSeisMeses ?? []),
                                borderColor: '#10b981', // Emerald 500
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Despesas',
                                data: @json($despesasTotaisComFaturas ?? []),
                                borderColor: '#ef4444', // Red 500
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: commonOptions
                });

                // 2. Gráfico: Gastos por Categoria (Linhas)
                renderChart('categoriasChart', {
                    type: 'line',
                    data: {
                        labels: labelsMeses,
                        datasets: @json($datasetsClassificacoes ?? [])
                    },
                    options: commonOptions
                });

                // 3. Gráfico: Total de Faturas (Barras)
                renderChart('faturasChart', {
                    type: 'bar',
                    data: {
                        labels: labelsMeses,
                        datasets: [{
                            label: 'Faturas de Cartão',
                            data: @json($totalFaturasUltimoSeisMeses ?? []),
                            backgroundColor: '#6366f1', // Indigo 500
                            borderRadius: 4
                        }]
                    },
                    options: commonOptions
                });
            }

            // Suporte para carregamento tradicional (F5 / Primeiro Acesso)
            document.addEventListener('DOMContentLoaded', inicializarGraficos);
            
            // Suporte para Livewire 3 (wire:navigate)
            document.addEventListener('livewire:navigated', inicializarGraficos);
            
            // Suporte para Turbo Drive (Hotwire)
            document.addEventListener('turbo:load', inicializarGraficos);
        })();
    </script>
</x-layouts::app>