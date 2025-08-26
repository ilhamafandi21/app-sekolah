<?php

namespace App\Filament\Resources\SchedullResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SchedullResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedull extends EditRecord
{
    protected static string $resource = SchedullResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
