<?php

namespace App\Models;

use App\Models\RombelsSubjects;
use App\Models\RombelsSubjectsTeacher;
use App\Traits\GeneratesNip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory, SoftDeletes, GeneratesNip;

    protected $fillable = [
        'nip',
        'name',
        'tgl_lahir',
        'kota_lahir',
        'alamat',
        'pendidikan',
        'foto',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teachers_subjects')->withTimestamps();
    }
    public function rombelsSubjects(): HasMany
    {
        return $this->hasMany(RombelsSubjects::class);
    }
    
    public function RombelsSubjectsSchedullsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsSchedullsTeacher::class);
    }
}
