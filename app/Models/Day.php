<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    protected $fillable = [
        'nama_hari',
    ];

    public function schedulls(): HasMany
    {
        return $this->hasMany(Day::class, 'day_id');
    }
}
