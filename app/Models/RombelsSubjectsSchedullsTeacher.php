<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RombelsSubjectsSchedullsTeacher extends Model
{
    protected $fillable = [
        'kode',
        'rombels_subjects_id',
        'rombel_id',
        'subject_id',
        'schedull_id',
        'teacher_id',
    ];

    public function rombelsSubject(): BelongsTo
    {
        return $this->belongsTo(RombelsSubjects::class);
    }

     public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }
    
     public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class);
    }

    public function schedull(): BelongsTo
    {
        return $this->belongsTo(Schedull::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
