<?php

namespace App\Models;

use App\Traits\GeneratesKodeFromName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
     use HasFactory, SoftDeletes, GeneratesKodeFromName;

    protected $fillable = [
        'kode',
        'name',
        'deskripsi',
    ];

    protected static function booted(): void
    {
        static::creating(function ($subject) {
            if (blank($subject->kode) && filled($subject->name)) {
                $subject->kode = $subject->generateKodeFromName($subject->name);
            }
        });
    }
    // Relasi opsional jika ada tabel jurusans
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teachers_subjects');
    }

    public function jurusans(): BelongsToMany
    {
        return $this->belongsToMany(Jurusan::class, 'subjects_jurusans');
    }
}
