<?php

namespace App\Models;


use App\Models\RombelSubjectTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RombelsSubjects extends Model
{
    use HasFactory;

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


    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'rombels_subjects_teachers');
    }

}
