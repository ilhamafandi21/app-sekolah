<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teachers_subjects');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rombels_subjects(): BelongsToMany
    {
        return $this->belongsToMany(RombelsSubjects::class, 'rombels_subjects_teachers');
    }
}
