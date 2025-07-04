<?php

namespace App\Filament\Resources\BiayaResource\Pages;

use App\Filament\Resources\BiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayas extends ListRecords
{
    protected static string $resource = BiayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
