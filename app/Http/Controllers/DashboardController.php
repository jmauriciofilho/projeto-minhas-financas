<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Classificacao;
use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes');

        $classificacoes = Classificacao::query()
            ->where('user_id', Auth::id())
            ->get();

        $queryBaseDespesas =  Despesa::query()
            ->where('user_id', Auth::id());

        if ($mes) {
            $queryBaseDespesas->where('mes', $mes);
        }else{
            $data = now();
            $mes = $data->format('Y-m');
            $queryBaseDespesas->where('mes', $mes);
        }

        $receitaTotalMes = Auth::user()
            ->receitas()
            ->where('mes', $mes)
            ->sum('valor');

        $receitaTotalMesSemBeneficios = Auth::user()
            ->receitas()
            ->where('mes', $mes)
            ->whereHas('conta', function ($query) {
                $query->where('tipo', 'CORRENTE');
            })
            ->sum('valor');

        $totalGastosMes = (clone $queryBaseDespesas)
            ->where('ja_pago', true)
            ->sum('valor');

        $gastosPrevistosMes = (clone $queryBaseDespesas)
            ->where('ja_pago', false)
            ->sum('valor');

        $faturasParaPagarMes = Fatura::query()
            ->whereHas('cartao', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('mes_referencia', $mes)
            ->sum('despesa_total');

        $saldoTotalPrevistoMes = $receitaTotalMes - $gastosPrevistosMes - $faturasParaPagarMes;
        $saldoTotalPrevistoMesSemBeneficios = $receitaTotalMesSemBeneficios - $gastosPrevistosMes - $faturasParaPagarMes;

        return view('dashboard', compact('mes', 
            'saldoTotalPrevistoMes', 'saldoTotalPrevistoMesSemBeneficios', 'classificacoes'));
    }
}
