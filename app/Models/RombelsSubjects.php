<?php

namespace App\Models;

use App\Models\Rombel;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RombelsSubjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
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
}
