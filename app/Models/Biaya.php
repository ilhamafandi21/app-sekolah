<?php

namespace App\Models;

use App\Models\Rombel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Biaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nominal',
        'status',
        'keterangan',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function rombels(): BelongsToMany
    {
        return $this->belongsToMany(Rombel::class, 'rombel_biayas');
    }
    public function rombelBiayas(): HasMany
    {
        return $this->hasMany(RombelBiaya::class);
    }
}
