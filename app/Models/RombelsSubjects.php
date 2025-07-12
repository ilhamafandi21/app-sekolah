<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RombelsSubjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'subject_id',
        'keterangan',
    ];
}
