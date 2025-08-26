<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Actions;
use App\Models\Teacher;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TeacherResource;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
        {
           return [
            'semua' => Tab::make('Semua')
                ->badge(Teacher::withoutTrashed()->count())
                ->badgeColor('success'),

            'sampah' => Tab::make('Sampah')
                ->badge(Teacher::onlyTrashed()->count()) // perbaikan
                ->badgeColor('secondary') // perbaikan typo
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
                ];
        }
}
