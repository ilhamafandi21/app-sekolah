<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiswaBiaya extends Model
{
    protected $table = 'siswa_biayas';

    protected $fillable = [
        'siswa_id',
        'biaya_id',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class, 'biaya_id');
    }
}
