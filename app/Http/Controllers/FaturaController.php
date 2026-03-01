<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaturaRequest;
use App\Http\Requests\UpdateFaturaRequest;
use App\Models\Conta;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;

class FaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Conta $conta)
    {
        $faturas = Auth::user()->contas()
            ->where('id', $conta->id)
            ->firstOrFail()
            ->faturas;
        return view('faturas', compact('faturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fatura');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaturaRequest $request)
    {
        //
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
    public function edit(Fatura $fatura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaturaRequest $request, Fatura $fatura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fatura $fatura)
    {
        //
    }
}
