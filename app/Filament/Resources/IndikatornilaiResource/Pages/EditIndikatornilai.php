<?php

namespace App\Filament\Resources\IndikatornilaiResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\IndikatornilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndikatornilai extends EditRecord
{
    protected static string $resource = IndikatornilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
