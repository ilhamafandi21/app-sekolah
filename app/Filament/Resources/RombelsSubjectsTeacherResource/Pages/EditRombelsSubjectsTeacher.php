<?php

namespace App\Filament\Resources\RombelsSubjectsTeacherResource\Pages;

use App\Filament\Resources\RombelsSubjectsTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRombelsSubjectsTeacher extends EditRecord
{
    protected static string $resource = RombelsSubjectsTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
