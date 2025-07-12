<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RombelsSubjectsTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombels_subjects_id',
        'teacher_id',
        'keterangan',
    ];

    public function teacher() : BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

     public function rombels_subjects() : BelongsTo
    {
        return $this->belongsTo(RombelsSubjects::class);
    }
}
