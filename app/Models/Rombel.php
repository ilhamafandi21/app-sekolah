<?php

namespace App\Models;

use App\Models\Biaya;
use App\Models\TahunAjaran;
use App\Traits\GeneratesKodeFromName;
use App\Models\RombelsSubjectsTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rombel extends Model
{
     use HasFactory;

    protected $fillable = [
        'kode',
        'tahun_ajaran_id',
        'tingkat_id',
        'jurusan_id',
        'divisi',
        'status',
        'keterangan',
    ];

    public function tahun_ajaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function biayas(): BelongsToMany
    {
        return $this->belongsToMany(Biaya::class, 'rombel_biayas');
    }

    public function rombelBiayas(): HasMany
    {
        return $this->hasMany(RombelBiaya::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'rombels_subjects', 'rombel_id', 'subject_id')
            ->using(\App\Models\RombelsSubjects::class)
            ->withPivot(['schedull_id', 'teacher_id'])
            ->as('rombelsSubject')
            ->withTimestamps();
    }

    public function rombelsSubjects(): HasMany
    {
        return $this->hasMany(RombelsSubjects::class);
    }

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 
            'rombels_siswas',
            'rombel_id',
            'siswa_id',
        )->withPivot(
            'tingkat_id',
            'jurusan_id',
            'divisi',
        )->withTimestamps();
    }

    public function rombelsSiswas(): HasMany
    {
        return $this->hasMany(RombelsSiswa::class);
    }

    public function RombelsSubjectsSchedullsTeachers(): HasMany
    {
        return $this->hasMany(RombelsSubjectsSchedullsTeacher::class);
    }
}
