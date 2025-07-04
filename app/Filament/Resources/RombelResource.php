<?php

namespace App\Filament\Resources;

use App\Enums\StatusRombel;
use App\Enums\TingkatKelas;
use App\Filament\Resources\RombelResource\Pages;
use App\Filament\Resources\RombelResource\RelationManagers;
use App\Models\Jurusan;
use App\Models\Rombel;
use App\Traits\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Rombel';
    
    protected static ?string $navigationGroup = 'Jurusan/Kelas/Mapel';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('jurusan:id,nama,kode');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->options(TahunAjaran::tahun_ajaran())
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Rombel')
                    ->helperText('Harus unik dan sesuai jurusan "contoh: IPA-1, IPS-2"')
                    ->required(),
                Forms\Components\Select::make('tingkat_id')
                    ->label('Tingkat Kelas')
                    ->options(TingkatKelas::options())
                    ->required(),
                Forms\Components\Select::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Active')
                    ->options(StatusRombel::options())
                    ->default(StatusRombel::NONAKTIF),
                 Forms\Components\Select::make('rombel_biayas')
                    ->label('Biaya')
                    ->multiple()
                    ->relationship('biayas', 'name')
                    ->preload(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status'),
                Tables\Columns\TextColumn::make('tingkat_id')
                    ->label('Tingkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan.nama')
                    ->description(fn(Rombel $record)=>"Kode jurusan:".' ' .$record->jurusan->kode)
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
            'index' => Pages\ListRombels::route('/'),
            'create' => Pages\CreateRombel::route('/create'),
            'edit' => Pages\EditRombel::route('/{record}/edit'),
        ];
    }
}
