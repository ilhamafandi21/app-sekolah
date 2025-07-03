<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Jurusan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\TingkatKelas;
use Filament\Resources\Resource;
use function Pest\Laravel\options;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RombelResource\Pages;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelResource\RelationManagers;
use App\Filament\Resources\RombelResource\RelationManagers\SiswasRelationManager;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Rombel';
    
    protected static ?string $navigationGroup = 'Jurusan/Kelas/Mapel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tingkat')
                    ->options(TingkatKelas::options())
                    ->required(),
                Forms\Components\Select::make('jurusan_id')
                    ->options(Jurusan::all()->pluck('nama', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('divisi')
                    ->required(),
                Forms\Components\Select::make('siswas')
                // Forms\Components\CheckboxList::make('subjects')-> hapus multiple dan preload gunakan CheckboxList 
                    ->label('Siswa')
                    ->multiple()
                    ->relationship('siswas', 'nama') // Tetap gunakan ini agar pivot auto disimpan
                    ->options(function () {
                        return Siswa::whereDoesntHave('rombels') // Pastikan relasi `rombels()` ada di model Siswa
                            ->orderBy('nama')
                            ->pluck('nama', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(function () {
                        return Siswa::whereDoesntHave('rombels') // Pastikan relasi `rombels()` ada di model Siswa
                            ->orderBy('nama')
                            ->pluck('nama', 'id');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} ({$record->nis})"), // agar tetap tampil readable
                Forms\Components\TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('divisi')
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
            SiswasRelationManager::class,
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
