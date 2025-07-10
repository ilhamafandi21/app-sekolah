<?php

namespace App\Models;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'files',
        'siswa_id',
        'ket',
    ];

     public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
