<?php

namespace App\Filament\Resources\RombelsSubjectsResource\Pages;

use Filament\Actions;
use App\Models\Rombel;
use App\Models\RombelsSubjects;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Query\Builder;
use App\Filament\Resources\RombelsSubjectsResource;

class ListRombelsSubjects extends ListRecords
{
    protected static string $resource = RombelsSubjectsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        
        $rombels = RombelsSubjects::with('rombel')->get();
       return  [

        '0' => Tab::make('Semua')
            ->badge(RombelsSubjects::count())
            ->badgeColor('success'),

        '1' => Tab::make('XI')
            ->badge(RombelsSubjects::with('rombel')->where('tingkat_id', 'XI')->count())
            ->badgeColor('danger')
           ->modifyQueryUsing(fn(Builder $query) => $query->where('tingkat_id', 'XI')),

       ];

    }
}
