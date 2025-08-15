<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RombelsPenilaian extends Model
{
    protected $fillable = [
        'rombel_id',
        'siswa_id',
        'subject_id',
        'indikatornilai_id',
        'teacher_id',
        'semester_id',
        'nilai',
    ];

    public function rombel(): BelongsTo {return $this->belongsTo(Rombel::class);}
    public function siswa(): BelongsTo {return $this->belongsTo(Siswa::class);}
    public function subject(): BelongsTo {return $this->belongsTo(Subject::class);}
    public function indikatornilai(): BelongsTo {return $this->belongsTo(Indikatornilai::class);}
    public function teacher(): BelongsTo {return $this->belongsTo(Teacher::class);}
    public function semester(): BelongsTo {return $this->belongsTo(Semester::class);}

}
