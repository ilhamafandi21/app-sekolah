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
        'name',
        'tahun_ajaran',
        'tingkat_id',
        'jurusan_id',
        'status',
        'keterangan',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }
}
