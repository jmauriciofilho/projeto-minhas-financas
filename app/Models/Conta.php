<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    /** @use HasFactory<\Database\Factories\ContaFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        "nome",
        "saldo"
    ];
}
