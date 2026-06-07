<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDespesaRequest;
use App\Http\Requests\UpdateDespesaRequest;
use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $despesas = (clone $queryBase)
            ->orderBy('mes', 'desc')
            ->paginate(10)
            ->withQueryString();

        $totalPagoNoMes = (clone $queryBase)
            ->where('ja_pago', true)
            ->sum('valor');

        $totalParaPagarNoMes = (clone $queryBase)
            ->where('ja_pago', false)
            ->sum('valor');

        $quantidadeDespesasNoMes = (clone $queryBase)
            ->count();

        $classificacoes = Auth::user()->classificacoes()->get();

        return view('despesas', [
            'mes' => $mes,
            'despesas' => $despesas,
            'totalPagoNoMes' => $totalPagoNoMes,
            'totalParaPagarNoMes' => $totalParaPagarNoMes,
            'quantidadeDespesasNoMes' => $quantidadeDespesasNoMes,
            'classificacoes' => $classificacoes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contas = Auth::user()->contas;
        return view('adicionarDespesa', ['contas' => $contas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDespesaRequest $request)
    {
        DB::transaction(function () use ($request) {

            $despesa = Despesa::create([
                'nome' => $request->nome,
                'conta_id' => $request->conta_id,
                'valor' => $request->valor,
                'mes' => $request->mes,
                'recorrente' => $request->recorrente ?? false,
                'ja_pago' => $request->status === 'pago',
                'user_id' => Auth::user()->id
            ]);

            if ($request->status === 'pago') {
                $data = now()->format('Y-m-d');
                $despesa->data_pagamento = $data;
                $despesa->save();
                
                $despesa->conta->decrement('saldo', $despesa->valor);
            }

        });

        return redirect('despesas');
    }

    /**
     * Display the specified resource.
     */
    public function show(Despesa $despesa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Despesa $despesa)
    {
        if ($despesa->user_id !== Auth::user()->id) {
            abort(403);
        }

        $contas = Auth::user()->contas;
        return view('editarDespesa', ['despesa' => $despesa, 'contas' => $contas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDespesaRequest $request, Despesa $despesa)
    {
        if ($despesa->user_id !== Auth::user()->id) {
            abort(403);
        }

        if ($despesa->ja_pago) {
            return back()->with('error', 'Não é possível editar uma despesa já paga.');
        }

        $dadosAtualizados = [
            'nome' => $request->nome,
            'conta_id' => $request->conta_id,
            'valor' => $request->valor
        ];

        $despesa->update($dadosAtualizados);

        return redirect()
            ->route('despesas.index')
            ->with('success', 'Despesa atualizada com sucesso.');
    }

    public function updateStatus(Despesa $despesa)
    {
        if ($despesa->user_id !== Auth::user()->id) {
            abort(403);
        }

        if ($despesa->ja_pago) {
            return back(); // já pago, não altera
        }

        if (!$despesa->conta) {
            return back()->with('error', 'A despesa não está associada a uma conta válida.');
        }

        DB::transaction(function () use ($despesa) {

            $data = now()->format('Y-m-d');

            $despesa->update([
                'ja_pago' => true,
                'data_pagamento' => $data
            ]);

            $despesa->conta->decrement('saldo', $despesa->valor);
        });

        return back()->with('success', 'Despesa marcada como paga.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Despesa $despesa)
    {
        if ($despesa->user_id !== Auth::user()->id) {
            abort(403);
        }

        DB::transaction(function () use ($despesa) {

            if ($despesa->ja_pago && $despesa->conta) {
                $despesa->conta->increment('saldo', $despesa->valor);
            }

            $despesa->delete();
        });

        return redirect()
            ->route('despesas.index')
            ->with('success', 'Despesa removida com sucesso.');
        
    }

    public function updateClassificacao(Despesa $despesa, Request $request)
    {
        if ($despesa->user_id !== Auth::user()->id) {
            abort(403);
        }

        $request->validate([
            'classificacao_id' => 'required|exists:classificacoes,id',
        ]);

        $despesa->classificacao_id = $request->classificacao_id;
        $despesa->save();

        return back()->with('success', 'Classificação da despesa atualizada com sucesso.');
    }
}
