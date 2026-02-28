<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartao extends Model
{
    /** @use HasFactory<\Database\Factories\CartaoFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        "nome",
        "final_cartao",
        "user_id"
    ];
}
