<?php

namespace App\Models;

use App\Traits\GeneratesKodeFromName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
     use HasFactory, SoftDeletes, GeneratesKodeFromName;

    protected $fillable = [
        'kode',
        'name',
        'deskripsi',
        'tingkat_kelas',
        'semester',
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
    public function teachers(): HasMany
    {
        return $this->hasMany(TeachersSubjects::class);
    }

    public function jurusans(): HasMany
    {
        return $this->hasMany(SubjectsJurusans::class);
    }
}
