<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaturaRequest;
use App\Http\Requests\UpdateFaturaRequest;
use App\Models\Cartao;
use App\Models\Conta;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Cartao $cartao)
    {
        if ($cartao->user_id !== Auth::id()) {
            abort(403);
        }

        $faturas = Fatura::where('cartao_id', $cartao->id)
            ->paginate(10)
            ->withQueryString();
        return view('faturas', compact('faturas', 'cartao'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Cartao $cartao)
    {
        if ($cartao->user_id !== Auth::id()) {
            abort(403);
        }

        $contas = Auth::user()->contas;
        return view('fatura', compact('contas', 'cartao'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaturaRequest $request)
    {
        $cartao = Cartao::findOrFail($request->cartao_id);
        if ($cartao->user_id !== Auth::id()) {
            abort(403);
        }

        $conta = Conta::findOrFail($request->conta_id);
        if ($conta->user_id !== Auth::id()) {
            abort(403);
        }

        Fatura::create([
            'mes_referencia' => $request->mes_referencia,
            'data_fechamento' => $request->data_fechamento,
            'data_vencimento' => $request->data_vencimento,
            'conta_id' => $request->conta_id,
            'cartao_id' => $request->cartao_id,
        ]);

        return redirect()->route('cartoes.faturas.index', $request->cartao_id)
            ->with('success', 'Fatura criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fatura $fatura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cartao $cartao, Fatura $fatura)
    {
        if ($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id) {
            abort(403);
        }

        $contas = Auth::user()->contas;
        return view('fatura', compact('fatura', 'contas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaturaRequest $request, Cartao $cartao, Fatura $fatura)
    {
        if ($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id) {
            abort(403);
        }

        $conta = Conta::findOrFail($request->conta_id);
        if ($conta->user_id !== Auth::id()) {
            abort(403);
        }

        $fatura->update([
            'mes_referencia' => $request->mes_referencia,
            'data_fechamento' => $request->data_fechamento,
            'data_vencimento' => $request->data_vencimento,
            'conta_id' => $request->conta_id,
        ]);

        return redirect()->route('cartoes.faturas.index', $fatura->cartao_id)
            ->with('success', 'Fatura atualizada com sucesso!');
    }

    public function updateStatus(Cartao $cartao, Fatura $fatura)
    {
        if ($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id) {
            abort(403);
        }

        DB::transaction(function () use ($fatura) {

            $fatura->ja_foi_paga = true;
            $fatura->save();

            $fatura->conta()->decrement('saldo', $fatura->despesa_total);

        });

        return redirect()->back()->with('success', 'Pagamento da fatura registrado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cartao $cartao, Fatura $fatura)
    {
        if ($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id) {
            abort(403);
        }

        $fatura->delete();
        return redirect()->route('cartoes.faturas.index', $fatura->cartao_id)
            ->with('success', 'Fatura excluída com sucesso!');
    }
}
