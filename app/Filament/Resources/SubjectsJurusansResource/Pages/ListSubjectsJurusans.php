<?php

namespace App\Filament\Resources\SubjectsJurusansResource\Pages;

use App\Filament\Resources\SubjectsJurusansResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubjectsJurusans extends ListRecords
{
    protected static string $resource = SubjectsJurusansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
