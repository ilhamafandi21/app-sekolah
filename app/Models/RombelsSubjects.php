<?php

namespace App\Models;

use App\Models\Rombel;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Schedull;
use App\Models\RombelsSubjectsTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RombelsSubjects extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'subject_id',
        'schedull_id',
        'teacher_id',
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
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedull(): BelongsTo
    {
        return $this->belongsTo(Schedull::class);
    }
}
