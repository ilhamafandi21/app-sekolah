<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Enums\SemesterEnum;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RombelResource;
use App\Models\Semester;
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
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->with('semester')->withoutGlobalScopes()
            ),
        'ganjil' => Tab::make()
            ->label('Semester Ganjil')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->with('semester')
                      ->whereHas('semester', fn ($q) => $q->where('name', SemesterEnum::GANJIL->value))
            ),
        'genap' => Tab::make()
            ->label('Semester Genap')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->with('semester')
                      ->whereHas('semester', fn ($q) => $q->where('name', SemesterEnum::GENAP->value))
            ),
        
        'aktif' => Tab::make()
            ->label('Rombel Aktif')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', true) // atau 0 jika status tipe int
            ),
        
        'nonaktif' => Tab::make()
            ->label('Rombel Non-aktif')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', false) // atau 0 jika status tipe int
            ),
    ];
}

    
    

}
