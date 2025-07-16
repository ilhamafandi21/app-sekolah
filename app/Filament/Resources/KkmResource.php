<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KkmResource\Pages;
use App\Filament\Resources\KkmResource\RelationManagers;
use App\Models\Kkm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KkmResource extends Resource
{
    protected static ?string $model = Kkm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->label('Mata Pelajaran')
                    ->unique(ignoreRecord:true)
                    ->validationMessages([
                        'unique' => 'Mata pelajaran ini sudah di set'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('nilai')
                    ->label('Masukan Nilai Standart KKM Nasional')
                    ->numeric(),
                Forms\Components\Textarea::make('keterangan')
                    ->default('-')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai KKM')
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
            'index' => Pages\ListKkms::route('/'),
            'create' => Pages\CreateKkm::route('/create'),
            'edit' => Pages\EditKkm::route('/{record}/edit'),
        ];
    }
}
