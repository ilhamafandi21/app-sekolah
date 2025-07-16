<?php

namespace App\Filament\Resources\KkmResource\Pages;

use App\Filament\Resources\KkmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKkm extends EditRecord
{
    protected static string $resource = KkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
