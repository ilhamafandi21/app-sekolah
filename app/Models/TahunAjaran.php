<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use function PHPUnit\Framework\returnSelf;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'tahun',
        'keterangan',
    ];

    protected function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'siswa_tahuns');
    }
}
