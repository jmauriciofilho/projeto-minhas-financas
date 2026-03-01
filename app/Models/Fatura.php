<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fatura extends Model
{
    /** @use HasFactory<\Database\Factories\FaturaFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'mes_referencia',
        'dia_fechamento',
        'dia_vencimento',
        'ja_foi_paga',
        'cartao_id',
    ];

    public function cartao(): BelongsTo
    {
        return $this->belongsTo(Cartao::class);
    }
}
