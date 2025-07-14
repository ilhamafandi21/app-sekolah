<?php

namespace App\Models;


use App\Models\RombelSubjectTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RombelsSubjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'subject_id',
        'keterangan',
    ];

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

     public function rombels_subjects_teachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsTeacher::class);
    }


    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'rombels_subjects_teachers');
    }

}
