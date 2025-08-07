<?php

namespace App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages;

use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRombelsSubjectsSchedullsTeacher extends EditRecord
{
    protected static string $resource = RombelsSubjectsSchedullsTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
