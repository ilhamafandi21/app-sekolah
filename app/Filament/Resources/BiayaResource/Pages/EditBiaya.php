<?php

namespace App\Filament\Resources\BiayaResource\Pages;

use App\Filament\Resources\BiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiaya extends EditRecord
{
    protected static string $resource = BiayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
