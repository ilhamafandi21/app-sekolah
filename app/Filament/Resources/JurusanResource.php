<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurusanResource\Pages;
use App\Filament\Resources\JurusanResource\Pages\DetailJurusan;
use App\Filament\Resources\JurusanResource\RelationManagers;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                 Forms\Components\Placeholder::make('detail-jurusan')
                    ->visible(fn (string $context) => $context === 'edit') 
                    ->viewData(fn ($record) => ['record' => $record])
                    ->view(DetailJurusan::VIEW_PATH)
                    ->columnSpanFull(),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->relationship('tahun_ajaran', 'thn_ajaran')
                            ->required(),

                        Forms\Components\TextInput::make('nama_jurusan')
                            ->label('Nama Jurusan')
                            ->required(),

                        Forms\Components\Select::make('tingkat_id')
                            ->label('Tingkat')
                            ->relationship('tingkat', 'nama_tingkat')
                            ->required(),

                        Forms\Components\Hidden::make('kode')
                            ->dehydrated()
                            ->nullable(),
                    ]),

                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->default('-')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun_ajaran.thn_ajaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tingkat.nama_tingkat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_jurusan')
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListJurusans::route('/'),
            'create' => Pages\CreateJurusan::route('/create'),
            'edit' => Pages\EditJurusan::route('/{record}/edit'),
        ];
    }
}
