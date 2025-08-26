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

class Subject extends Model
{
     use HasFactory, SoftDeletes, GeneratesKodeFromName;

    protected $fillable = [
        'kode',
        'name',
        'nilai_kkm',
        'deskripsi',
    ];
    

    public function rombelsPenilaian(): HasMany
    {
        return $this->hasMany(rombelsPenilaian::class);
    }
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teachers_subjects')->withTimestamps();
    }

    public function jurusans(): BelongsToMany
    {
        return $this->belongsToMany(Jurusan::class, 'subjects_jurusans');
    }

    public function rombels(): BelongsToMany
    {
        return $this->belongsToMany(Rombel::class, 'rombels_subjects', 'subject_id', 'rombel_id')
            ->using(RombelsSubjects::class)
            ->withPivot(['schedull_id', 'teacher_id'])
            ->as('rombelsSubject')
            ->withTimestamps();
    }

    public function indikatornilais(): BelongsToMany
    {
        return $this->belongsToMany(Indikatornilai::class, 'subjects_indikatornilais', 'subject_id', 'indikatornilai_id')
                ->withTimestamps();
    }

    public function subjectsindikatornilais(): HasMany
    {
        return $this->hasMany(Indikatornilai::class);
    }

    public function rombelsSubjects(): HasMany
    {
        return $this->hasMany(RombelsSubjects::class);
    }
    
     public function rombelsSubjectsSchedullsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsSchedullsTeacher::class);
    }
}
