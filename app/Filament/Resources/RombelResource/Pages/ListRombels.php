<?php

namespace App\Filament\Resources\RombelResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\RombelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRombels extends ListRecords
{
    protected static string $resource = RombelResource::class;
    protected static ?string $title = 'Daftar Rombel';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat')
                ->icon('heroicon-o-plus-circle')
                ->iconPosition('after'),
        ];
    }
}
