<?php

namespace App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages;

use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRombelsSubjectsSchedullsTeacher extends CreateRecord
{
    protected static string $resource = RombelsSubjectsSchedullsTeacherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);

        return $data;
    }
}
