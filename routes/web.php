<?php

use App\Http\Controllers\CartaoController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    ->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
