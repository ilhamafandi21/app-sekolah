<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use App\Models\Jurusan;
use App\Filament\Resources\JurusanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateJurusan extends CreateRecord
{
    protected static string $resource = JurusanResource::class;
    protected static ?string $title = 'Tambah Jurusan';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nama_jurusan'] = strtoupper($data['nama_jurusan']);

            if(Jurusan::where('nama_jurusan', $data['nama_jurusan'])->exists()){
                Notification::make()
                    ->title('Error')
                    ->body('Data sudah ada!')
                    ->danger()
                    ->send();

                    $this->halt();
            }

        return $data;
    }
}
