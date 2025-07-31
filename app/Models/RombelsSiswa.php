<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RombelsSiswa extends Model
{
    protected $fillable = [
        'rombel_id',
        'siswa_id',
        'tingkat_id',
        'jurusan_id',
        'divisi',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(RombelsSiswa::class);
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(RombelsSiswa::class);
    }
}
