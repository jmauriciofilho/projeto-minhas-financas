<x-layouts::app :title="__('Dashboard')">
 <div class="flex h-full w-full flex-1 flex-col gap-6">

        {{-- TOPO: TÍTULO DA DASHBOARD --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Dashboard
                </h1>
                <p class="text-xs text-neutral-500 mt-0.5">Visão geral do seu patrimônio e histórico dos últimos 6 meses</p>
            </div>
        </div>

        {{-- LINHA 1: Resumo de Contas e Histórico de Fluxo de Caixa --}}
        <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">

            {{-- 1. Saldo em Contas (1 Coluna) --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-neutral-100 dark:border-neutral-800">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                                Saldo em Contas
                            </h2>
                        </div>

                        <a href="{{ route('contas') }}" class="text-xs text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200 font-medium transition">
                            Gerenciar →
                        </a>
                    </div>

                    {{-- Lista de Contas com Rolagem Elegante --}}
                    <div class="space-y-2 mt-3 max-h-[220px] overflow-y-auto pr-1">
                        @forelse ($contas ?? [] as $conta)
                            <div class="flex justify-between items-center p-2.5 rounded-xl bg-neutral-50 dark:bg-neutral-800/40 border border-neutral-100 dark:border-neutral-800/60 text-xs sm:text-sm">
                                <div class="flex items-center gap-2 truncate pr-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
                                    <span class="font-medium text-neutral-800 dark:text-neutral-200 truncate" title="{{ $conta['nome'] }}">
                                        {{ $conta['nome'] }}
                                    </span>
                                </div>
                                <span class="font-bold tabular-nums whitespace-nowrap {{ ($conta['saldo'] ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    R$ {{ number_format($conta['saldo'], 2, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-neutral-400 dark:text-neutral-500 py-6 text-center">Nenhuma conta cadastrada.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-neutral-100 dark:border-neutral-800 flex justify-end">
                    <a href="{{ route('contas') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-neutral-900 text-white dark:bg-white dark:text-neutral-900 rounded-xl text-xs font-medium hover:bg-neutral-800 dark:hover:bg-neutral-200 transition shadow-sm">
                        <span>Ver Todas as Contas</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- 2. Receitas vs Despesas (2 Colunas em telas grandes) --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm lg:col-span-2 flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-neutral-100 dark:border-neutral-800 mb-2">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            Receitas vs Despesas
                        </h2>
                    </div>
                    <span class="text-xs text-neutral-400">Últimos 6 Meses</span>
                </div>

                <div class="relative flex-1 w-full min-h-[250px] pt-2">
                    <canvas id="receitasDespesasChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>

        {{-- LINHA 2: Categorias e Faturas --}}
        <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">

            {{-- 3. Gastos por Categoria --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-neutral-100 dark:border-neutral-800 mb-2">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            Gastos por Categoria
                        </h2>
                    </div>
                    <span class="text-xs text-neutral-400">Últimos 6 Meses</span>
                </div>

                <div class="relative flex-1 w-full min-h-[250px] pt-2">
                    <canvas id="categoriasChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- 4. Total de Faturas --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 bg-white dark:bg-neutral-900 shadow-sm flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-neutral-100 dark:border-neutral-800 mb-2">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h2 class="text-sm sm:text-base font-semibold text-neutral-900 dark:text-neutral-100">
                            Total de Faturas de Cartão
                        </h2>
                    </div>
                    <span class="text-xs text-neutral-400">Últimos 6 Meses</span>
                </div>

                <div class="relative flex-1 w-full min-h-[250px] pt-2">
                    <canvas id="faturasChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>

    </div>

   <script>
        document.addEventListener('livewire:navigated', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const canvasReceitas = document.getElementById('receitasDespesasChart');
            if (!canvasReceitas) return;

            if (typeof Chart === 'undefined') return;

            Chart.defaults.color = isDark ? '#a3a3a3' : '#737373';
            Chart.defaults.font.family = "Inter, ui-sans-serif, system-ui, -apple-system, sans-serif";
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { color: gridColor, drawBorder: false } },
                    y: { 
                        grid: { color: gridColor, drawBorder: false }, 
                        beginAtZero: true,
                        ticks: { callback: v => 'R$ ' + Number(v).toLocaleString('pt-BR') }
                    }
                },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 6, padding: 15 } }
                }
            };

            function renderChart(canvasId, config) {
                const el = document.getElementById(canvasId);
                if (!el) return;
                const existing = Chart.getChart(el);
                if (existing) existing.destroy();
                new Chart(el, config);
            }

            const labelsMeses = @json($labelsMeses ?? []);

            renderChart('receitasDespesasChart', {
                type: 'line',
                data: {
                    labels: labelsMeses,
                    datasets: [
                        {
                            label: 'Receitas',
                            data: @json($totalReceitasUltimoSeisMeses ?? []),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.35,
                            fill: true
                        },
                        {
                            label: 'Despesas',
                            data: @json($despesasTotaisComFaturas ?? []),
                            borderColor: '#f43f5e',
                            backgroundColor: 'rgba(244, 63, 94, 0.1)',
                            borderWidth: 2,
                            tension: 0.35,
                            fill: true
                        }
                    ]
                },
                options: commonOptions
            });

            renderChart('categoriasChart', {
                type: 'line',
                data: {
                    labels: labelsMeses,
                    datasets: (@json($datasetsClassificacoes ?? [])).map(d => ({ ...d, borderWidth: 2, tension: 0.35 }))
                },
                options: commonOptions
            });

            renderChart('faturasChart', {
                type: 'bar',
                data: {
                    labels: labelsMeses,
                    datasets: [{
                        label: 'Faturas de Cartão',
                        data: @json($totalFaturasUltimoSeisMeses ?? []),
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        borderRadius: 8
                    }]
                },
                options: commonOptions
            });
        });
    </script>
</x-layouts::app>