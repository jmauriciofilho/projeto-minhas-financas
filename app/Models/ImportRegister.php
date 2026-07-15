<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ImportRegister extends Model
{
    use HasUuids;

    protected $fillable = [
        "data_mes",
        "data_import",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
