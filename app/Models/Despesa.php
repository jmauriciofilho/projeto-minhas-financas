<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despesa extends Model
{
    /** @use HasFactory<\Database\Factories\DespesaFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        "nome",
        "valor",
        "mes",
        "recorrente",
        "ja_pago",
        "data_pagamento",
        "conta_id",
        "user_id",
        "classificacao_id"
    ];

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class);
    }

    public function classificacao(): BelongsTo
    {
        return $this->belongsTo(Classificacao::class);
    }
}
