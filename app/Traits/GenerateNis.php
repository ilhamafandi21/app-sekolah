<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Siswa;
use Filament\Notifications\Notification;
use App\Models\TahunAjaran;          // tabel tahun_ajarans

Trait GenerateNis
{
    public static function generateNis(): ?string
    {
         // Ambil tahun ajaran aktif
    $ta = TahunAjaran::where('status', 1)->first();

    if (! $ta) {
        Notification::make()
            ->title('Tahun ajaran aktif belum ada.')
            ->danger()
            ->send();
        return null;
    }

    // Pecah '2023-2024' → ['2023', '2024']
    $parts = explode('-', $ta->thn_ajaran);
    if (count($parts) !== 2) {
        Notification::make()
            ->title('Format tahun ajaran salah (harus 2023-2024).')
            ->danger()
            ->send();
        return null;
    }

    [$awal, $akhir] = $parts;

    $prefix = $awal[0]                     // 2
            . substr($akhir, -2)           // 24
            . substr(now()->format('m'), -1); // bulan → 7
    // → 2247

    $lastNis = Siswa::where('nis', 'like', $prefix.'%')
                    ->orderBy('nis', 'desc')
                    ->value('nis');

    $next = $lastNis ? (int) substr($lastNis, -4) + 1 : 1;

    if ($next > 9998) {
        Notification::make()
            ->title('NIS penuh untuk tahun ajaran ini.')
            ->danger()
            ->send();
        return null;
    }

    return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}