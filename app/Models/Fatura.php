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
        'data_fechamento',
        'data_vencimento',
        'conta_id',
        'cartao_id',
    ];

    public function cartao(): BelongsTo
    {
        return $this->belongsTo(Cartao::class);
    }

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}
