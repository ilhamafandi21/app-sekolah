<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectsIndikatornilai extends Model
{
    protected $fillable = [
        'subject_id',
        'indikatornilai_id',
        'nilai',
        'teacher_id',
    ];
}
