<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\BiayaResource\Pages\ListBiayas;
use App\Filament\Resources\BiayaResource\Pages\CreateBiaya;
use App\Filament\Resources\BiayaResource\Pages\EditBiaya;
use App\Filament\Resources\BiayaResource\Pages;
use App\Filament\Resources\BiayaResource\RelationManagers;
use App\Models\Biaya;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaResource extends Resource
{
    protected static ?string $model = Biaya::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data Akademik';
    protected static ?string $navigationLabel = 'Data Biaya';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Jenis Biaya')
                    ->required(),
                TextInput::make('nominal')
                    ->label('Nominal')
                    ->prefix('Rp.')
                    ->numeric(),
                Toggle::make('status')
                    ->label('Status')
                    ->default(true),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('nominal')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
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
            'index' => ListBiayas::route('/'),
            'create' => CreateBiaya::route('/create'),
            'edit' => EditBiaya::route('/{record}/edit'),
        ];
    }
}
