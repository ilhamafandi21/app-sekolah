<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RombelBiaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'biaya_id',
    ];

     public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

     public function biaya(): BelongsTo
    {
        return $this->belongsTo(Biaya::class);
    }
}
