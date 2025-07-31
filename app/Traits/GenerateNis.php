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
        $ta = TahunAjaran::where('aktif', 1)->first();

        if (! $ta) {
            Notification::make()
                ->title('Tahun ajaran aktif belum ditetapkan. Diharapkan untuk membuat terlebih dulu Tahun Ajaran Aktif agar bisa generate NIS otomatis')
                ->danger()
                ->send();

            return null;                                  // ⬅ batal
        }

        // Pecah "2022-2023"
        [$awal, $akhir] = explode('-', $ta->nama);

        // Prefix 4 digit
        $prefix = $awal[0]                     // 2
                . substr($akhir, -2)           // 23
                . substr(Carbon::now()->format('m'), -1); // 7  => 2237

        // Urutan terakhir
        $lastNis = Siswa::where('nis', 'like', $prefix . '%')
                        ->orderBy('nis', 'desc')
                        ->value('nis');

        $next = $lastNis ? (int) substr($lastNis, -4) + 1 : 1;

        if ($next > 9998) {
            Notification::make()
                ->title('NIS untuk tahun ajaran ini sudah penuh.')
                ->danger()
                ->send();

            return null;                                  // ⬅ batal
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}