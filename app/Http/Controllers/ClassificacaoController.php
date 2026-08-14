<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassificacaoRequest;
use App\Http\Requests\UpdateClassificacaoRequest;
use App\Models\Classificacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassificacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classificacoes = Auth::user()->classificacoes()
            ->paginate(10)
            ->withQueryString();
        return view('classificacoes', compact('classificacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classificacao');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassificacaoRequest $request)
    {
        $classificacao = new Classificacao();
        $classificacao->fill($request->validated());
        $classificacao->user_id = Auth::id();
        $classificacao->slug = \Str::slug($request->nome);
        $classificacao->save();

        return redirect()->route('classificacoes.index')->with('success', 'Classificação criada com sucesso!');
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
        Gate::authorize('view', $classificacao);

        return view('classificacao', compact('classificacao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassificacaoRequest $request, Classificacao $classificacao)
    {
        Gate::authorize('update', $classificacao);

        $classificacao->fill($request->validated());
        $classificacao->save();

        return redirect()->route('classificacoes.index')->with('success', 'Classificação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classificacao $classificacao)
    {
        Gate::authorize('delete', $classificacao);

        $classificacao->delete();

        return redirect()->route('classificacoes.index')->with('success', 'Classificação excluída com sucesso!');
    }
}
