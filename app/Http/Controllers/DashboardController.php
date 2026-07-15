<?php

namespace App\Http\Controllers;

use App\Models\Classificacao;
use App\Models\Despesa;
use App\Models\Fatura;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
    {
        $mes = now()->format('Y-m');

        Carbon::setLocale('pt_BR'); 

        $dataFinal = Carbon::parse($mes);
        $mesesEscopo = collect();

        // 1. Esqueleto com chave=Ano-Mês e valor=Nome do Mês
        for ($i = 5; $i >= 0; $i--) {
            $data = $dataFinal->copy()->subMonths($i);
            $mesesEscopo->put($data->format('Y-m'), ucfirst($data->translatedFormat('M')));
        }

        // 2. Receitas (Últimos 6 meses)
        $receitasAgrupadas = Auth::user()
            ->receitas()
            ->whereBetween('mes', [
                $dataFinal->copy()->subMonths(5)->format('Y-m'), 
                $dataFinal->copy()->format('Y-m')
            ])
            ->selectRaw('mes as ano_mes, SUM(valor) as total')
            ->groupBy('mes')
            ->pluck('total', 'ano_mes');

        $totalReceitasUltimoSeisMeses = $mesesEscopo->map(function ($nomeMes, $anoMes) use ($receitasAgrupadas) {
            return (float) $receitasAgrupadas->get($anoMes, 0);
        })->values()->toArray(); 

        // 3. Despesas (Últimos 6 meses)
        $despesasAgrupadas = Auth::user()
            ->despesas()
            ->whereBetween('mes', [
                $dataFinal->copy()->subMonths(5)->format('Y-m'), 
                $dataFinal->copy()->format('Y-m')
            ])
            ->selectRaw('mes as ano_mes, SUM(valor) as total')
            ->groupBy('mes')
            ->pluck('total', 'ano_mes');

        $totalDespesasUltimoSeisMeses = $mesesEscopo->map(function ($nomeMes, $anoMes) use ($despesasAgrupadas) {
            return (float) $despesasAgrupadas->get($anoMes, 0);
        })->values()->toArray();

        // 4. Faturas (Últimos 6 meses)
        $faturasAgrupadas = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereBetween('mes_referencia', [
                $dataFinal->copy()->subMonths(5)->format('Y-m'), 
                $dataFinal->copy()->format('Y-m')
            ])
            ->selectRaw('mes_referencia as ano_mes, SUM(despesa_total) as total')
            ->groupBy('mes_referencia')
            ->pluck('total', 'ano_mes');

        $totalFaturasUltimoSeisMeses = $mesesEscopo->map(function ($nomeMes, $anoMes) use ($faturasAgrupadas) {
            return (float) $faturasAgrupadas->get($anoMes, 0);
        })->values()->toArray();

        // 5. Despesas por Classificação (Preparando os Datasets do Chart.js)
        $despesasBrutas = Auth::user()
            ->despesas()
            ->with('classificacao') // Carrega a relação para evitar N+1
            ->whereBetween('mes', [
                $dataFinal->copy()->subMonths(5)->format('Y-m'), 
                $dataFinal->copy()->format('Y-m')
            ])
            ->get();

        // Soma as despesas comuns com o total das faturas mês a mês
        $despesasTotaisComFaturas = array_map(function ($despesaComum, $fatura) {
            return $despesaComum + $fatura;
        }, $totalDespesasUltimoSeisMeses, $totalFaturasUltimoSeisMeses);

        // Agrupa as despesas pelo nome da classificação
        $agrupadoPorClassificacao = $despesasBrutas->groupBy(function($despesa) {
            return $despesa->classificacao->nome ?? 'Sem Categoria';
        });

        $datasetsClassificacoes = [];
        $coresPredeterminadas = ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444', '#f97316', '#06b6d4'];
        $corIndex = 0;

        foreach ($agrupadoPorClassificacao as $nomeCategoria => $despesas) {
            // Agrupa os gastos dessa categoria específica por mês
            $gastosPorMes = $despesas->groupBy('mes')->map->sum('valor');

            // Mapeia contra o nosso esqueleto de 6 meses (preenchendo vazios com 0)
            $dadosMesAMes = $mesesEscopo->map(function ($nomeMes, $anoMes) use ($gastosPorMes) {
                return (float) $gastosPorMes->get($anoMes, 0);
            })->values()->toArray();

            // Pega a cor do banco ou usa uma predeterminada
            $cor = $despesas->first()->classificacao->background_color ?? $coresPredeterminadas[$corIndex % count($coresPredeterminadas)];

            // Monta o array exatamente como o Chart.js exige
            $datasetsClassificacoes[] = [
                'label' => $nomeCategoria,
                'data' => $dadosMesAMes,
                'borderColor' => $cor,
                'borderWidth' => 2,
                'tension' => 0.3
            ];
            
            $corIndex++;
        }

        // 6. Labels do Gráfico (Eixo X)
        $labelsMeses = $mesesEscopo->values()->toArray(); 

        // 7. Contas
        $contas = Auth::user()
            ->contas()
            ->select('nome', 'saldo')
            ->orderBy('nome')
            ->get()
            ->toArray();

        return view('dashboard', compact(
            'contas',
            'labelsMeses',
            'totalReceitasUltimoSeisMeses',
            'totalDespesasUltimoSeisMeses',
            'despesasTotaisComFaturas',
            'totalFaturasUltimoSeisMeses',
            'datasetsClassificacoes'
        ));
    }

    public function visaoMes(Request $request)
    {
        $mes = $request->get('mes');

        if (!$mes) {
            $data = now();
            $mes = $data->format('Y-m');
        }

        $receitaTotalMesPrevista = Auth::user()
            ->receitas()
            ->where('mes', $mes)
            ->sum('valor');

        $despesaTotalMesPrevista = Auth::user()
            ->despesas()
            ->where('mes', $mes)
            ->sum('valor');

        $totalFaturasMesPrevista = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('mes_referencia', $mes)
            ->sum('despesa_total');

        $saldoTotalPrevistoMes = $receitaTotalMesPrevista - $despesaTotalMesPrevista - $totalFaturasMesPrevista;

        $receitaTotalMesRealizada = Auth::user()
            ->receitas()
            ->where('mes', $mes)
            ->where('ja_recebido', true)
            ->sum('valor');

        $despesaTotalMesRealizada = Auth::user()
            ->despesas()
            ->where('mes', $mes)
            ->where('ja_pago', true)
            ->sum('valor');

        $totalFaturasMesRealizada = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('mes_referencia', $mes)
            ->where('ja_foi_paga', true)
            ->sum('despesa_total');

        $saldoTotalRealizadoMes = $receitaTotalMesRealizada - $despesaTotalMesRealizada - $totalFaturasMesRealizada;

        $proximoMes = $mes ? \Carbon\Carbon::createFromFormat('Y-m', $mes)->addMonth()->format('Y-m') : null;

        $receitaTotalMesPrevistaProximoMesSemBeneficios = Receita::query()
            ->where('user_id', Auth::id())
            ->where('mes', $proximoMes)
            ->whereHas('conta', function ($query) {
                $query->where('tipo', 'CORRENTE');
            })
            ->sum('valor');
        
        $despesaTotalMesPrevistaProximoMesSemBeneficios = Auth::user()
            ->despesas()
            ->where('mes', $proximoMes)
            ->whereHas('conta', function ($query) {
                $query->where('tipo', 'CORRENTE');
            })
            ->sum('valor');

        $totalFaturasMesPrevistaProximoMes = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('mes_referencia', $proximoMes)
            ->sum('despesa_total');
        
        $saldoPrevistoProximoMesSemBeneficio = 
            $receitaTotalMesPrevistaProximoMesSemBeneficios 
            - $despesaTotalMesPrevistaProximoMesSemBeneficios 
            - $totalFaturasMesPrevistaProximoMes;

        $classificacoes = Classificacao::query()
            ->where('user_id', Auth::id())
            ->withSum(['despesas' => function ($query) use ($mes) {
                $query->where('mes', $mes);
            }], 'valor')
            ->get()
            ->map(function ($classificacao) {
                return [
                    'nome' => $classificacao->nome,
                    'total_mes' => $classificacao->despesas_sum_valor,
                    'background_color' => $classificacao->background_color
                ];
            })
            ->toArray();

        $faturas = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('mes_referencia', $proximoMes)
            ->with('cartao')
            ->get()
            ->map(function ($fatura) {
                return [
                    'nome'  => $fatura->cartao->nome,
                    'valor' => $fatura->despesa_total, 
                ];
            })
            ->toArray();

        $resumo = [
            'previsto' => [
                'receita' => $receitaTotalMesPrevista,
                'despesas' => $despesaTotalMesPrevista,
                'faturas' => $totalFaturasMesPrevista,
                'saldo' => $saldoTotalPrevistoMes,
            ],
            'realizado' => [
                'receita' => $receitaTotalMesRealizada,
                'despesas' => $despesaTotalMesRealizada,
                'faturas' => $totalFaturasMesRealizada,
                'saldo' => $saldoTotalRealizadoMes,
            ],
        ];

        $graficoBarras = [
            'receita' => $receitaTotalMesRealizada,
            'despesas' => $despesaTotalMesRealizada + $totalFaturasMesRealizada,
        ];

        return view('visaoMes', compact(
            'mes',
            'resumo',
            'saldoPrevistoProximoMesSemBeneficio',
            'graficoBarras',
            'classificacoes',
            'faturas'
        ));
    }
}


