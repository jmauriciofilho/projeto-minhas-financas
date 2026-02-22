<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    /** @use HasFactory<\Database\Factories\ReceitaFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        "nome",
        "valor",
        "mes",
        "conta_id"
    ];

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class);
    }
}
