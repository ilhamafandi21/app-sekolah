<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use Filament\Actions;
use App\Models\Subject;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SubjectResource;
use Illuminate\Contracts\Database\Query\Builder;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;
    protected static ?string $title = 'Mata Pelajaran';


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
            ->badge(Subject::count())
            ->badgeColor('success'),

        'sampah' => Tab::make('Sampah')
            ->badge(Subject::onlyTrashed()->count()) // perbaikan
            ->badgeColor('secondary') // perbaikan typo
            ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
            ];
    }
}
