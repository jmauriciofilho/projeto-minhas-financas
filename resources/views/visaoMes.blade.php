<x-layouts::app :title="__('Visão Mês')">

    <div class="flex h-full w-full flex-1 flex-col gap-6">

        {{-- TOPO: TÍTULO + FILTRO POR MÊS --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Visão Por Mês
                </h1>
                <p class="text-xs text-neutral-500 mt-0.5">Visão consolidada do fluxo financeiro e distribuição de despesas</p>
            </div>

            <form action="{{ route('visaoMes') }}" method="GET" class="flex items-center gap-2">
                <input 
                    type="month"
                    name="mes"
                    value="{{ $mes ?? '' }}"
                    class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400"
                >
                <button 
                    type="submit"
                    class="px-4 py-2 bg-neutral-800 text-white dark:bg-neutral-100 dark:text-neutral-900 text-sm font-medium rounded-xl hover:bg-neutral-700 dark:hover:bg-neutral-200 transition shadow-sm"
                >
                    Filtrar
                </button>
            </form>
        </div>

        {{-- BLOCO 1: PROJEÇÃO DO MÊS (MODULAR / FLUXO DE CAIXA RESPONSIVO) --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-neutral-100 dark:border-neutral-800/80">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-neutral-900 dark:text-neutral-100">
                            Projeção do Mês
                        </h2>
                        <span class="text-[11px] text-neutral-500">Cálculo estimado das contas correntes</span>
                    </div>
                </div>

                <span class="text-[11px] font-medium text-neutral-600 dark:text-neutral-400 bg-neutral-100 dark:bg-neutral-800/80 px-2.5 py-1 rounded-lg">
                    Contas Correntes
                </span>
            </div>

            {{-- Grid de métricas conectado em fluxo --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                
                {{-- 1. Saldo em Contas --}}
                <div class="p-3.5 rounded-xl bg-neutral-50 dark:bg-neutral-800/40 border border-neutral-100 dark:border-neutral-800 flex flex-col justify-between">
                    <span class="text-xs font-medium text-neutral-500">Saldo Atual</span>
                    <span class="text-base sm:text-lg font-bold mt-1 tabular-nums {{ ($projecao['saldo_contas'] ?? 0) >= 0 ? 'text-neutral-900 dark:text-neutral-100' : 'text-rose-600 dark:text-rose-400' }}">
                        R$ {{ number_format($projecao['saldo_contas'] ?? 0, 2, ',', '.') }}
                    </span>
                </div>

                {{-- 2. A Receber (+) --}}
                <div class="p-3.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-emerald-800 dark:text-emerald-400">A Receber</span>
                        <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/60 px-1.5 py-0.2 rounded">+</span>
                    </div>
                    <span class="text-base sm:text-lg font-bold mt-1 tabular-nums text-emerald-600 dark:text-emerald-400">
                        R$ {{ number_format($projecao['receitas_a_receber'] ?? 0, 2, ',', '.') }}
                    </span>
                </div>

                {{-- 3. Despesas a Pagar (-) --}}
                <div class="p-3.5 rounded-xl bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-rose-800 dark:text-rose-400">Despesas a Pagar</span>
                        <span class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/60 px-1.5 py-0.2 rounded">-</span>
                    </div>
                    <span class="text-base sm:text-lg font-bold mt-1 tabular-nums text-rose-600 dark:text-rose-400">
                        R$ {{ number_format($projecao['despesas_a_pagar'] ?? 0, 2, ',', '.') }}
                    </span>
                </div>

                {{-- 4. Faturas a Pagar (-) --}}
                <div class="p-3.5 rounded-xl bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-rose-800 dark:text-rose-400">Faturas a Pagar</span>
                        <span class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/60 px-1.5 py-0.2 rounded">-</span>
                    </div>
                    <span class="text-base sm:text-lg font-bold mt-1 tabular-nums text-rose-600 dark:text-rose-400">
                        R$ {{ number_format($projecao['faturas_a_pagar'] ?? 0, 2, ',', '.') }}
                    </span>
                </div>

                {{-- 5. Saldo Restante (=) --}}
                <div class="col-span-2 sm:col-span-1 p-3.5 rounded-xl border flex flex-col justify-between {{ ($projecao['saldo_restante'] ?? 0) >= 0 ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-rose-500/10 border-rose-500/30' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold {{ ($projecao['saldo_restante'] ?? 0) >= 0 ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">Saldo Restante</span>
                        <span class="text-[11px] font-semibold px-1.5 py-0.2 rounded {{ ($projecao['saldo_restante'] ?? 0) >= 0 ? 'bg-emerald-200 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200' : 'bg-rose-200 dark:bg-rose-900 text-rose-800 dark:text-rose-200' }}">=</span>
                    </div>
                    <span class="text-base sm:text-lg font-extrabold mt-1 tabular-nums {{ ($projecao['saldo_restante'] ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        R$ {{ number_format($projecao['saldo_restante'] ?? 0, 2, ',', '.') }}
                    </span>
                </div>

            </div>
        </div>

        {{-- BLOCO 2: CARDS COMPACTOS (SALDO PREVISTO E FATURAS CARTÕES) --}}
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
            
            {{-- Saldo Previsto Próximo Mês --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            Saldo Previsto Próximo Mês
                        </h2>
                    </div>
                    <span class="text-[11px] text-neutral-500 bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 rounded-md">
                        Sem Multibenefícios
                    </span>
                </div>

                <div class="py-6 text-center">
                    <p class="text-3xl sm:text-4xl font-extrabold tracking-tight tabular-nums {{ ($saldoPrevistoProximoMesSemBeneficio ?? 0) > 0 ? 'text-emerald-600 dark:text-emerald-400' : (($saldoPrevistoProximoMesSemBeneficio ?? 0) < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-amber-500 dark:text-amber-400') }}">
                        R$ {{ number_format($saldoPrevistoProximoMesSemBeneficio ?? 0, 2, ',', '.') }}
                    </p>
                    <span class="text-xs text-neutral-400 mt-1 block">Estimativa líquida para o próximo período</span>
                </div>
            </div>

            {{-- Faturas Cartões Próximo Mês --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            Faturas de Cartões
                        </h2>
                    </div>

                    <a href="{{ route('cartoes.index') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-medium inline-flex items-center gap-1">
                        Ver Cartões
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                <div class="flex-1 space-y-2 max-h-[140px] overflow-y-auto pr-1 my-3 divide-y divide-neutral-100 dark:divide-neutral-800/60">
                    @forelse ($faturas ?? [] as $fatura)
                        <div class="flex justify-between items-center text-xs sm:text-sm pt-2 first:pt-0">
                            <div class="flex items-center gap-2 truncate pr-2">
                                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                <span class="text-neutral-800 dark:text-neutral-200 font-medium truncate" title="{{ $fatura['nome'] }}">
                                    {{ $fatura['nome'] }}
                                </span>
                            </div>
                            <span class="font-bold text-rose-600 dark:text-rose-400 tabular-nums whitespace-nowrap">
                                R$ {{ number_format($fatura['valor'], 2, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-neutral-400 dark:text-neutral-500">
                            Nenhuma fatura com compras para este mês.
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>

        {{-- BLOCO 3: GRÁFICOS (BARRAS + ROSCA DE GASTOS) --}}
        <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">

            {{-- Gráfico Barras (Receita x Despesas) --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col">
                <div class="flex items-center justify-between pb-3 mb-2 border-b border-neutral-100 dark:border-neutral-800">
                    <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                        Comparativo: Receitas x Despesas
                    </h2>
                    <span class="text-xs text-neutral-400">Total apurado</span>
                </div>
                <div class="relative flex-1 w-full min-h-[240px] sm:min-h-[260px] flex items-center justify-center pt-2">
                    <canvas id="graficoBarras" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Gráfico Doughnut (Gastos por Área) --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col">
                <div class="flex items-center justify-between pb-3 mb-2 border-b border-neutral-100 dark:border-neutral-800">
                    <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                        Gastos por Área
                    </h2>
                    <span class="text-xs text-neutral-400">Distribuição mensal</span>
                </div>
                
                <div class="flex-1 w-full flex flex-col sm:flex-row items-center gap-4 min-h-[260px] pt-2">
                    {{-- O GRÁFICO DE ROSCA --}}
                    <div class="w-full sm:w-1/2 h-[200px] sm:h-full relative flex items-center justify-center">
                        <canvas id="graficoPizza"></canvas>
                    </div>

                    {{-- LEGENDA DOS GASTOS --}}
                    <div class="w-full sm:w-1/2 max-h-[200px] sm:max-h-full overflow-y-auto space-y-2 pr-1">
                        @forelse ($classificacoes ?? [] as $c)
                            <div class="flex items-center justify-between p-2 rounded-xl bg-neutral-50 dark:bg-neutral-800/40 border border-neutral-100 dark:border-neutral-800/60 text-xs">
                                <div class="flex items-center gap-2 truncate pr-2">
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $c['background_color'] ?? '#9ca3af' }}"></span>
                                    <span class="truncate font-medium text-neutral-800 dark:text-neutral-200" title="{{ $c['nome'] }}">
                                        {{ $c['nome'] }}
                                    </span>
                                </div>
                                <span class="font-semibold tabular-nums text-neutral-900 dark:text-neutral-100 whitespace-nowrap">
                                    R$ {{ number_format($c['total_mes'] ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-center text-neutral-400 py-6">Nenhum gasto classificado.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('livewire:navigated', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const canvasBarras = document.getElementById('graficoBarras');
            if (!canvasBarras) return;

            if (typeof Chart === 'undefined') return;

            Chart.defaults.color = isDark ? '#a3a3a3' : '#737373';
            Chart.defaults.font.family = "Inter, ui-sans-serif, system-ui, -apple-system, sans-serif";

            const dadosBarras = @json($graficoBarras ?? ['receita' => 0, 'despesas' => 0]);
            const graficoBarrasExistente = Chart.getChart(canvasBarras);
            if (graficoBarrasExistente) graficoBarrasExistente.destroy();

            new Chart(canvasBarras, {
                type: 'bar',
                data: {
                    labels: ['Receitas', 'Despesas'],
                    datasets: [{
                        label: 'Total (R$)',
                        data: [dadosBarras.receita, dadosBarras.despesas],
                        backgroundColor: ['rgba(16, 185, 129, 0.85)', 'rgba(244, 63, 94, 0.85)'],
                        borderWidth: 1.5,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: { callback: v => 'R$ ' + Number(v).toLocaleString('pt-BR') }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            const canvasPizza = document.getElementById('graficoPizza');
            const classificacoes = @json($classificacoes ?? []);
            if (canvasPizza && classificacoes.length > 0) {
                const graficoPizzaExistente = Chart.getChart(canvasPizza);
                if (graficoPizzaExistente) graficoPizzaExistente.destroy();

                new Chart(canvasPizza, {
                    type: 'doughnut',
                    data: {
                        labels: classificacoes.map(c => c.nome),
                        datasets: [{
                            data: classificacoes.map(c => c.total_mes),
                            backgroundColor: classificacoes.map(c => c.background_color ?? '#9ca3af'),
                            borderWidth: 2,
                            borderColor: isDark ? '#171717' : '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>

</x-layouts::app>