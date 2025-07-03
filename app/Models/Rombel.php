<?php

namespace App\Models;

use App\Traits\GeneratesKodeFromName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rombel extends Model
{
     use HasFactory;

    protected $fillable = [
        'tingkat',
        'jurusan_id',
        'divisi',
        'keterangan',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'rombel_siswas');
    }
}
