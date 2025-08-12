<?php

namespace App\Models;

use App\Models\Schedull;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    use HasFactory;
    
    protected $table = 'days';
    
    protected $fillable = [
        'nama_hari',
    ];

    public function rombelsSubjectsSchedullsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsSchedullsTeacher::class, 'day_id');
    }
}
