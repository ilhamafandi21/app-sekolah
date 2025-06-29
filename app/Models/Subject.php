<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'name',
        'deskripsi',
        'tingkat_kelas',
        'semester',
    ];

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
