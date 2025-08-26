<?php

namespace App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRombelsSubjectsSchedullsTeachers extends ListRecords
{
    protected static string $resource = RombelsSubjectsSchedullsTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
