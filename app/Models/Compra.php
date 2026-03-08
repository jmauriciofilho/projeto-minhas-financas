<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compra extends Model
{
    /** @use HasFactory<\Database\Factories\CompraFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'descricao',
        'data_compra',
        'valor',
        'total_parcelas',
        'numero_parcela',
        'fatura_id',
        'classificacao_id'
    ];

    public function fatura(): BelongsTo
    {
        return $this->belongsTo(Fatura::class);
    }

    public function classificacao(): BelongsTo
    {
        return $this->belongsTo(Classificacao::class);
    }
}
