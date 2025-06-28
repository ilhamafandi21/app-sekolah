<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SiswaResource;

class SiswaDetail extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
                ActionGroup::make([
                    EditAction::make('Edit'),
                    DeleteAction::make('Delete'),
                ])
        ];
    }

    public function getTitle(): string
    {
        return $this->record->nama;
    }
}
