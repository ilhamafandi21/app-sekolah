<?php

namespace App\Filament\Resources\RombelSubjectTeacherResource\Pages;

use App\Filament\Resources\RombelSubjectTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRombelSubjectTeachers extends ListRecords
{
    protected static string $resource = RombelSubjectTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
