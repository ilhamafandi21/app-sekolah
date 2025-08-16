<?php

namespace App\Filament\Resources\RombelsSubjectsResource\Pages;

use Filament\Actions;
use App\Models\RombelsSubjects;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RombelsSubjectsResource;

class CreateRombelsSubjects extends CreateRecord
{
    protected static string $resource = RombelsSubjectsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
            $exists = RombelsSubjects::query()
                ->where('rombel_id', $data['rombel_id'] ?? null)
                ->where('subject_id', $data['subject_id'] ?? null)
                ->exists();

            if ($exists) {
                Notification::make()
                    ->title('Data duplikat')
                    ->body('Kombinasi Rombel & Subject sudah ada.')
                    ->danger()
                    ->send();

                $this->halt(); // hentikan proses create
            }

            return $data;
    }
}
