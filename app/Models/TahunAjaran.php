<?php

namespace App\Models;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'thn_ajaran',
        'status',
        'keterangan'
    ];

    public function rombels(): HasMany
    {
        return $this->hasMany(Rombel::class);
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function tingkats(): HasMany
    {
        return $this->hasMany(Tingkat::class);
    }
}
