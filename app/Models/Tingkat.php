<?php

namespace App\Models;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tingkat extends Model
{
    protected $fillable = [
        'tahun_ajaran_id',
        'nama_tingkat',
        'keterangan',
    ];

    public function tahun_ajaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
