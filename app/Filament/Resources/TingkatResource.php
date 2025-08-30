<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TingkatResource\Pages\ListTingkats;
use App\Filament\Resources\TingkatResource\Pages\CreateTingkat;
use App\Filament\Resources\TingkatResource\Pages\EditTingkat;
use App\Filament\Resources\TingkatResource\Pages;
use App\Filament\Resources\TingkatResource\RelationManagers;
use App\Models\Tingkat;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TingkatResource extends Resource
{
    protected static ?string $model = Tingkat::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube-transparent';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data Akademik';
    protected static ?string  $navigationLabel = 'Tingkat';

    protected static ?string $breadcrumb = 'tingkat';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_tingkat')
                    ->unique(ignoreRecord:true)
                    ->numeric()
                    ->required(),
                TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_tingkat')
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
                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->iconPosition('after'),
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
            'index' => ListTingkats::route('/'),
            'create' => CreateTingkat::route('/create'),
            'edit' => EditTingkat::route('/{record}/edit'),
        ];
    }
}
