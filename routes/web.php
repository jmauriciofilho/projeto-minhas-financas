<?php

use App\Http\Controllers\CartaoController;
use App\Http\Controllers\ClassificacaoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('visaoMes', [DashboardController::class, 'visaoMes'])
    ->middleware(['auth', 'verified'])
    ->name('visaoMes');

Route::get('/contas', [ContaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('contas');

Route::get('/contas/adicionar', [ContaController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('adicionar.conta');

Route::post('/contas/adicionar', [ContaController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('adicionar.conta');

Route::get('/contas/{conta}/editar', [ContaController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('editar.conta');

Route::put('/contas/{conta}/atualizar', [ContaController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('atualizar.conta');

Route::delete('/contas/{conta}/excluir', [ContaController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('excluir.conta');

Route::resource('receitas', ReceitaController::class)
    ->middleware(['auth', 'verified']);

Route::patch('/receitas/{receita}/recebida', [ReceitaController::class, 'updateStatus'])
    ->middleware(['auth', 'verified'])
    ->name('receitas.updateStatus');

Route::resource('despesas', DespesaController::class)
    ->middleware(['auth', 'verified']);

Route::patch('/despesas/{despesa}/paga', [DespesaController::class, 'updateStatus'])
    ->middleware(['auth', 'verified'])
    ->name('despesas.updateStatus');

Route::resource('cartoes', CartaoController::class)
    ->parameters([
        'cartoes' => 'cartao'
    ])
    ->middleware(['auth', 'verified']);

Route::resource('cartoes.faturas', FaturaController::class)
    ->parameters([
        'cartoes' => 'cartao'
    ])
    ->middleware(['auth', 'verified']);

Route::patch('/cartoes/{cartao}/faturas/{fatura}', [FaturaController::class, 'updateStatus'])
    ->middleware(['auth', 'verified'])
    ->name('cartoes.faturas.updateStatus');


Route::resource('cartoes.faturas.compras', CompraController::class)
    ->parameters([
        'cartoes' => 'cartao',
        'faturas' => 'fatura',
        'compras' => 'compra',
    ])
    ->middleware(['auth', 'verified']);

Route::resource('classificacoes', ClassificacaoController::class)
    ->parameters([
        'classificacoes' => 'classificacao'
    ])
    ->middleware(['auth', 'verified']);

Route::patch('/despesas/{despesa}/classificacao', [DespesaController::class, 'updateClassificacao'])
    ->middleware(['auth', 'verified'])
    ->name('despesas.updateClassificacao');

Route::patch('cartoes/{cartao}/faturas/{fatura}/compras/{compra}/classificacao', [CompraController::class, 'updateClassificacao'])
    ->middleware(['auth', 'verified'])
    ->name('cartoes.faturas.compras.updateClassificacao');

Route::get('/importacao', [ImportController::class, 'import'])
    ->middleware(['auth', 'verified'])
    ->name('importacao');

Route::post('/importacao/json', [ImportController::class, 'importJson'])
    ->middleware(['auth', 'verified'])
    ->name('importacao.json');

require __DIR__.'/settings.php';
