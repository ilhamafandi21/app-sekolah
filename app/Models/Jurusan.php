<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
   use HasFactory,  SoftDeletes;

    protected $table = 'jurusans';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
    ];

    public function subjects(): HasMany
    {
        return $this->hasMany(SubjectsJurusans::class);
    }
}
