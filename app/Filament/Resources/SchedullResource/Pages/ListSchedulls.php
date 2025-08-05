<?php

namespace App\Filament\Resources\SchedullResource\Pages;

use App\Filament\Resources\SchedullResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedulls extends ListRecords
{
    protected static string $resource = SchedullResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
