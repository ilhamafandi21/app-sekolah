<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use Filament\Actions;
use App\Models\Jurusan;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\JurusanResource;
use Illuminate\Contracts\Database\Query\Builder;

class ListJurusans extends ListRecords
{
    protected static string $resource = JurusanResource::class;
    protected static ?string $title = 'Jurusan';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        return [
        'semua' => Tab::make('Semua')
            ->badge(Jurusan::count())
            ->badgeColor('success'),

        'sampah' => Tab::make('Sampah')
            ->badge(Jurusan::onlyTrashed()->count()) // perbaikan
            ->badgeColor('secondary') // perbaikan typo
            ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
            ];
    }
}
