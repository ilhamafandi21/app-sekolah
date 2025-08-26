<?php

namespace App\Filament\Resources\RombelsSubjectsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\RombelsSubjectsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRombelsSubjects extends ListRecords
{
    protected static string $resource = RombelsSubjectsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
