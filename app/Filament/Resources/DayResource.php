<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DayResource\Pages\ListDays;
use App\Filament\Resources\DayResource\Pages\CreateDay;
use App\Filament\Resources\DayResource\Pages\EditDay;
use App\Models\Day;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DayResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DayResource\RelationManagers;
use App\Filament\Resources\DayResource\RelationManagers\SchedullsRelationManager;
use Filament\Tables\Columns\ColumnGroup;

class DayResource extends Resource
{
    protected static ?string $model = Day::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Penjadwalan';

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->with('schedulls');
    // }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_hari')
                    ->dehydrated()
                    ->mutateDehydratedStateUsing(fn($state)=>strtoupper($state))
                    ->disabled( fn(string $context) => $context === 'edit')
                    ->required()
                    ->unique()
                    ->validationMessages([
                        'unique' => 'Hasri sudah tersedia!'
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_hari')
                    ->badge()
                    ->color('secondary')
                    ->searchable(),
                
                // Tables\Columns\TextColumn::make('schedulls')
                //     ->label('Waktu')
                //     ->html() // ← INI PENTING!
                //     ->formatStateUsing(function ($record) {
                //         return $record->schedulls
                //             ->map(fn ($s) => "{$s->start_at} - {$s->end_at}")
                //             ->implode('<br>');
                // }),

                // Tables\Columns\TextColumn::make('schedulls.rombelsSubjects.subject_id')
                //     ->label('Mapel')
                    
                // ,

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // SchedullsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDays::route('/'),
            'create' => CreateDay::route('/create'),
            'edit' => EditDay::route('/{record}/edit'),
        ];
    }
}
