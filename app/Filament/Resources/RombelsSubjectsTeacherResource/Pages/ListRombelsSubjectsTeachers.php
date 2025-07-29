<?php

namespace App\Filament\Resources\RombelsSubjectsTeacherResource\Pages;

use App\Filament\Resources\RombelsSubjectsTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRombelsSubjectsTeachers extends ListRecords
{
    protected static string $resource = RombelsSubjectsTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
