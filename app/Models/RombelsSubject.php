<?php

namespace App\Models;


use App\Models\RombelSubjectTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RombelsSubjects extends Model
{
    use HasFactory;

    protected $table = 'rombels_subjects';

    protected $fillable = [
        'rombel_id',
        'subject_id',
        'keterangan',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // public function teachers()
    // {
    //     return $this->hasMany(Teacher::class);
    // }

    public function rombelsSubjectTeachers(): HasMany
    {
        return $this->hasMany(RombelSubjectTeacher::class);
    }

}
