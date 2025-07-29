<?php

namespace App\Models;

use App\Models\Rombel;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\RombelsSubjectsTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RombelsSubjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'semester_id',
        'subject_id',
        'keterangan',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'rombels_subjects_teachers', 'rombels_subjects_id', 'teacher_id')
            ->withPivot(['semester_id'])
            ->withTimestamps();
    }

     public function rombelsSubjectsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsTeacher::class);
    }
}
