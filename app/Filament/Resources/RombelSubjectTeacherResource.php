<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RombelSubjectTeacherResource\Pages;
use App\Filament\Resources\RombelSubjectTeacherResource\RelationManagers;
use App\Models\RombelSubjectTeacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RombelSubjectTeacherResource extends Resource
{
    protected static ?string $model = RombelSubjectTeacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
                    ->label('Rombel')
                    ->options(
                        \App\Models\Rombel::get()
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('rombels_subjects_id')
                    ->label('Mata Pelajaran')
                    ->relationship('rombels_subjects', 'id')
                    
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('teacher_id')
                    ->label('Guru Pengampu')
                    ->options(
                        \App\Models\Teacher::get()
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('-')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rombel_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rombels_subjects_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
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
            'index' => Pages\ListRombelSubjectTeachers::route('/'),
            'create' => Pages\CreateRombelSubjectTeacher::route('/create'),
            'edit' => Pages\EditRombelSubjectTeacher::route('/{record}/edit'),
        ];
    }
}
