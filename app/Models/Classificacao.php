<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classificacao extends Model
{
    /** @use HasFactory<\Database\Factories\ClassificacaoFactory> */
    use HasFactory;

    use HasUlids;

    protected $table = 'classificacoes';

    protected $fillable = [
        'nome',
        'background_color',
        'user_id'
    ];

    public function despesas(): HasMany
    {
        return $this->hasMany(Despesa::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }
}
