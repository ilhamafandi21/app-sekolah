<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'kode',
        'siswa_id',
        'biaya_id',
        'rombel_id',
        'tingkat_id',
        'jurusan_id',
        'divisi',
        'nominal',
        'keterangan',
    ];
}
