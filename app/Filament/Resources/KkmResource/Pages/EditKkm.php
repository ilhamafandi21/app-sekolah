<?php

namespace App\Filament\Resources\KkmResource\Pages;

use App\Filament\Resources\KkmResource;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\EditRecord;

class EditKkm extends EditRecord
{
    protected static string $resource = KkmResource::class;
    protected static ?string $title = 'Edit Nilai KKM';

    protected function getHeaderActions(): array
    {
        return [
      
             Actions\DeleteAction::make(),

        ];
    }
}
