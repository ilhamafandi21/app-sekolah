<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedull extends Model
{
    protected $fillable = [
        'kode',
        'day_id',
        'start_at',
        'end_at',
     ];

     public function day(): BelongsTo
     {
        return $this->belongsTo(\App\Models\Day::class);
     }
}
