<?php

namespace App\Filament\Resources\TahunAjaranResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TahunAjaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTahunAjaran extends EditRecord
{
    protected static string $resource = TahunAjaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
