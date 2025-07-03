<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RombelSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'siswa_id',
    ];
}
