<?php

namespace App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRombelsSubjectsSchedullsTeacher extends EditRecord
{
    protected static string $resource = RombelsSubjectsSchedullsTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
