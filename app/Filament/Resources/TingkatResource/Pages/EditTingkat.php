<?php

namespace App\Filament\Resources\TingkatResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TingkatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTingkat extends EditRecord
{
    protected static string $resource = TingkatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
