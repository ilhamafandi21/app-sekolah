<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchedullResource\Pages;
use App\Filament\Resources\SchedullResource\RelationManagers;
use App\Models\Schedull;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchedullResource extends Resource
{
    protected static ?string $model = Schedull::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Penjadwalan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->helperText('Contoh : JP-01 (jam pelajaran 1)')
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->rule('regex:/^JP-\d{2}$/')
                    ->validationAttribute('kode')
                    ->validationMessages([
                        'regex' => 'Format kode harus JP- diikuti 2 digit angka, contoh: JP-01',
                    ]),
                Forms\Components\TimePicker::make('start_at')
                    ->required(),
                Forms\Components\TimePicker::make('end_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_at'),
                Tables\Columns\TextColumn::make('end_at'),
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
            'index' => Pages\ListSchedulls::route('/'),
            'create' => Pages\CreateSchedull::route('/create'),
            'edit' => Pages\EditSchedull::route('/{record}/edit'),
        ];
    }
}
