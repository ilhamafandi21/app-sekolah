<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'jenis_nilai_id',
        'rombel_id',
        'teacher_id',
        'skor',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function jenisNilai()
    {
        return $this->belongsTo(JenisNilai::class, 'jenis_nilai_id');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}