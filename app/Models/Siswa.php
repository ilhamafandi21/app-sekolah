<?php

namespace App\Models;

use App\Enums\Agama;
use App\Models\User;
use App\Models\Documents;
use App\Models\OrtuSiswa;
use App\Enums\StatusSiswa;
use App\Models\Pembayaran;
use App\Enums\JenisKelamin;
use App\Enums\StatusPendaftaran;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model implements HasMedia
{
    use HasFactory, Notifiable, SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'nama',
        'tempat_lahir',
        'ttl',
        'jenis_kelamin',
        'alamat',
        'email',
        'agama',
        'nama_ibu',
        'nama_ayah',
        'pekerjaan_ibu',
        'pekerjaan_ayah',
        'nis',
        'nisn',
        'asal_sekolah',
        'status_pendaftaran',
        'status_siswa',
        'waktu_pendaftaran',
        'waktu_siswa_aktif',
        'password',
        'role',
        'user_id',
    ];

    protected $casts = [
        'gender' => JenisKelamin::class,
        'agama' => Agama::class,
        'status' => StatusPendaftaran::class,
        'statusSiswa' => StatusSiswa::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Documents::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function ortus(): HasOne
    {
        return $this->hasOne(OrtuSiswa::class);
    }
}
