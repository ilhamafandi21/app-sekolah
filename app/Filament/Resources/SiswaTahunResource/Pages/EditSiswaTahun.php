<?php

namespace App\Filament\Resources\SiswaTahunResource\Pages;

use App\Filament\Resources\SiswaTahunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiswaTahun extends EditRecord
{
    protected static string $resource = SiswaTahunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
