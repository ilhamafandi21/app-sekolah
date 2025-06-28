<?php

namespace App\Models;

use App\Enums\PendTerakhir;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrtuSiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_ibu',
        'nama_ayah',
        'pekerjaan_ibu',
        'pekerjaan_ayah',
        'pend_terakhir_ibu',
        'pend_terakhir_ayah',
        'phone',
        'alamat',
        'siswa_id',
    ];

    protected $casts = [
        'pend_terakhir' => PendTerakhir::class,
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
