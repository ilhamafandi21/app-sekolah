<?php

namespace App\Filament\Resources\KkmResource\Pages;

use App\Filament\Resources\KkmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKkms extends ListRecords
{
    protected static string $resource = KkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
