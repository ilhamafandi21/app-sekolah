<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use App\Filament\Resources\JurusanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJurusan extends CreateRecord
{
    protected static string $resource = JurusanResource::class;
    protected static ?string $title = 'Jurusan';


     protected function mutateFormDataBeforeCreate(array $data): array
     {
        $nama = $data['nama'] = strtoupper($data['nama']);


         if (\App\Models\Jurusan::where('nama', $data['nama'])->exists())
            {
            // Notifikasi error & hentikan proses
                \Filament\Notifications\Notification::make()
                    ->title('Duplikat Jurusan')
                    ->body("Jurusan: {$nama} sudah ada. Periksa kembali data!")
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
