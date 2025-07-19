<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JenisNilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'indikator',
        'keterangan',
    ];

    public function subject():BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'jenis_nilai_id');
    }
}
