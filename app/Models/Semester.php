<?php

namespace App\Models;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'keterangan',
        'tahun_ajaran_id',
    ];

    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class);
    }

     public function tahun_ajaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
