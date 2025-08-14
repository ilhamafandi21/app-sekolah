<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikatornilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_indikator',
        'keterangan',
    ];
}
