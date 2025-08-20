<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectsIndikatornilai extends Model
{
    protected $fillable = [
        'subject_id',
        'indikatornilai_id',
        'nilai',
        'teacher_id',
    ];

    public function subject(): BelongsTo { return $this->belongsTo(Subject::class); }
    public function indikatornilai(): BelongsTo { return $this->belongsTo(Indikatornilai::class); }
}
