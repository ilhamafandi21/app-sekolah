<?php

namespace App\Models;

use App\Models\Kkm;
use App\Traits\GeneratesKodeFromName;
use App\Models\RombelsSubjectsTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subject extends Model
{
     use HasFactory, SoftDeletes, GeneratesKodeFromName;

    protected $fillable = [
        'kode',
        'name',
        'kkm',
        'deskripsi',
    ];
    
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teachers_subjects');
    }

    public function jurusans(): BelongsToMany
    {
        return $this->belongsToMany(Jurusan::class, 'subjects_jurusans');
    }

    public function jurusans_subjects(): HasMany
    {
        return $this->hasMany(SubjectsJurusans::class);
    }

    public function rombels_subjects(): BelongsToMany
    {
        return $this->belongsToMany(Rombel::class, 'rombels_subjects');
    }

    
    public function jenisNilai(): HasMany
    {
        return $this->hasMany(JenisNilai::class);
    }

}
