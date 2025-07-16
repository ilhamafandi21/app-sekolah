<?php

namespace App\Filament\Resources\KkmResource\Pages;

use App\Filament\Resources\KkmResource;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;

class ListKkms extends ListRecords
{
    protected static string $resource = KkmResource::class;
    protected static ?string $title = 'Daftar Nilai KKM Nasional';

    protected function getHeaderActions(): array
    {
        return [
                Actions\CreateAction::make()
                    ->slideOver()
                    ->modalWidth('xl')
                    ->label('Tambah'),
        ];
    }
}
