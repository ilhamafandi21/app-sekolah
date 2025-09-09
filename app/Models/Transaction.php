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
        'semester',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }
    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }


}
