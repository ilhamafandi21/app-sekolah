<?php

namespace App\Filament\Resources\BiayaResource\Pages;

use App\Filament\Resources\BiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiaya extends EditRecord
{
    protected static string $resource = BiayaResource::class;
    protected static ?string $title = 'Edit Biaya';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->requiresConfirmation()
                ->modalHeading('Hapus Biaya')
                ->modalDescription('Apakah Anda yakin ingin menghapus biaya ini? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus Biaya'),
        ];
    }
}
