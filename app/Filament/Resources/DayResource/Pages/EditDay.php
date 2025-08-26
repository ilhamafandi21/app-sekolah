<?php

namespace App\Filament\Resources\DayResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDay extends EditRecord
{
    protected static string $resource = DayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
