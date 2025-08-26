<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurusan extends EditRecord
{
    protected static string $resource = JurusanResource::class;
    protected static ?string $title = 'Edit Jurusan';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus')
                ->button()
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Data')
                ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                ->modalCancelActionLabel('Batal')
                ->modalSubmitActionLabel('Hapus'),
        ];
    }

   protected function mutateFormDataBeforeSave(array $data): array
   {
        $data['nama_jurusan'] = strtoupper($data['nama_jurusan']);

        return $data;
   }
}
