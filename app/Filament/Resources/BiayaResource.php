<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaResource\Pages;
use App\Filament\Resources\BiayaResource\RelationManagers;
use App\Models\Biaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaResource extends Resource
{
    protected static ?string $model = Biaya::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Biaya';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Jenis Biaya')
                    ->required(),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal')
                    ->prefix('Rp.')
                    ->numeric(),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->default(true),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->prefix('Rp.')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListBiayas::route('/'),
            'create' => Pages\CreateBiaya::route('/create'),
            'edit' => Pages\EditBiaya::route('/{record}/edit'),
        ];
    }
}
