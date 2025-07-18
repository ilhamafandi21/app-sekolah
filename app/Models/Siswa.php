<?php

namespace App\Models;

use App\Models\Rombel;
use App\Models\Document;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Siswa extends Model
{

    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'siswas';

    protected $fillable = [
        'name',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'agama',
        'jenis_kelamin',
        'asal_sekolah',
        'tahun_lulus',
        'documents',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function rombelsSiswa(): HasMany
    {
        return $this->hasMany(RombelsSiswa::class);
    }

    public function rombels(): BelongsToMany
    {
        return $this->belongsToMany(Rombel::class, 'rombels_siswas');
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }


}
