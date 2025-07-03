<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Filament\Resources\RombelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRombel extends EditRecord
{
    protected static string $resource = RombelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
