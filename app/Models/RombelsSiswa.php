<?php

namespace App\Models;

use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RombelsSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'siswa_id',
    ];

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
