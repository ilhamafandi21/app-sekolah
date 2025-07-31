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
        /* ───── 1. Tahun ajaran aktif ───── */
        $ta = TahunAjaran::where('status', 1)->first();

        if (! $ta) {
            Notification::make()
                ->title('Tahun ajaran aktif belum ada.')
                ->danger()
                ->send();
            return null;
        }

        /* ───── 2. Format "2023-2024" → prefix 4 digit ───── */
        $parts = explode('-', $ta->thn_ajaran, 2);    // pakai 2 limit agar aman
        if (count($parts) !== 2) {
            Notification::make()
                ->title('Format tahun ajaran salah (contoh: 2023-2024).')
                ->danger()
                ->send();
            return null;
        }

        [$awal, $akhir] = $parts;

        $prefix = $awal[0]                               // digit pertama 2023 → 2
                . substr($akhir, -2)                     // dua digit akhir 2024 → 24
                . substr(now()->format('m'), -1);        // digit terakhir bulan → 7
        // Contoh: 2247

        /* ───── 3. Cari urutan terkecil yang belum dipakai (termasuk soft-delete) ───── */
        for ($seq = 1; $seq <= 9998; $seq++) {
            $nis = $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);

            $exists = Siswa::withTrashed()               // hitung yg soft-delete juga
                ->where('nis', $nis)
                ->exists();

            if (! $exists) {                             // belum dipakai → pakai
                return $nis;
            }
        }

        /* ───── 4. Penuh ───── */
        Notification::make()
            ->title('NIS untuk tahun ajaran ini sudah penuh.')
            ->danger()
            ->send();

        return null;
    }
}