<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SubjectResource;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;
    protected static ?string $title = 'Mata Pelajaran';

    protected function mutateFormDataBeforeCreate(array $data): array
     {

         if (\App\Models\Subject::where('name', $data['name'])->exists())
            {
            // Notifikasi error & hentikan proses
                \Filament\Notifications\Notification::make()
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
