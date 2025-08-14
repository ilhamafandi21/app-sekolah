<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndikatornilaiResource\Pages;
use App\Filament\Resources\IndikatornilaiResource\RelationManagers;
use App\Models\Indikatornilai;
use App\Traits\GenerateIndikator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\CallLike;

class IndikatornilaiResource extends Resource
{
    protected static ?string $model = Indikatornilai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Indikator penilaian';

    use GenerateIndikator;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->default(fn (): string => GenerateIndikator::indikator())
                    ->unique(ignoreRecord:true)
                    ->required(),
                Forms\Components\TextInput::make('nama_indikator')
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_indikator')
                    ->searchable(),
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
            'index' => Pages\ListIndikatornilais::route('/'),
            'create' => Pages\CreateIndikatornilai::route('/create'),
            'edit' => Pages\EditIndikatornilai::route('/{record}/edit'),
        ];
    }
}
