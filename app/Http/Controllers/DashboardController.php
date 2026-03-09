<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes');

        $queryBase =  Despesa::query()
            ->where('user_id', Auth::id());

        if ($mes) {
            $queryBase->where('mes', $mes);
        }else{
            $data = now();
            $mes = $data->format('Y-m');
            $queryBase->where('mes', $mes);
        }

        $totalGastosMes = (clone $queryBase)
            ->where('ja_pago', true)
            ->sum('valor');

        $gastosPrevistosMes = (clone $queryBase)
            ->where('ja_pago', false)
            ->sum('valor');

        $saldoTotalContas = Auth::user()->contas()->sum('saldo');

        $saldoTotalContasPrevistoMes = $saldoTotalContas - $gastosPrevistosMes;

        $classificacoes = Auth::user()
            ->classificacoes()
            ->withSum(['despesas as total_mes' => function ($query) use ($mes) {
                $query->where('mes', $mes);
            }], 'valor')
            ->get()
            ->map(function ($classificacao) {
                $classificacao->total_mes = $classificacao->total_mes ?? 0;
                return $classificacao;
            })
            ->mergeHidden(['user_id', 'created_at', 'updated_at'])
            ->toArray();

        return view('dashboard', compact('totalGastosMes', 'gastosPrevistosMes', 
            'saldoTotalContas', 'saldoTotalContasPrevistoMes', 'classificacoes', 'mes'));
    }
}
