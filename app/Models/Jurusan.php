<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jurusan extends Model
{
   use HasFactory,  SoftDeletes;

    protected $table = 'jurusans';

    protected $fillable = [
        'tingkat_id',
        'kode',
        'nama_jurusan',
        'keterangan',
    ];

     public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subjects_jurusans');
    }
    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class, 'subjects_jurusans');
    }
}
