<?php

namespace App\Http\Controllers;

use App\Models\Classificacao;
use App\Models\Despesa;
use App\Models\Fatura;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
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
        
        $despesaTotalMesPrevistaProximoMes = Auth::user()
            ->despesas()
            ->where('mes', $proximoMes)
            ->sum('valor');

        $totalFaturasMesPrevistaProximoMes = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('mes_referencia', $proximoMes)
            ->sum('despesa_total');
        
        $saldoPrevistoProximoMesSemBeneficio = 
            $receitaTotalMesPrevistaProximoMesSemBeneficios 
            - $despesaTotalMesPrevistaProximoMes 
            - $totalFaturasMesPrevistaProximoMes;

        $contas = Auth::user()
            ->contas()
            ->select('nome', 'saldo')
            ->orderBy('nome')
            ->get()
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

        return view('dashboard', compact(
            'mes',
            'resumo',
            'saldoPrevistoProximoMesSemBeneficio',
            'graficoBarras',
            'classificacoes',
            'contas',
            'faturas'
        ));
    }
}
