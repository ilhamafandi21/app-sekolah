<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\IndikatornilaiResource\Pages\ListIndikatornilais;
use App\Filament\Resources\IndikatornilaiResource\Pages\CreateIndikatornilai;
use App\Filament\Resources\IndikatornilaiResource\Pages\EditIndikatornilai;
use App\Filament\Resources\IndikatornilaiResource\Pages;
use App\Filament\Resources\IndikatornilaiResource\RelationManagers;
use App\Models\Indikatornilai;
use App\Traits\GenerateIndikator;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\CallLike;

class IndikatornilaiResource extends Resource
{
    protected static ?string $model = Indikatornilai::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-magnifying-glass-plus';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data Akademik';
    protected static ?string $navigationLabel = 'Indikator Penilaian';

    use GenerateIndikator;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->default(fn (): string => GenerateIndikator::indikator())
                    ->unique(ignoreRecord:true)
                    ->required(),
                TextInput::make('nama_indikator')
                    ->required(),
                TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('nama_indikator')
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIndikatornilais::route('/'),
            'create' => CreateIndikatornilai::route('/create'),
            'edit' => EditIndikatornilai::route('/{record}/edit'),
        ];
    }
}
