<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceitaRequest;
use App\Http\Requests\UpdateReceitaRequest;
use App\Models\Receita;

class ReceitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receitas = auth()->user()->receitas;
        return view('receitas', ['receitas' => $receitas]);
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
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receita $receita)
    {
        //
    }
}
