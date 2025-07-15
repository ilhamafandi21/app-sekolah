<?php

namespace App\Filament\Resources\SemesterResource\Pages;

use Filament\Actions;
use App\Models\Semester;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SemesterResource;

class CreateSemester extends CreateRecord
{
    protected static string $resource = SemesterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = Semester::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('Semester sudah ada')
                ->body('Semester ini sudah terdaftar untuk tahun ajaran tersebut.')
                ->danger()
                ->send();

            // Hentikan proses create
            $this->halt();
        }

        return $data;
    }
}
