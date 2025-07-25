<?php

namespace App\Filament\Resources\RombelResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RombelResource;
use Illuminate\Contracts\Database\Query\Builder;

class ListRombels extends ListRecords
{
    protected static string $resource = RombelResource::class;
    protected static ?string $title = 'Daftar Rombel';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
        {
            return [
                'all' => Tab::make()
                    ->label('Semua')
                    ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes()),
                'ganjil' => Tab::make()
                    ->label('Ganjil')
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('semester_id', 1)),
                'genap' => Tab::make()
                    ->label('Genap')
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('semester_id', 2)),
            ];
        }
}
