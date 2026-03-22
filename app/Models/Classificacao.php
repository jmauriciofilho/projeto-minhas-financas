<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classificacao extends Model
{
    /** @use HasFactory<\Database\Factories\ClassificacaoFactory> */
    use HasFactory;

    use HasUuids;

    protected $table = 'classificacoes';

    protected $fillable = [
        'nome',
        'slug',
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
