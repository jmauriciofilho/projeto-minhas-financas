<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReceitaRequest;
use App\Http\Requests\UpdateReceitaRequest;

class ReceitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes');

        $queryBase =  Receita::query()
            ->where('user_id', Auth::id());

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
        $contas = Auth::user()->contas;
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
                'user_id' => Auth::user()->id
            ]);

            if ($request->status === 'recebido') {
                $data = now()->format('Y-m-d');
                $receita->data_recebimento = $data;
                $receita->save();

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
        if ($receita->user_id !== Auth::user()->id) {
            abort(403);
        }

        $contas = Auth::user()->contas;

        return view('editarReceita', ['receita' => $receita, 'contas' => $contas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceitaRequest $request, Receita $receita)
    {
        if ($receita->user_id !== Auth::user()->id) {
            abort(403);
        }

        if ($receita->ja_recebido) {
            return back()->with('error', 'Não é possível editar uma receita já recebida.'); // já recebido, não altera
        }

        DB::transaction(function () use ($request, $receita) {

            $dadosAtualizados = [
                'nome' => $request->nome,
                'conta_id' => $request->conta_id,
                'valor' => $request->valor
            ];

            $receita->update($dadosAtualizados);
        });

        return redirect()
            ->route('receitas.index')
            ->with('success', 'Receita atualizada com sucesso.');
    }

    public function updateStatus(Receita $receita)
    {
        if ($receita->user_id !== Auth::user()->id) {
            abort(403);
        }

        if ($receita->ja_recebido) {
            return back(); // já recebido, não altera
        }

        if (!$receita->conta) {
            return back()->with('error', 'A receita não está associada a uma conta válida.');
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
        if ($receita->user_id !== Auth::user()->id) {
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
