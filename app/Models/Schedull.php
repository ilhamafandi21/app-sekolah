<?php

namespace App\Models;

use App\Models\Day;
use App\Models\RombelsSubjects;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedull extends Model
{

    use HasFactory;

    protected $fillable = [
        'kode',
        'start_at',
        'end_at',
     ];

     public function day(): BelongsTo
     {
        return $this->belongsTo(Day::class);
     }

     public function rombelsSubjects(): HasMany
     {
         return $this->hasMany(RombelsSubjects::class);
     }

    public function rombelsSubjectsSchedullsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsSchedullsTeacher::class);
    }
}
