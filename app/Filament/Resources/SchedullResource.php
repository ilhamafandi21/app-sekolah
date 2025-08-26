<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SchedullResource\Pages\ListSchedulls;
use App\Filament\Resources\SchedullResource\Pages\CreateSchedull;
use App\Filament\Resources\SchedullResource\Pages\EditSchedull;
use App\Filament\Resources\SchedullResource\Pages;
use App\Filament\Resources\SchedullResource\RelationManagers;
use App\Models\Schedull;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchedullResource extends Resource
{
    protected static ?string $model = Schedull::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Penjadwalan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->helperText('Contoh : JP-01 (jam pelajaran 1)')
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->rule('regex:/^JP-\d{2}$/')
                    ->validationAttribute('kode')
                    ->validationMessages([
                        'regex' => 'Format kode harus JP- diikuti 2 digit angka, contoh: JP-01',
                    ]),
                TimePicker::make('start_at')
                    ->required(),
                TimePicker::make('end_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('start_at'),
                TextColumn::make('end_at'),
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
            'index' => ListSchedulls::route('/'),
            'create' => CreateSchedull::route('/create'),
            'edit' => EditSchedull::route('/{record}/edit'),
        ];
    }
}
