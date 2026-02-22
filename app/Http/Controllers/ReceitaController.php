<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceitaRequest;
use App\Http\Requests\UpdateReceitaRequest;
use App\Models\Receita;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReceitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes');

        $queryBase =  Receita::query()
            ->where('user_id', auth()->id());

        if ($mes) {
            $queryBase->where('mes', $mes);
        }else{
            $data = now();
            $mes = $data->format('Y-m');
            $queryBase->where('mes', $mes);
        }

        $receitas = (clone $queryBase)
            ->orderBy('mes', 'desc')
            ->paginate(10)
            ->withQueryString();

        $totalRecebidoNoMes = (clone $queryBase)
            ->where('ja_recebido', true)
            ->sum('valor');

        $totalParaReceberNoMes = (clone $queryBase)
            ->where('ja_recebido', false)
            ->sum('valor');

        $quantidadeReceitasNoMes = (clone $queryBase)
            ->count();

        return view('receitas', [
            'mes' => $mes,
            'receitas' => $receitas,
            'totalRecebidoNoMes' => $totalRecebidoNoMes,
            'totalParaReceberNoMes' => $totalParaReceberNoMes,
            'quantidadeReceitasNoMes' => $quantidadeReceitasNoMes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contas = auth()->user()->contas;
        return view('adicionarReceita', ['contas' => $contas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceitaRequest $request)
    {
        DB::transaction(function () use ($request) {

            $receita = Receita::create([
                'nome' => $request->nome,
                'conta_id' => $request->conta_id,
                'valor' => $request->valor,
                'mes' => $request->mes,
                'ja_recebido' => $request->status === 'recebido',
                'user_id' => auth()->user()->id
            ]);

            if ($request->status === 'recebido') {
                $receita->conta->increment('saldo', $receita->valor);
            }

        });

        return redirect('receitas');
    }

    /**
     * Display the specified resource.
     */
    public function show(Receita $receita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receita $receita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceitaRequest $request, Receita $receita)
    {
        //
    }

    public function updateStatus(Receita $receita)
    {
        if ($receita->user_id !== auth()->id()) {
            abort(403);
        }

        if ($receita->ja_recebido) {
            return back(); // já recebido, não altera
        }

        DB::transaction(function () use ($receita) {

            $data = now()->format('Y-m-d');

            $receita->update([
                'ja_recebido' => true,
                'data_recebimento' => $data
            ]);

            $receita->conta->increment('saldo', $receita->valor);
        });

        return back()->with('success', 'Receita marcada como recebida.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receita $receita)
    {
        if ($receita->user_id !== auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($receita) {

            if ($receita->ja_recebido && $receita->conta) {
                $receita->conta->decrement('saldo', $receita->valor);
            }

            $receita->delete();
        });

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita removida com sucesso.');
        }
    }
