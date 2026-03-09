<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompraRequest;
use App\Http\Requests\UpdateCompraRequest;
use App\Models\Cartao;
use App\Models\Compra;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Cartao $cartao, Fatura $fatura)
    {
        $compras = Auth::user()->cartaos()->find($cartao->id)->faturas()->find($fatura->id)->compras()
            ->paginate(10)
            ->withQueryString();
        $cartao = Auth::user()->cartaos()->find($cartao->id);
        $fatura = $cartao->faturas()->find($fatura->id);
        $classificacoes = Auth::user()->classificacoes()->get();

        return view('compras', compact('compras', 'cartao', 'fatura', 'classificacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Cartao $cartao, Fatura $fatura)
    {
        if($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id) {
            abort(403);
        }

        return view('compra', compact('cartao', 'fatura'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request, Cartao $cartao, Fatura $fatura)
    {
        if($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id) {
            abort(403);
        }

        DB::transaction(function () use ($request, $fatura) {

            Compra::create([
                'descricao' => $request->descricao,
                'data_compra' => $request->data_compra,
                'valor' => $request->valor,
                'total_parcelas' => $request->total_parcelas,
                'numero_parcela' => $request->numero_parcela,
                'fatura_id' => $fatura->id,
            ]);

            $fatura->increment('despesa_total', $request->valor);

        });

        return redirect()->route('cartoes.faturas.compras.index', ['cartao' => $cartao->id, 'fatura' => $fatura->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompraRequest $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cartao $cartao, Fatura $fatura, Compra $compra)
    {
        if($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id || $compra->fatura_id !== $fatura->id) {
            abort(403);
        }

        DB::transaction(function () use ($compra, $fatura) {
            $fatura->decrement('despesa_total', $compra->valor);
            $compra->delete();
        });

        return redirect()->route('cartoes.faturas.compras.index', ['cartao' => $cartao->id, 'fatura' => $fatura->id]);
    }

    public function updateClassificacao(Cartao $cartao, Fatura $fatura, Compra $compra)
    {
        if($cartao->user_id !== Auth::id() || $fatura->cartao_id !== $cartao->id || $compra->fatura_id !== $fatura->id) {
            abort(403);
        }

        $compra->classificacao_id = request('classificacao_id');
        $compra->save();

        return back()->with('success', 'Classificação da compra atualizada com sucesso.');
    }
}
