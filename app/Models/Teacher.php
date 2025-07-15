<?php

namespace App\Models;

use App\Models\RombelsSubjects;
use App\Models\RombelsSubjectsTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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

    public function rombelsSubjects(): BelongsToMany
    {
        return $this->belongsToMany(RombelsSubjects::class, 'rombels_subjects_teachers');
    }

    public function rombelsSubjectsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsTeacher::class);
    }
}
