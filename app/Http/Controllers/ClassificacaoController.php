<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassificacaoRequest;
use App\Http\Requests\UpdateClassificacaoRequest;
use App\Models\Classificacao;
use Illuminate\Support\Facades\Auth;

class ClassificacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classificacoes = Auth::user()->classificacoes()->get();
        return view('classificacoes', compact('classificacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassificacaoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Classificacao $classificacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classificacao $classificacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassificacaoRequest $request, Classificacao $classificacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classificacao $classificacao)
    {
        //
    }
}
