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
        'deskripsi',
    ];
    
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
            ->withPivot(['semester_id', 'teacher_id'])
            ->withTimestamps();
    }

    public function rombelsSubjects(): HasMany
    {
        return $this->hasMany(RombelsSubjects::class);
    }

    public function kkms(): HasMany
    {
        return $this->hasMany(Kkm::class);
    }
}
