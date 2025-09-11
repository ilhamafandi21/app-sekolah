<?php

namespace App\Models;

use App\Models\Document;
use App\Traits\GenerateNis;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Siswa extends Model
{

    use HasFactory, HasRoles, SoftDeletes, GenerateNis;

    protected $table = 'siswas';

    protected $fillable = [
        'nis',
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

    public function siswaBiayas(): HasMany
    {
        return $this->hasMany(SiswaBiaya::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function rombelsPenilaian(): HasMany
    {
        return $this->hasMany(rombelsPenilaian::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function rombels(): BelongsToMany
    {
        return $this->belongsToMany(Rombel::class,
            'rombels_siswas',
            'siswa_id',
            'rombel_id',
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
}
