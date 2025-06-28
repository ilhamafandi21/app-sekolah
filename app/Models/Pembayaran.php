<?php

namespace App\Models;

use App\Enums\StatusPembayaran;
use App\Models\User;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'user_id',
        'jenis_pembayaran_id',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => StatusPembayaran::class,
    ];

    public function jenis_pembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
