<?php

namespace App\Models;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jurusan extends Model
{
   use HasFactory;

    protected $table = 'jurusans';

    protected $fillable = [
        'tahun_ajaran_id',
        'tingkat_id',
        'kode',
        'nama_jurusan',
        'keterangan',
    ];


    public function tahun_ajaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class);
    }
    
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subjects_jurusans');
    }
  
}
