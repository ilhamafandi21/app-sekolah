<?php

namespace App\Models;

use App\Models\Rombel;
use App\Models\Teacher;
use App\Models\RombelsSubjects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RombelSubjectTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'rombels_subjects_id',
        'teacher_id',
        'keterangan',
    ];

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function rombels_subjects(): BelongsTo
    {
        return $this->belongsTo(RombelsSubjects::class);
    }
}
