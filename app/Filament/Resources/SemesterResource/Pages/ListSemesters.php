<?php

namespace App\Filament\Resources\SemesterResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSemesters extends ListRecords
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
