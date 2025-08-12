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
        return $this->belongsTo(RombelsSubjects::class, 'rombels_subjects_id');
    }

     public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }
    
    public function day(): BelongsTo      { return $this->belongsTo(\App\Models\Day::class, 'day_id'); }
public function subject(): BelongsTo  { return $this->belongsTo(\App\Models\Subject::class, 'subject_id'); }
public function schedull(): BelongsTo { return $this->belongsTo(\App\Models\Schedull::class, 'schedull_id'); }
public function teacher(): BelongsTo  { return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id'); }

}
