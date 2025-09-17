<?php

namespace App\Models;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tingkat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_tingkat',
        '',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tahun_ajaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function tingkats(): HasMany
    {
        return $this->hasMany(Jurusan::class);
    }

    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class);
    }
}
