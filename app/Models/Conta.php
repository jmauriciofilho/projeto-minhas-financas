<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conta extends Model
{
    /** @use HasFactory<\Database\Factories\ContaFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        "nome",
        "saldo"
    ];

    public function receitas(): HasMany
    {
        return $this->hasMany(Receita::class);
    }

    public function despesas(): HasMany
    {
        return $this->hasMany(Despesa::class);
    }
}
