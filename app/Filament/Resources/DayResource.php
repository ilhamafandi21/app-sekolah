<?php

namespace App\Filament\Resources;

use App\Models\Day;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('schedulls');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_hari')
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
                Tables\Columns\TextColumn::make('nama_hari')
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

                ColumnGroup::make('jadwal',
                [
                    Tables\Columns\TextColumn::make('schedulls.start_at')
                        ->label('Start at')
                        ->listWithLineBreaks()
                        ->badge()
                        ->color('success'),
                    Tables\Columns\TextColumn::make('schedulls.end_at')
                        ->label('End at')
                        ->listWithLineBreaks()
                        ->badge()
                        ->color('primary'),
                ]),

                
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SchedullsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDays::route('/'),
            'create' => Pages\CreateDay::route('/create'),
            'edit' => Pages\EditDay::route('/{record}/edit'),
        ];
    }
}
