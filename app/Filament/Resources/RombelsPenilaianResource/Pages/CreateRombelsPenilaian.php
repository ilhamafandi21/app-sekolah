<?php

namespace App\Filament\Resources\RombelsPenilaianResource\Pages;

use Filament\Actions;
use App\Models\RombelsPenilaian;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RombelsPenilaianResource;

class CreateRombelsPenilaian extends CreateRecord
{
    protected static string $resource = RombelsPenilaianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
         // --- KOMBINASI UMUM TANPA teacher_id (paling sering dipakai) ---
        $exists = static::getModel()::query()
            ->where('rombel_id',         $data['rombel_id'] ?? null)
            ->where('siswa_id',          $data['siswa_id'] ?? null)
            ->where('subject_id',        $data['subject_id'] ?? null)
            ->where('indikatornilai_id', $data['indikatornilai_id'] ?? null)
            ->where('semester_id',       $data['semester_id'] ?? null)
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('Nilai sudah di input')
                ->body('Kombinasi Rombel, Siswa, Subject, Indikator, dan Semester sudah gi input.')
                ->danger()
                ->send();

            $this->halt(); // hentikan create
        }

        // (opsional) normalisasi/guard nilai
        $data['nilai'] = isset($data['nilai']) ? (int) $data['nilai'] : null;

        return $data;
    } 
}
