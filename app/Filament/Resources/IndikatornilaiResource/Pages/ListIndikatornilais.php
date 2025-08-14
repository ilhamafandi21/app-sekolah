<?php

namespace App\Filament\Resources\IndikatornilaiResource\Pages;

use App\Filament\Resources\IndikatornilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndikatornilais extends ListRecords
{
    protected static string $resource = IndikatornilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
