<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Models\Subject;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SubjectResource;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;
    protected static ?string $title = 'Mata Pelajaran';

    protected function mutateFormDataBeforeCreate(array $data): array
     {

         if (Subject::where('name', $data['name'])->exists())
            {
            // Notifikasi error & hentikan proses
                Notification::make()
                    ->title('Duplikat Mapel')
                    ->body("Mapel: {$data['name']} sudah ada. Periksa kembali data!")
                    ->danger()
                    ->send();
            }

        return $data;
    }


    protected function afterCreate(): void
    {
        // Refresh halaman setelah submit
        $this->redirect(self::getResource()::getUrl('create'));
    }
}
