<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RombelsSubjectsResource\Pages;
use App\Filament\Resources\RombelsSubjectsResource\RelationManagers;
use App\Filament\Resources\RombelsSubjectsResource\RelationManagers\RombelsSubjectsTeachersRelationManager;
use App\Models\RombelsSubjects;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RombelsSubjectsResource extends Resource
{
    protected static ?string $model = RombelsSubjects::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
                    ->relationship('rombel', 'name')
                    ->required(),
                Forms\Components\TextInput::make('semester_id'),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rombel.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRombelsSubjects::route('/'),
            'create' => Pages\CreateRombelsSubjects::route('/create'),
            'edit' => Pages\EditRombelsSubjects::route('/{record}/edit'),
        ];
    }
}
