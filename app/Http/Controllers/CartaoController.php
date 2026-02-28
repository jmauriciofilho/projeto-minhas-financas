<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartaoRequest;
use App\Http\Requests\UpdateCartaoRequest;
use App\Models\Cartao;
use Illuminate\Support\Facades\Auth;

class CartaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartoes = Auth::user()->cartaos;
        return view('cartoes', compact('cartoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cartao');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartaoRequest $request)
    {
        Cartao::create([
            'nome' => $request->nome,
            'final_cartao' => $request->final_cartao,
            'user_id' => Auth::user()->id
        ]);

        return redirect('/cartoes');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cartao $cartao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cartao $cartao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartaoRequest $request, Cartao $cartao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cartao $cartao)
    {
        //
    }
}
