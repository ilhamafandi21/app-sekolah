<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPembayaranResource\Pages;
use App\Filament\Resources\JenisPembayaranResource\RelationManagers;
use App\Models\JenisPembayaran;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisPembayaranResource extends Resource
{
    protected static ?string $model = JenisPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-2';

    protected static ?string $navigationLabel = 'Jenis Pembayaran';
    
    protected static ?string $navigationGroup = 'Tata Usaha';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('-')
                    ->required(),
                Forms\Components\Hidden::make('user_id')
                    ->default(Filament::auth()->user()->id)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
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
            'index' => Pages\ListJenisPembayarans::route('/'),
            'create' => Pages\CreateJenisPembayaran::route('/create'),
            'edit' => Pages\EditJenisPembayaran::route('/{record}/edit'),
        ];
    }
}
