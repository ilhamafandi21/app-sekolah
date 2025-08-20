<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Indikatornilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_indikator',
        'keterangan',
    ];


    public function subjectsindikatornilai(): HasMany
    {
        return $this->hasMany(SubjectsIndikatornilai::class);
    }

    
    public function rombelsPenilaian(): HasMany
    {
        return $this->hasMany(rombelsPenilaian::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subjects_indikatornilais','indikatornilai_id','subject_id' )
                ->withTimestamps();
    }
}
