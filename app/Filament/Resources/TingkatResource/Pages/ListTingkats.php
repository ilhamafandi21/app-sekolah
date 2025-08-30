<?php

namespace App\Filament\Resources\TingkatResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TingkatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTingkats extends ListRecords
{
    protected static string $resource = TingkatResource::class;
    protected static ?string $title = 'Daftar Tingkat';


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
