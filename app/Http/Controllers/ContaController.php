<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContaRequest;
use App\Http\Requests\UpdateContaRequest;
use App\Models\Conta;

class ContaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contas = auth()->user()->contas;
        return view('contas', ['contas' => $contas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adicionarContas');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContaRequest $request)
    {
        $conta = new Conta;

        $conta->nome = $request->nome;
        $conta->saldo = $request->saldo;
        $conta->user_id = auth()->user()->id;

        $conta->save();

        return redirect('/contas');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conta $conta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conta $conta)
    {
        if ($conta->user_id !== auth()->id()) {
            abort(403);
        }

        return view('editarContas', ['conta' => $conta]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContaRequest $request, Conta $conta)
    {
        if ($conta->user_id !== auth()->id()) {
            abort(403);
        }

        $conta->nome = $request->nome;

        $conta->save();

        return redirect('/contas')
            ->with('success', 'Conta editada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conta $conta)
    {
        if ($conta->user_id !== auth()->id()) {
            abort(403);
        }
    
        $conta->delete();

        return redirect('/contas')
            ->with('success', 'Conta removida com sucesso.');
    }
}
