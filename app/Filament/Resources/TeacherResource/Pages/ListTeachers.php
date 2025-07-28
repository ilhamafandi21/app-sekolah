<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use Filament\Actions;
use App\Models\Teacher;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TeacherResource;
use Illuminate\Contracts\Database\Query\Builder;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

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
                ->badge(Teacher::count())
                ->badgeColor('success'),

            'sampah' => Tab::make('Sampah')
                ->badge(Teacher::onlyTrashed()->count()) // perbaikan
                ->badgeColor('secondary') // perbaikan typo
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
                ];
        }
}
