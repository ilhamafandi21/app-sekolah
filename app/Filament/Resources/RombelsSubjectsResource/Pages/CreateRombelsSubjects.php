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

     public function create(bool $another = false): void
    {
        $data = $this->form->getState();

        $exists = RombelsSubjects::where('rombel_id', $data['rombel_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('Duplikat Data')
                ->body('Rombel dan mata pelajaran ini sudah dipasangkan sebelumnya.')
                ->danger()
                ->send();

            return; // Stop tanpa menyimpan & tanpa error
        }

        $this->record = $this->handleRecordCreation($data);

        Notification::make()
            ->title('Berhasil')
            ->body('Data berhasil ditambahkan.')
            ->success()
            ->send();

        $this->redirectAfterCreate($another);
    }
}
