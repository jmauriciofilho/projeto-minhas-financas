<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContaController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('contas', 'contas')
    ->middleware(['auth', 'verified'])
    ->name('contas');

Route::get('/contas/adicionar', [ContaController::class, 'create'])
    ->middleware(['auth', 'verified'])
    -> name('adicionar.conta');

Route::post('/contas/adicionar', [ContaController::class, 'store'])
    ->middleware(['auth', 'verified'])
    -> name('adicionar.conta');

require __DIR__.'/settings.php';
