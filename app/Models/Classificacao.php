<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classificacao extends Model
{
    /** @use HasFactory<\Database\Factories\ClassificacaoFactory> */
    use HasFactory;

    use HasUlids;

    protected $table = 'classificacoes';

    protected $fillable = [
        'nome',
        'user_id'
    ];
}
